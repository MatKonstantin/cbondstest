<template>
    <div :class="table_class" class="table_cntrl" ref="tableCntrl">
        <div v-if="column_order"></div>
        <div class="fixedHeadTable" v-show="fixedThead">
            <div class="fixedHeadTableLeft" v-show="showAdaptive" v-bind:style="{width: widthFixedHeadTableLeft}">
                <table class="table full" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            <div v-bind:style="{height: heightH}">
                                <span v-if="number" class="number">№</span>
                                <a v-if="sort_fields.indexOf(columns[0]) != -1" href="javascript:void(0);" class="sortable" v-on:click="sortByField(columns[0])">
                                    <slot name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                                    <span v-if="columns[0] == sort_field && sort_order == 'asc'">▲</span>
                                    <span v-if="columns[0] == sort_field && sort_order == 'desc'">▼</span>
                                </a>
                                <slot v-else name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                            </div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div ref="fixedHeadTableRight" class="fixedHeadTableRight" v-bind:style="{width: widthFixedHeadTableRight}" v-bind:class="{fieldLR:showAdaptive}">
                <a href="javascript:void(0);" class="arrow_left adaptive" v-if="index > 1"  v-on:click="scrollTableLeft()"></a>
                <a href="javascript:void(0);" class="arrow_right adaptive" v-if="index < countActiveColumn" v-on:click="scrollTableRight()"></a>
                <table class="table" v-bind:style="{'margin-left': indent + 'px'}" border="0" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th v-if="number && !showAdaptive"><div v-bind:style="{width: forFixedThN, height: heightH}">№</div></th>
                        <th v-bind:class="{center_txt:arrow_adaptive}" v-if="!showAdaptive">
                            <div v-bind:style="{width: forFixedThF, height: heightH}">
                                <a v-if="sort_fields.indexOf(columns[0]) != -1" href="javascript:void(0);" class="sortable" v-on:click="sortByField(columns[0])">
                                    <slot name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                                    <span v-if="columns[0] == sort_field && sort_order == 'asc'">▲</span>
                                    <span v-if="columns[0] == sort_field && sort_order == 'desc'">▼</span>
                                </a>
                                <slot v-else name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                            </div>
                        </th>
                        <th v-for="(key, index) in columns" v-bind:class="[{center_txt:arrow_adaptive}, 'field_' + key]" v-if="index > 0">
                            <div v-bind:style="{width: forFixedTh[index], height: heightH}">
                                <a v-if="sort_fields.indexOf(key) != -1" href="javascript:void(0);" class="sortable" v-on:click="sortByField(key)">
                                    <slot name="header" :field="key" :value="header[key]" :row="header"><span v-html="header[key]"></span></slot>
                                    <span v-if="key == sort_field && sort_order == 'asc'">▲</span>
                                    <span v-if="key == sort_field && sort_order == 'desc'">▼</span>
                                </a>
                                <slot v-else name="header" :field="key" :value="header[key]" :row="header"><span v-html="header[key]"></span></slot>
                            </div>
                        </th>
                        <template v-if="group && showAdaptive && prestrings[index]">
                            <th><div v-bind:style="{width: forFixedThG, height: heightH}">&nbsp;</div></th>
                        </template>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        <div ref="theadLeftBlock" class="table_adaptive" v-show="showAdaptive" v-bind:style="{width: widthScreen}">
            <table class="table full" border="0" cellpadding="0" cellspacing="0">
                <thead>
                <tr ref="trFixedHead">
                    <th>
                        <div v-bind:style="{height: heightH}">
                            <span v-if="number" class="number">№</span>
                            <a v-if="sort_fields.indexOf(columns[0]) != -1" href="javascript:void(0);" class="sortable" v-on:click="sortByField(columns[0])">
                                <slot name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                                <span v-if="columns[0] == sort_field && sort_order == 'asc'">▲</span>
                                <span v-if="columns[0] == sort_field && sort_order == 'desc'">▼</span>
                            </a>
                            <slot v-else name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="prev && showAdaptive" class="openOther" ref="trFixedBodyGroupPr">
                    <td>
                        <div v-bind:style="{height: heightBGPr}">
                            <a href="javascript:void(0);" v-on:click="showPrev" v-bind:class="{show_hide_block:prevInner, hide_hide_block:!prevInner}">
                                <template v-if="!prevInner">{{ getLabel('link_show_prevs') }}</template>
                                <template v-if="prevInner">{{ getLabel('hide') }}</template>
                            </a>
                        </div>
                    </td>
                </tr>
                <template v-for="(item, index) in body">
                    <template v-if="index < innerLimit">
                        <tr :key="'trFixedBodyPrestrings' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="prestrings[index]" ref="trFixedBodyPrestrings"  v-on:click="setActiveColor(item)">
                            <td class="small_txt" v-bind:class="{group_tr:collapse}">
                                <div v-if="collapse" v-bind:style="{height: heightBP[index]}">
                                    <a href="javascript:void(0);" class="group show_hide_block" v-on:click.prevent="switchGroupList(index)" v-html="prestrings[index]"></a>
                                </div>
                                <div v-else v-bind:style="{height: heightBP[index]}" v-html="prestrings[index]"></div>
                            </td>
                        </tr>
                        <template v-if="collapse">
                            <tr :id="table_name +'_fixed_item_' + index" :key="'fixed_' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" ref="trFixedBody" v-show="showTr(item.show) && item.show_group"  v-on:click="setActiveColor(item)">
                                <td>
                                    <div v-bind:style="{height: heightB[index]}">
                                        <span v-if="number" class="number">{{index + 1}}</span>
                                        <a v-bind:href="item.data[columns[0]].lnk" v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[columns[0]])">
                                                <app-asterisks :message="getAsterisksMessage(item.data[columns[0]])"></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr :id="table_name + '_fixed_item_' + index" :key="'fixed_' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" ref="trFixedBody" v-show="showTr(item.show)"  v-on:click="setActiveColor(item)">
                                <td>
                                    <div v-bind:style="{height: heightB[index]}">
                                        <span v-if="number" class="number">{{index + 1}}</span>
                                        <a v-bind:href="item.data[columns[0]].lnk" v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[columns[0]])">
                                                <app-asterisks :message="getAsterisksMessage(item.data[columns[0]])"></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template v-else>
                        <tr :key="'trFixedBodyPrestrings' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="prestrings[index]" ref="trFixedBodyPrestrings"  v-on:click="setActiveColor(item)">
                            <td class="small_txt">
                                <div v-if="collapse" v-bind:style="{height: heightBP[index]}">
                                    <a href="javascript:void(0);" class="group show_hide_block" v-on:click.prevent="switchGroupList(index)" v-html="prestrings[index]"></a>
                                </div>
                                <div v-else v-bind:style="{height: heightBP[index]}" v-html="prestrings[index]"></div>
                            </td>
                        </tr>
                        <template v-if="collapse">
                            <tr :id="table_name + '_fixed_item_' + index" :key="'trFixedBody' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" ref="trFixedBody" v-show="showTr(item.show) && show_tr && item.show_group"  v-on:click="setActiveColor(item)">
                                <td>
                                    <div v-bind:style="{height: heightB[index]}">
                                        <span v-if="number" class="number">{{index + 1}}</span>
                                        <a v-bind:href="item.data[columns[0]].lnk" v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[columns[0]])">
                                                <app-asterisks :message="getAsterisksMessage(item.data[columns[0]])"></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr :id="table_name + '_fixed_item_' + index" :key="'trFixedBody' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" ref="trFixedBody" v-show="showTr(item.show) && show_tr"  v-on:click="setActiveColor(item)">
                                <td>
                                    <div v-bind:style="{height: heightB[index]}">
                                        <span v-if="number" class="number">{{index + 1}}</span>
                                        <a v-bind:href="item.data[columns[0]].lnk" v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[columns[0]])">
                                                <app-asterisks :message="getAsterisksMessage(item.data[columns[0]])"></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                </template>
                <tr v-if="next && showAdaptive" class="openOther" ref="trFixedBodyGroupNx">
                    <td>
                        <div v-bind:style="{height: heightBGNx}">
                            <a href="javascript:void(0);" v-on:click="showNext" v-bind:class="{show_hide_block:!nextInner, hide_hide_block:nextInner}">
                                <template v-if="!nextInner">{{ getLabel('link_show_nexts') }}</template>
                                <template v-if="nextInner">{{ getLabel('hide') }}</template>
                            </a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <a href="javascript:void(0);" class="arrow_right_screen" v-bind:class="{arrow_right_screen_multy:arrow_multy}" v-if="arrow_screen && arrow_screen_right" v-on:mousedown="startScrollR" v-on:mouseleave="stopScroll" v-on:mouseup="stopScroll" v-on:touchstart="startScrollR" v-on:touchend="stopScroll" v-bind:style="{top: heightH, height: height_body + 'px'}"></a>
        <a href="javascript:void(0);" class="arrow_left_screen" v-if="arrow_screen && arrow_screen_left" v-bind:class="{arrow_left_screen_multy:arrow_multy}" v-on:mousedown="startScrollL" v-on:mouseleave="stopScroll" v-on:mouseup="stopScroll" v-on:touchstart="startScrollL" v-on:touchend="stopScroll" v-bind:style="{top: heightH, height: height_body + 'px'}"></a>
        <a href="javascript:void(0);" class="arrow_left adaptive" v-if="arrow_adaptive && index > 1" v-on:click="scrollTableLeft"></a>
        <a href="javascript:void(0);" class="arrow_right adaptive" v-if="arrow_adaptive && index < countActiveColumn" v-on:click="scrollTableRight"></a>
        <div v-bind:class="{roll_table: showAdaptive}" v-bind:style="{width: widthScreen}" ref="table_wrapper">
            <table ref="table_scroll" class="table full" v-bind:class="{indent:arrow_screen}" v-bind:style="{width: widthFull, 'margin-left': indent + 'px'}" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr ref="trScrollHead">
                    <th ref="width_th_scroll_number" v-if="number && !showAdaptive"><div v-bind:style="{height: heightH}">№</div></th>
                    <th ref="width_th_scroll_first" v-bind:class="{center_txt:arrow_adaptive}" v-if="!showAdaptive">
                        <div v-bind:style="{width: width, height: heightH}">
                            <a v-if="sort_fields.indexOf(columns[0]) != -1" href="javascript:void(0);" class="sortable" v-on:click="sortByField(columns[0])">
                                <slot name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                                <span v-if="columns[0] == sort_field && sort_order == 'asc'">▲</span>
                                <span v-if="columns[0] == sort_field && sort_order == 'desc'">▼</span>
                            </a>
                            <slot v-else name="header" :field="columns[0]" :value="header[columns[0]]" :row="header"><span v-html="header[columns[0]]"></span></slot>
                        </div>
                    </th>
                    <th ref="width_th_scroll" v-for="(key, index) in columns" v-bind:class="[(arrow_adaptive ? 'center_txt' : ''), 'field_' + key]" v-if="index > 0">
                        <div v-bind:style="{width: width, height: heightH}">
                            <a v-if="sort_fields.indexOf(key) != -1" href="javascript:void(0);" class="sortable" v-on:click="sortByField(key)">
                                <slot name="header" :field="key" :value="header[key]" :row="header"><span v-html="header[key]"></span></slot>
                                <span v-if="key == sort_field && sort_order == 'asc'">▲</span>
                                <span v-if="key == sort_field && sort_order == 'desc'">▼</span>
                            </a>
                            <slot v-else name="header" :field="key" :value="header[key]" :row="header"><span v-html="header[key]"></span></slot>
                        </div>
                    </th>
                    <template v-if="group && showAdaptive && substrings.length > 0  && !prestrings[index]">
                        <th ref="width_th_scroll_group"><div v-bind:style="{width: width, height: heightH}">&nbsp;</div></th>
                    </template>
                </tr>
            </thead>
            <tbody>
            <tr v-show="prev" class="openOther">
                <td v-bind:colspan="colspan" class="center_txt">
                    <div v-bind:style="{height: heightBGPr}">
                        <a href="javascript:void(0);" v-on:click="showPrev" v-bind:class="{show_hide_block:prevInner, hide_hide_block:!prevInner}" v-if="!showAdaptive">
                            <template v-if="!prevInner">{{ getLabel('link_show_prevs') }}</template>
                            <template v-if="prevInner">{{ getLabel('hide') }}</template>
                        </a>
                    </div>
                </td>
            </tr>
            <template v-for="(item, index) in body">
                <template v-if="index < innerLimit">
                    <tr v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="prestrings[index] && !showAdaptive" v-on:click="setActiveColor(item)">
                        <template v-if="collapse">
                            <td v-bind:colspan="colspan" class="group_tr">
                                <div v-bind:style="{height: heightBP[index]}">
                                    <a href="javascript:void(0);" class="group show_hide_block" v-on:click.prevent="switchGroupList(index)" v-html="prestrings[index]"></a>
                                </div>
                            </td>
                        </template>
                        <template v-else>
                            <td v-bind:colspan="colspan" class="small_txt 2" v-html="prestrings[index]"></td>
                        </template>
                    </tr>
                    <tr v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="prestrings[index] && showAdaptive"  v-on:click="setActiveColor(item)">
                        <template v-if="collapse">
                            <td v-bind:colspan="colspan" class="group_tr" v-if="columns.length > 1">
                                <div v-bind:style="{width: width, height: heightBP[index]}"></div>
                            </td>
                        </template>
                        <template v-else>
                            <td v-bind:colspan="colspan" class="small_txt">
                                <div v-bind:style="{width: width, height: heightBP[index]}"></div>
                            </td>
                        </template>
                    </tr>
                    <template v-if="collapse">
                        <tr :id="table_name + '_scroll_item_' + index" :key="'trScrollBody' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="showPreviousElements(index)" ref="trScrollBody" v-show="showTr(item.show)  && item.show_group" v-on:click="setActiveColor(item)">
                            <td v-if="number && !showAdaptive">{{index + 1}}</td>
                            <td v-bind:class="{center_txt:arrow_adaptive}" v-if="!showAdaptive">
                                <div v-bind:style="{width: width, height: heightB[0]}">
                                    <template v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'">
                                        <a v-if="item.data[columns[0]].lnk" v-bind:href="item.data[columns[0]].lnk" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[columns[0]].ttl)">
                                                <app-asterisks></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]].ttl" :row="item.data"><span v-html="item.data[columns[0]].ttl"></span></slot>
                                        </template>
                                        <div class="small" v-if="item.data[columns[0]].info" v-html="item.data[columns[0]].info"></div>
                                    </template>
                                    <template v-else>
                                        <template v-if="getAsterisks(item.data[columns[0]])">
                                            <app-asterisks :message="getAsterisksMessage(item.data[columns[0]])"></app-asterisks>
                                        </template>
                                        <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                                    </template>
                                </div>
                            </td>
                            <td v-for="(key, indexInner) in columns" v-bind:class="[(arrow_adaptive ? 'center_txt' : ''), 'field_' + key]" v-if="indexInner > 0">
                                <div v-bind:style="{width: width, height: heightB[index]}" v-bind:title="getTitle(item.data[key])">
                                    <template v-if="!!item.data[key] && typeof(item.data[key]) === 'object'">
                                        <a v-if="item.data[key].lnk" v-bind:href="item.data[key].lnk" target="_blank" v-html="item.data[key].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[key].ttl)">
                                                <app-asterisks></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="key" :value="item.data[key].ttl" :row="item.data"><span v-html="item.data[key].ttl"></span></slot>
                                        </template>
                                        <div class="small" v-if="item.data[key].info" v-html="item.data[key].info"></div>
                                    </template>
                                    <template v-else>
                                        <template v-if="getAsterisks(item.data[key])">
                                            <app-asterisks :message="getAsterisksMessage(item.data[key])"></app-asterisks>
                                        </template>
                                        <slot v-else name="field" :field="key" :value="filterLength(item.data[key], key)" :row="item.data">
                                            <span v-html="filterLength(item.data[key], key)"></span>
                                        </slot>
                                    </template>
                                    <template v-if="filterLength(item.data[key], key).length > 80 && arrow_adaptive">
                                        <div class="showMore clear top_5">
                                            <a href="javascript:void(0);" class="small" v-on:click.prevent="setFullDescrTableMobile(item.data[key])">{{label_show_more}}</a>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <template v-if="group && showAdaptive && !next && !prev && substrings.length > 0">
                                <td v-if="substrings[index] != ''">
                                    <template v-if="getAsterisks(substrings[index])">
                                        <app-asterisks></app-asterisks>
                                    </template>
                                    <template v-else>
                                        <div v-bind:style="{height: heightB[index]}" v-html="substrings[index]"></div>
                                    </template>
                                </td>
                                <td v-else><div v-bind:style="{height: heightB[index]}">&nbsp;</div></td>
                            </template>
                        </tr>
                    </template>
                    <template v-else>
                        <tr :id="table_name + '_scroll_item_' + index" :key="'trScrollBody' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="showPreviousElements(index)" ref="trScrollBody" v-show="showTr(item.show)" v-on:click="setActiveColor(item)">
                            <td v-if="number && !showAdaptive">{{index + 1}}</td>
                            <td v-bind:class="{center_txt:arrow_adaptive}" v-if="!showAdaptive">
                                <div v-bind:style="{width: width, height: heightB[0]}">
                                    <template v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'">
                                        <a v-if="item.data[columns[0]].lnk" v-bind:href="item.data[columns[0]].lnk" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[columns[0]].ttl)">
                                                <app-asterisks></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]].ttl" :row="item.data"><span v-html="item.data[columns[0]].ttl"></span></slot>
                                        </template>
                                        <div class="small" v-if="item.data[columns[0]].info" v-html="item.data[columns[0]].info"></div>
                                    </template>
                                    <template v-else>
                                        <template v-if="getAsterisks(item.data[columns[0]])">
                                            <app-asterisks :message="getAsterisksMessage(item.data[columns[0]])"></app-asterisks>
                                        </template>
                                        <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                                    </template>
                                </div>
                            </td>
                            <td v-for="(key, indexInner) in columns" v-bind:class="[(arrow_adaptive ? 'center_txt' : ''), 'field_' + key]" v-if="indexInner > 0">
                                <div v-bind:style="{width: width, height: heightB[index]}" v-bind:title="getTitle(item.data[key])">
                                    <template v-if="!!item.data[key] && typeof(item.data[key]) === 'object'">
                                        <a v-if="item.data[key].lnk" v-bind:href="item.data[key].lnk" target="_blank" v-html="item.data[key].ttl"></a>
                                        <template v-else>
                                            <template v-if="getAsterisks(item.data[key].ttl)">
                                                <app-asterisks></app-asterisks>
                                            </template>
                                            <slot v-else name="field" :field="key" :value="item.data[key].ttl" :row="item.data"><span v-html="item.data[key].ttl"></span></slot>
                                        </template>
                                        <div class="small" v-if="item.data[key].info" v-html="item.data[key].info"></div>
                                    </template>
                                    <template v-else>
                                        <template v-if="getAsterisks(item.data[key])">
                                            <app-asterisks :message="getAsterisksMessage(item.data[key])"></app-asterisks>
                                        </template>
                                        <slot v-else name="field" :field="key" :value="filterLength(item.data[key], key)" :row="item.data">
                                            <span v-html="filterLength(item.data[key], key)"></span>
                                        </slot>
                                    </template>
                                    <template v-if="filterLength(item.data[key], key).length > 80 && arrow_adaptive">
                                        <div class="showMore clear top_5">
                                            <a href="javascript:void(0);" class="small" v-on:click.prevent="setFullDescrTableMobile(item.data[key])">{{label_show_more}}</a>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <template v-if="group && showAdaptive && !next && !prev && substrings.length > 0">
                                <td v-if="substrings[index] != ''">
                                    <template v-if="getAsterisks(substrings[index])">
                                        <app-asterisks></app-asterisks>
                                    </template>
                                    <template v-else>
                                        <div v-bind:style="{height: heightB[index]}" >
                                            <span v-html="filterLength(substrings[index])"></span>
                                            <div class="showMore clear top_5">
                                                <a href="javascript:void(0);" class="small" v-on:click.prevent="setFullDescrTableMobile(substrings[index])">{{label_show_more}}</a>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td v-else><div v-bind:style="{height: heightB[index]}">&nbsp;</div></td>
                            </template>
                        </tr>
                        <tr v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="group && !showAdaptive && showPreviousElements(index)" ref="trScrollBody" v-show="showTr(item.show)" v-on:click="setActiveColor(item)">
                            <template v-if="getAsterisks(substrings[index])">
                                <td v-bind:colspan="colspan" class="small_txt"><app-asterisks></app-asterisks></td>
                            </template>
                            <template v-else>
                                <td v-bind:colspan="colspan" class="small_txt" v-html="substrings[index]"></td>
                            </template>
                        </tr>
                    </template>
                </template>
                <template v-else>
                    <tr v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-on:click="setActiveColor(item)" v-if="prestrings[index] && !showAdaptive">
                        <template v-if="getAsterisks(prestrings[index])">
                            <td v-bind:colspan="colspan" class="small_txt"><app-asterisks></app-asterisks></td>
                        </template>
                        <template v-else>
                            <td v-bind:colspan="colspan" class="small_txt" v-html="prestrings[index]"></td>
                        </template>
                    </tr>
                    <tr v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-on:click="setActiveColor(item)" v-if="prestrings[index] && showAdaptive">
                        <td v-bind:colspan="colspan" class="small_txt">
                            <div v-bind:style="{width: width, height: heightBP[index]}"></div>
                        </td>
                    </tr>
                    <tr :id="table_name + '_scroll_item_' + index" :key="'trScrollBody' + index" v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="showPreviousElements(index)" ref="trScrollBody" v-show="showTr(item.show) && show_tr" v-on:click="setActiveColor(item)">
                        <td v-if="number && !showAdaptive">{{index + 1}}</td>
                        <td v-bind:class="{center_txt:arrow_adaptive}" v-if="!showAdaptive">
                            <div v-bind:style="{width: width, height: heightB[0]}">
                                <template v-if="!!item.data[columns[0]] && typeof(item.data[columns[0]]) === 'object'">
                                    <a v-if="item.data[columns[0]].lnk" v-bind:href="item.data[columns[0]].lnk" target="_blank" v-html="item.data[columns[0]].ttl"></a>
                                    <template v-else>
                                        <template v-if="getAsterisks(item.data[columns[0]].ttl)">
                                            <app-asterisks></app-asterisks>
                                        </template>
                                        <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]].ttl" :row="item.data"><span v-html="item.data[columns[0]].ttl"></span></slot>
                                    </template>
                                    <div class="small" v-if="item.data[columns[0]].info" v-html="item.data[columns[0]].info"></div>
                                </template>
                                <slot v-else name="field" :field="columns[0]" :value="item.data[columns[0]]" :row="item.data"><span v-html="item.data[columns[0]]"></span></slot>
                            </div>
                        </td>
                        <td v-for="(key, indexInner) in columns" :key="'scroll_' + indexInner" v-bind:class="[(arrow_adaptive ? 'center_txt' : ''), 'field_' + key]" v-if="indexInner > 0">
                            <div v-bind:style="{width: width, height: heightB[index]}" v-bind:title="getTitle(item.data[key])">
                                <template v-if="!!item.data[key] && typeof(item.data[key]) === 'object'">
                                    <a v-if="item.data[key].lnk" v-bind:href="item.data[key].lnk" target="_blank" v-html="item.data[key].ttl"></a>
                                    <template v-else>
                                        <template v-if="getAsterisks(item.data[key].ttl)">
                                            <app-asterisks></app-asterisks>
                                        </template>
                                        <slot v-else name="field" :field="key" :value="item.data[key].ttl" :row="item.data"><span v-html="item.data[key].ttl"></span></slot>
                                    </template>
                                    <div class="small" v-if="item.data[key].info" v-html="item.data[key].info"></div>
                                </template>
                                <template v-else>
                                    <template v-if="getAsterisks(item.data[key])">
                                        <app-asterisks :message="getAsterisksMessage(item.data[key])"></app-asterisks>
                                    </template>
                                    <slot v-else name="field" :field="key" :value="filterLength(item.data[key], key)" :row="item.data">
                                        <span v-html="filterLength(item.data[key], key)"></span>
                                    </slot>
                                </template>
                                <template v-if="filterLength(item.data[key], key).length > 80 && arrow_adaptive">
                                    <div class="showMore clear top_5">
                                        <a href="javascript:void(0);" class="small" v-on:click.prevent="setFullDescrTableMobile(item.data[key])">{{label_show_more}}</a>
                                    </div>
                                </template>
                            </div>
                        </td>
                        <template v-if="group && showAdaptive && substrings.length > 0">
                            <td v-if="substrings[index] != ''">
                                <template v-if="getAsterisks(substrings[index])">
                                    <app-asterisks></app-asterisks>
                                </template>
                                <template v-else>
                                    <div v-bind:style="{height: heightB[index]}" v-html="substrings[index]"></div>
                                </template>
                            </td>
                            <td v-else><div v-bind:style="{height: heightB[index]}">&nbsp;</div></td>
                        </template>
                    </tr>
                    <tr v-bind:class="[item.class, getOdd(index), {click_color: activeClickRow == item ? true : false}]" v-if="group && !showAdaptive && showPreviousElements(index)" ref="trScrollBody" v-show="showTr(item.show)&& index && show_tr" v-on:click="setActiveColor(item)">
                        <template v-if="getAsterisks(substrings[index])"><td v-bind:colspan="colspan" class="small_txt"><app-asterisks></app-asterisks></td></template>
                        <template v-else><td v-bind:colspan="colspan" class="small_txt" v-html="substrings[index]"></td></template>
                    </tr>
                </template>
            </template>
            <tr v-if="showSumm">
                <template v-for="(item) in columns">
                    <td>{{ getSumm(item) }}</td>
                </template>
            </tr>
            <tr v-show="next" class="openOther">
                <td v-bind:colspan="colspan" class="center_txt">
                    <div v-bind:style="{height: heightBGNx}">
                        <a href="javascript:void(0);" v-on:click="showNext" v-bind:class="{show_hide_block:!nextInner, hide_hide_block:nextInner}" v-if="!showAdaptive">
                            <template v-if="!nextInner">{{ getLabel('link_show_nexts') }}</template>
                            <template v-if="nextInner">{{ getLabel('hide') }}</template>
                        </a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
        <div class="clear"></div>
        <div class="top_20" v-if="limit != 0 && limit < data.length">
            <template v-cloak>
                <a href="javascript:void(0);" v-if="!show_tr" class="show_hide_block" v-on:click.prevent="switchTr">{{label_show_more}}</a>
                <a href="javascript:void(0);" v-if="show_tr" class="hide_hide_block" v-on:click.prevent="switchTr">{{label_hide}}</a>
            </template>
        </div>
        <div class="fullDescrTableMobile" v-if="showFullDescrTableMobile">
            <a href="javascript:void(0);" class="close" v-on:click.prevent="showFullDescrTableMobile=false">{{label_hide}}</a>
            <div v-html="textFullDescrTableMobile"></div>
        </div>
    </div>
