@extends('layouts.base')

@section('title', 'Тестовое задание CBONDS')

@section('content')
    <div class="wrapper_content">
        <div class="content_scroll">
            <div class="content">
                <div class="content_header">
                    <h2 class="content_ttl">Новая запись</h2>
                </div>
                <div class="infoUser">
                    <div class="field" v-for="field in tableFields" v-bind:id="field" v-if="field != 'action'">
                        <div class="lbl indent">@{{ !!labels[field] ? labels[field] : field }}</div>
                        <div class="fld">
                            <input type="text" class="input" v-model="newItem[field]"/>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <a href="#" @click="appendRow()" class="btn_green top_10">Добавить</a>
                </div>
                <div class="content_header top_20">
                    <h2 class="content_ttl">Таблица с данными</h2>
                </div>
                <div v-if="tableData.length">
                    <app-table-new
                        :data="tableData"
                        :fields="tableFields"
                        :fields_labels="labels"
                        :no_hidden_empty_cols="true"
                        :summ_columns="summColumns"
                    >
                        <template slot="field" slot-scope="props">
                            <div v-if="props.field == 'action'">
                               <a href="#" @click="removeRow(props.row.id)">Удалить</a>
                            </div>
                        </template>
                    </app-table-new>

                    <a href="{{ route('export') }}" class="btn_blue top_10">Экспорт в Excel</a>
                </div>
                <div v-else>
                    Нет данных, необходимо добавить
                </div>

            </div>
        </div>
    </div>
@endsection
