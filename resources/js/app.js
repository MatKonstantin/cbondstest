import Vue from 'vue';
import axios from 'axios';
import AppTableNew from './components/AppTableNew.vue';
import store from './store.js';
import * as _ from 'lodash';

var resizeTable = new Vue;
Vue.prototype.$resizeTable = resizeTable;

new Vue({
    el: '#app',
    store,
    components: {
        'app-table-new': AppTableNew,
    },
    data: {
        labels: {id: '#', name: 'Наименование', param1: 'Параметр 1', param2: 'Параметр 2', param3: 'Параметр 3', action: 'Действие'},
        tableFields: ['name', 'param1', 'param2', 'param3', 'action'],
        tableData: [],
        newItem: {
            id: 0,
            name: '',
            param1: '',
            param2: '',
            param3: '',
        },
        summColumns: ['param1', 'param2', 'param3'],
    },
    mounted() {
        this.getTableData();
    },
    methods: {
        appendRow: function () {
            this.newItem.id = 1;
            if (this.tableData.length) {
                this.newItem.id = _.maxBy(this.tableData, 'id').id + 1;
            }
            this.tableData.push(this.newItem);
            this.setTableData();
        },
        removeRow: function (id) {
            _.remove(this.tableData, {id: id});
            this.setTableData();
        },
        setTableData: function () {
            axios.post('/set', {data: this.tableData})
                .then((response) => {
                    this.getTableData();
                })
                .catch((error) => {
                    this.getTableData();
                });
        },
        getTableData: function () {
            axios.get('/get')
                .then((response) => {
                    this.tableData = response.data;
                });
        },
    },
});