</template>

<script>
    import AppAsterisks from './AppAsterisks.vue';

    let labelsSource = '';
    if (typeof labels !== "undefined") {
        labelsSource = labels;
    }

    export default {
        name: "AppTableNew",
        components: {
            'app-asterisks': AppAsterisks
        },
        props: {
            table_name: {default: '', type: String}, // класс таблицы
            limit: {default: 0, type: Number}, // класс таблицы,
            table_class: {default: '', type: String}, // класс таблицы
            number: {default: false, type: Boolean}, // флаг того что в первой ячейке порядковый номер
            group: {default: false, type: Boolean},
            collapse: {default: false, type: Boolean},
            labels: {type: Object, default: function () { return {} }}, // ассоц массив лейблов
            column_order: {default: false, type: Boolean}, // флаг фиксировать заголовок таблицы
            fixed_head: {default: false, type: Boolean}, // флаг фиксировать заголовок таблицы
            fields: {type: Array, default: function () { return [] }}, // список колонок
            data: {type: Array, default: function () { return [] }}, // данные таблицы
            data_ext: {type: Array, default: function () { return [] }}, // данные таблицы в расширенном формате
            fields_labels: {type: Object, default: function () { return {} }}, // список лейблов для колонок
            fields_classes: {type: Object, default: function () { return {} }}, // список классов для колонок
            fields_labels_prefix: {type: String, default: ''}, // префикс лейблов для полей при автоматической подстановке (если не задан массив fields_labels)
            field_customize: {type: Function, default: function (field, row) { return row[field]; }}, // кастомная функция форматирования поля
            no_hidden_empty_cols: {type: Boolean, default: false}, // флаг не скрывать пустые столбцы
            never_hidden_empty_cols_list: { type: Array, default: () => [] }, // флаг не скрывать пустые столбцы
            sort_fields: {default: function () { return [] },type: Array,}, // поля для которых будет добавлено управление сортировкой
            sort: {default: '',type: String}, // поле сортировки по умолчанию
            order: {default: '',type: String}, // направление сортировки по умолчанию (asc/desc)
            need_arrows: {default: false, type: Boolean},
            arrows_names: {type: Object, default: function () { return {} }}, // неименования стрелок
            substrings: {type: Array, default: function () { return [] }}, // подстроки
            show_elements: {type: Array, default() { return [] }}, // подстроки
            prestrings: {type: Array, default: function () { return [] }}, // выводится перед строкой
            reduction: {default: true, type: Boolean},
            summ_columns: {type: Array, default: function () { return [] }}, // для каких колонок надо выводить сумму
        },
        data: function () {
            return {
                innerLimit: this.limit > 0 ? this.limit : this.data.length,
                showPrevious: false,
                heightHead: '',
                heightBody: [],
                heightBodyGroupPr: '',
                heightBodyGroupNx: '',
                headFixed: '',
                headScroll: '',
                heightBPrestring: [],
                bodyFixed: '',
                bodyScroll: '',
                lengthFixed: 0,
                arrow_adaptive: false,
                arrow_screen: false,
                arrow_screen_right: false,
                arrow_screen_left: false,
                height_body: 0,
                indent: 0,
                index: 1,
                widthDiv: '',
                widthTd: '',
                widthTable: '',
                widthOld: 0,
                timerLeft: '',
                timerRight: '',
                colspan: 0,
                showAdaptive: false,
                prevInner: false,
                nextInner: false,
                fixedThead: false,
                fixedTheadTop: 0,
                forFixedTh: [],
                forFixedThN: '',
                forFixedThF: '',
                forFixedThG: '',
                sort_field: this.sort,
                sort_order: this.order,
                countActiveColumn: 0,
                show_tr: false,
                label_show_more: typeof (labels.show_more) != 'undefined' ? labels.show_more : 'Show more',
                label_hide: typeof (labels.hide) != 'undefined' ? labels.hide : 'Hide',
                bodyFixedPrestrings: [],
                arrow_multy: false,
                widthFixedHeadTableLeft: '',
                widthFixedHeadTableRight: '',
                tableHB: '',
                maxScrollTable: '',
                showFullDescrTableMobile: false,
                textFullDescrTableMobile: '',
                activeClickRow: ''
            }
        },
        watch: {
            data: function () {
                this.innerLimit = this.limit > 0 ? this.limit : this.data.length;
            }
        },
        computed: {
            isSubstringTableType() {
                return this.substrings.length > 0;
            },
            isPrestringTableType() {
                return this.prestrings.length > 0;
            },
            header: function () {
                if (Object.keys(this.fields_labels).length) {
                    return this.fields_labels;
                } else {
                    var fields_labels = {};
                    for (var f in this.columns) {
                        fields_labels[this.columns[f]] = this.getFieldLabel(this.columns[f]);
                    }
                    return fields_labels;
                }
            },
            columns: function () {
                this.colspan = this.fields.length;
                if (!!this.fields) {
                    if (this.no_hidden_empty_cols) {
                        return this.fields;
                    } else {
                        var columnFill = {};
                        for (var i in this.body) {
                            for (var j in this.fields) {
                                var value = this.body[i].data[this.fields[j]];
                                if (this.never_hidden_empty_cols_list.length > 0
                                    && this.never_hidden_empty_cols_list.includes(this.fields[j])
                                ) {
                                    columnFill[j] = this.fields[j];
                                } else if (value && value !== '0') {
                                    columnFill[j] = this.fields[j];
                                }
                            }
                        }
                        return Object.values(columnFill);
                    }
                }
                return this.fields;
            },
            body: function () {
                let self = this,
                    dataExt = [];
                if (self.data_ext.length) {
                    dataExt = self.data_ext;
                } else if (self.data.length) {
                    dataExt = self.data.map(function (row) {
                        let rowExt = {data: row};
                        if (!!row.class) {
                            rowExt.class = row.class;
                        }
                        if (!!row.show) {
                            rowExt.show = row.show;
                        }
                        if (!!row.colspan) {
                            rowExt.colspan = row.colspan;
                        }
                        return rowExt;
                    });
                }
                for (let y in dataExt) {
                    for (var field in dataExt[y].data) {
                        if (!/\.numeric$/.test(field)) {
                            dataExt[y].data[field] = self.field_customize(field, dataExt[y].data);
                        }
                    }
                }
                return dataExt;
            },
            heightH: function () {
                return this.heightHead;
            },
            heightB: function () {
                return this.heightBody;
            },
            heightBGPr: function () {
                return this.heightBodyGroupPr;
            },
            heightBGNx: function () {
                return this.heightBodyGroupNx;
            },
            width: function(){
                if(this.widthDiv == '')
                    return '';
                else
                    return this.widthDiv + 'px';
            },
            widthScreen: function(){
                if(this.widthTd == '')
                    return '';
                else
                    return this.widthTd + 'px';
            },
            widthFull: function () {
                return this.widthTable;
            },
            prev: function () {
                for (var i in this.body) {
                    if (!!this.body[i].show && this.body[i].show == 'prev') {
                        return true;
                    }
                }
                return false;
            },
            next: function () {
                for (var i in this.body) {
                    if (!!this.body[i].show && this.body[i].show == 'next') {
                        return true;
                    }
                }
                return false;
            },
            heightBP() {
                return this.heightBPrestring;
            },
        },
        mounted: function () {
            let self = this;
            self.headFixed = self.$refs.trFixedHead;
            self.bodyFixed = self.$refs.trFixedBody;
            self.headScroll = self.$refs.trScrollHead;
            self.bodyScroll = self.$refs.trScrollBody;
            self.bodyFixedPrestrings = self.$refs.trFixedBodyPrestrings;
            self.$nextTick(function () {
                self.tableHB = (-1) * self.$refs.table_wrapper.clientHeight;
                self.maxScrollTable = self.$refs.table_scroll.clientWidth - self.$refs.table_wrapper.clientWidth;
            });
            window.addEventListener('resize', self.getWindowWidth);
            window.addEventListener('scroll', self.scrollPage);
            self.$refs.table_wrapper.addEventListener('scroll', self.scrollTable);
            self.getWindowWidth();
        },
        created: function () {
            if(this.collapse) {
                let self = this;
                this.body.forEach(function (item) {
                    self.$set(item, 'show_group', true)
                });
            }
            this.widthOld = window.innerWidth;

            this.$resizeTable.$on('refreshTable', this.getWindowWidth, scroll);

            this.colspan = this.columns.length;
            if(this.number)
                ++this.colspan;
        },
        methods: {
            setActiveColor: function(item){
                this.activeClickRow = item;
                console.log(this.activeClickRow == item);
            },
            getTitle: function(item){
                if(typeof(item) === 'object')
                    return item;
                if(!/app-asterisks/.test(item))
                    return item;
                else
                    return '';
            },
            setFullDescrTableMobile: function(item){
                this.textFullDescrTableMobile = item;
                this.showFullDescrTableMobile = true;
            },
            scrollTable: function(){
                this.$refs.fixedHeadTableRight.scrollLeft = this.$refs.table_wrapper.scrollLeft;
            },
            switchGroupList: function(index){
                let self = this,
                    group_param = '';
                for(let i = 0; i < self.prestrings.length; i++) {
                    if(i == index || self.body[i].data.subregion_id == group_param){
                        group_param = self.body[i].data.subregion_id;
                        self.$set(self.body[i], 'show_group', !self.body[i].show_group);
                    }
                }
                this.getWindowWidth();
            },
            switchTr: function(){
                this.show_tr = !this.show_tr;
                this.$nextTick(function () {
                    this.getWindowWidth();
                });
            },
            showPreviousElements: function (elementKey) {
                if (this.need_arrows && !this.show_elements[elementKey] && !this.showPrevious)
                    return false;
                return true;
            },
            scrollPage: function(){
                let top = this.$refs.table_wrapper.getBoundingClientRect().top;
                if (top < 0 && top > this.tableHB) {
                    this.fixedThead = true;
                    this.$refs.fixedHeadTableRight.scrollLeft = this.$refs.table_wrapper.scrollLeft;
                }
                else
                    this.fixedThead = false;
            },
            startScrollR: function(){
                this.stopScroll();
                let self = this,
                    element = self.$refs.table_wrapper,
                    element2 = self.$refs.fixedHeadTableRight,
                    v = element.scrollLeft;

                self.arrow_screen_left = true;
                this.timerRight = setInterval(function () {
                    v+=5;
                    if (v > self.maxScrollTable) {
                        self.arrow_screen_right = false;
                        element.scrollLeft = self.maxScrollTable;
                        element2.scrollLeft = self.maxScrollTable;
                        clearTimeout(self.timerRight);
                        return;
                    }
                    else{
                        element.scrollLeft = v;
                        if(self.fixedThead)
                            element2.scrollLeft = v;
                    }
                }, 1);
            },
            startScrollL: function(){
                this.stopScroll();
                let self = this,
                    element = self.$refs.table_wrapper,
                    element2 = self.$refs.fixedHeadTableRight,
                    v = element.scrollLeft;

                self.arrow_screen_right = true;
                this.timerLeft = setInterval(function () {
                    v-=5;
                    if (v < 5) {
                        self.arrow_screen_left = false;
                        element.scrollLeft = 0;
                        element2.scrollLeft = 0;
                        clearTimeout(self.timerLeft);
                        return;
                    }
                    else{
                        element.scrollLeft = v;
                        if(self.fixedThead)
                            element2.scrollLeft = v;
                    }
                }, 1);
            },
            stopScroll: function(){
                clearTimeout(this.timerLeft);
                clearTimeout(this.timerRight);
            },
            scrollTableRight: function() {
                if(this.index < this.countActiveColumn) {
                    this.index++;
                    this.indent = this.indent - this.widthTd;
                }
            },
            scrollTableLeft: function() {
                if(this.index > 1) {
                    this.index--;
                    this.indent = this.indent + this.widthTd;
                }
            },
            getWindowWidth: function(scroll = true) {
                var self = this;

                self.heightHead = '';
                self.heightBody = [];
                self.heightBPrestring = [];
                self.heightBodyGroupPr = '';
                self.heightBodyGroupNx = '';
                self.showAdaptive = false;
                self.arrow_adaptive = false;
                self.$refs.fixedHeadTableRight.scrollLeft = 0;
                self.$refs.table_wrapper.scrollLeft = 0;
                self.maxScrollTable = self.$refs.table_scroll.clientWidth - self.$refs.table_wrapper.clientWidth;

                if(self.widthOld != window.innerWidth) {
                    self.index = 1;
                    self.indent = 0;
                    self.widthOld = window.innerWidth;
                }

                if (window.innerWidth < self.$store.state.adaptive_width) {
                    self.showAdaptive = true;
                    self.arrow_adaptive = true;
                    self.arrow_screen = false;
                    self.widthTd = parseInt(self.$refs.tableCntrl.clientWidth / 2);
                    self.widthDiv = self.widthTd - 10;
                }
                else {
                    self.widthTd = '';
                    self.widthDiv = '';
                    self.widthTable = '';
                    self.index = 1;
                    self.indent = 0;

                    if (!!self.$refs.table_scroll.clientWidth && !!self.$refs.table_wrapper.clientWidth && self.$refs.table_scroll.clientWidth > self.$refs.table_wrapper.clientWidth) {
                        self.arrow_screen = true;
                        self.arrow_screen_right = true;
                        self.showAdaptive = true;
                    }
                    else {
                        self.arrow_screen = false;
                        self.showAdaptive = false;
                    }
                }

                this.$nextTick(function () {
                    self.height_body = 0;
                    if (window.innerWidth >= self.$store.state.adaptive_width) {
                        if (!!self.$refs.table_scroll.clientWidth && !!self.$refs.table_wrapper.clientWidth && self.$refs.table_scroll.clientWidth > self.$refs.table_wrapper.clientWidth) {
                            self.arrow_screen = true;
                            self.showAdaptive = true;
                        }
                        else {
                            self.arrow_screen = false;
                            self.showAdaptive = false;
                        }
                    }

                    self.lengthFixed = !!self.bodyFixed ? self.bodyFixed.length : 0;
                    self.countActiveColumn = !!self.$refs.width_th_scroll ? self.$refs.width_th_scroll.length : 0;

                    if (window.innerWidth < self.$store.state.adaptive_width)
                        self.widthTable = (self.widthTd * self.countActiveColumn) + 'px';

                    self.forFixedTh = [];
                    self.forFixedTh.push(0);

                    for (let i = 0; i < self.countActiveColumn; ++i) {
                        self.forFixedTh.push(self.$refs.width_th_scroll[i].clientWidth - 10 + 'px');
                    }

                    if (!!self.$refs.width_th_scroll_number)
                        self.forFixedThN = self.$refs.width_th_scroll_number.clientWidth - 10 + 'px';

                    if (!!self.$refs.width_th_scroll_first)
                        self.forFixedThF = self.$refs.width_th_scroll_first.clientWidth - 10 + 'px';

                    if (!!self.$refs.width_th_scroll_group)
                        self.forFixedThG = self.$refs.width_th_scroll_group.clientWidth - 10 + 'px';

                    if (self.showAdaptive) {
                        if (self.group)
                            self.countActiveColumn = self.countActiveColumn + 1;

                        if (self.substrings.length == 0 && self.group)
                            self.countActiveColumn = self.countActiveColumn - 1;

                        if (!!self.$refs.trFixedBodyGroupPr)
                            self.heightBodyGroupPr = self.$refs.trFixedBodyGroupPr.clientHeight - 8 + 'px';

                        if (!!self.$refs.trFixedBodyGroupNx)
                            self.heightBodyGroupNx = self.$refs.trFixedBodyGroupNx.clientHeight - 8 + 'px';

                        let iPre = 0;

                       for (let i = 0; i < self.lengthFixed; ++i) {
                            let fixed = document.getElementById(self.table_name + '_fixed_item_' + i),
                                scroll = document.getElementById(self.table_name + '_scroll_item_' + i);

                            if (fixed.clientHeight > scroll.clientHeight) {
                                self.heightBody[i] = fixed.clientHeight - 4 + 'px';
                                self.height_body += fixed.clientHeight;
                            } else {
                                self.heightBody[i] = scroll.clientHeight - 4 + 'px';
                                self.height_body += scroll.clientHeight;
                            }

                            if (self.isPrestringTableType) {
                                if (self.prestrings[i]) {
                                    let item = self.bodyFixedPrestrings[iPre];
                                    self.heightBPrestring.push(item.clientHeight - 4 + 'px');
                                    self.height_body += item.clientHeight;
                                    iPre++;
                                } else {
                                    self.heightBPrestring.push('auto');
                                }
                            }
                        }

                        if (self.prev && !!self.$refs.trFixedBodyGroup)
                            self.height_body += self.$refs.trFixedBodyGroup.clientHeight + 2;

                        if (self.next && !!self.$refs.trFixedBodyGroup)
                            self.height_body += self.$refs.trFixedBodyGroup.clientHeight + 2;

                        if(self.height_body > window.innerHeight)
                            self.arrow_multy = true;
                        else
                            self.arrow_multy = false;
                    }

                    if (self.headFixed.clientHeight > self.headScroll.clientHeight)
                        self.heightHead = self.headFixed.clientHeight - 10 + 'px';
                    else
                        self.heightHead = self.headScroll.clientHeight - 10 + 'px';

                    self.widthFixedHeadTableLeft = self.$refs.theadLeftBlock.clientWidth + 'px';
                    self.widthFixedHeadTableRight = self.$refs.table_wrapper.clientWidth + 'px';
                    self.maxScrollTable = self.$refs.table_scroll.clientWidth - self.$refs.table_wrapper.clientWidth;
                });
            },
            getOdd: function (i) {
                if (i%2 == 0)
                    return 'odd';
                else
                    return 'even';
            },
            showTr: function(status){
                if(status == 'prev')
                    return this.prevInner;
                else if(status == 'next')
                    return this.nextInner;
                else
                    return true;
            },
            showPrev: function () {
                this.prevInner = !this.prevInner;
                this.getWindowWidth();
            },
            showNext: function () {
                this.nextInner = !this.nextInner;
                this.getWindowWidth();
            },
            getFieldLabel: function (label) {
                var caption = '';

                if (!!this.fields_labels[label])
                    caption = this.fields_labels[label];
                else
                    caption = this.getLabel(this.fields_labels_prefix + label);

                return caption;
            },
            getLabel: function (label) {
                var caption = '{{' + label + '}}';

                if (typeof(this.labels[label]) !== 'undefined' )
                    caption = this.labels[label];

                return caption;
            },
            sortByField: function(field) {
                if (field == this.sort_field && this.sort_order == 'asc')
                    this.sort_order = 'desc';
                else if(field == this.sort_field && this.sort_order == 'desc') {
                    this.sort_field = '';
                    this.sort_order = '';
                } else {
                    this.sort_field = field;
                    this.sort_order = 'asc';
                }
                this.$eventHub.$emit('sortChange', {'field': this.sort_field, 'order': this.sort_order});
            },
            filterLength: function(item, key){
                if (item == null) {
                    return '';
                }
                if (key != 'comment' && this.reduction) {
                    if (item != null) {
                        if (typeof(item) == 'string' && item.replace(/<\/?[^>]+>/gi, '').length > 80)
                            return item.replace(/<\/?[^>]+>/gi, '').substring(0, 80) + '...';
                        else
                            return item;
                    }
                    else
                        return '';
                }
                else
                    return item;
            },
            getAsterisks: function (item) {
                if (typeof(item) != undefined) {
                    if (String(item).indexOf('asterisks') != -1)
                        return true;
                    else
                        return false;
                }
                else
                    return false;
            },
            getAsterisksMessage: function (item) {
                if (typeof(item) != undefined) {
                    if (String(item).includes('forbidden_request'))
                        return 'forbidden_request_label';
                    if (String(item).includes('already_send_request'))
                        return 'already_send_request_label';
                    if (String(item).includes('send_request'))
                        return 'send_request_label';
                }
                return '';
            },
            showSumm: function () {
                return (this.summ_columns.length && this.body.length);
            },
            getSumm: function(key) {
                if (this.summ_columns.indexOf(key) == -1) return '';
                let sum = 0;
                this.body.forEach(function (item) {
                    sum += !isNaN(item['data'][key]) ? +item['data'][key] : 0;
                });
                return sum;
            },
        },
        destroyed: function () {
            this.$resizeTable.$off('refreshTable', this.getWindowWidth, scroll);
            window.removeEventListener('resize', this.getWindowWidth);
            window.removeEventListener('scroll', this.scrollPage);
        }
    }
</script>
<style scoped>
    .table tbody .odd{
        background-color: #f7f7f7 ;
    }
    .table tbody .even{
        background-color: #ffffff ;
    }
    .field_subregion_name {
        white-space: nowrap;
    }
</style>
