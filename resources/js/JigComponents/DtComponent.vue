<template>
    <table
        v-cloak
        v-show="table_ready"
        :id="tableId"
        class="text-left nowrap stripe hover"
        :class="tableClasses"
        width="100%"
    >
        <slot></slot>
    </table>
</template>
<script>
import { onMounted } from "vue";
import { emitter } from "./eventHub.js";
import { defineComponent } from "vue";

export default defineComponent({
    name: "DtComponent",
    props: {
        tableId: {
            required: true,
            type: String,
        },
        tableClasses: {
            required: false,
            default: "",
        },
        processing: {
            type: Boolean,
            required: false,
            default: true,
        },
        serverSide: {
            type: Boolean,
            required: false,
            default: true,
        },
        columns: {
            required: true,
            type: Array,
        },
        columnDefs: {
            required: false,
            type: Array,
            default: () => {
                return [];
            },
        },
        actionButtons: {
            required: false,
            type: Array,
            default: () => {
                return [];
            },
        },
        ajaxUrl: {
            required: true,
            type: String,
        },
        ajaxParams: {
            required: false,
            type: Object,
            default: () => {
                return {};
            },
        },
        tenant: {
            required: false,
            type: String,
            default: null,
        },
        tenantHeaderName: {
            required: false,
            default: null,
        },
        tenantQueryParam: {
            required: false,
            default: null,
        },
        defaultOrderColumn: {
            type: Number,
            default: 0,
        },
        defaultOrderDirection: {
            type: String,
            default: "asc",
        },
        stateSave: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            item_id: null,
            table: null,
            table_ready: false,
            allColumns: [],
        };
    },
    mounted() {
        const vm = this;
        let columns = vm.columns;
        console.log(columns);
        vm.allColumns = columns;
        let colDefs = columns.map((col, idx) => {
            return {
                responsivePriority: col.responsivePriority || -1,
                targets: idx,
            };
        });
        console.log(colDefs);
        $(document).ready(function () {
            vm.table = $(`#${vm.tableId}`).DataTable({
                processing: true,
                serverSide: true,
                stateSave: vm.stateSave,
                responsive: {
                    breakpoints: [
                        { name: "tv", width: Infinity },
                        { name: "desktop-l", width: 1536 },
                        { name: "desktop", width: 1280 },
                        { name: "tablet-l", width: 1024 },
                        { name: "tablet-p", width: 768 },
                        { name: "mobile-l", width: 480 },
                        { name: "mobile-p", width: 320 },
                    ],
                },
                pageLength: 15,
                lengthMenu: [5, 10, 15, 20, 50, 100, 200, 500],
                ajax: {
                    url: vm.ajaxUrl,
                    data: function (d) {
                        for (const [key, value] of Object.entries(
                            vm.ajaxParams
                        )) {
                            d[key] = value;
                        }
                    },
                    beforeSend: function (request) {
                        if (vm.tenant && vm.tenantHeaderName) {
                            request.setRequestHeader(
                                `${vm.tenantHeaderName}`,
                                `${vm.tenant}`
                            );
                        }
                    },
                },
                columns: columns,
                columnDefs: [...vm.columnDefs],
                order: [[vm.defaultOrderColumn, vm.defaultOrderDirection]],
            });
            vm.table.columns.adjust().responsive.recalc();
            vm.table.columns.adjust().responsive.rebuild();
            vm.table.on("click", ".action-button", function (e) {
                var ev = $(this);
                if (ev.data("tag") === "button") {
                    vm.$emit(`${ev.data("action")}`, {
                        id: ev.data("id"),
                    });
                }
            });
            vm.table_ready = true;
        });
        emitter.on("refresh-dt", function (e) {
            if (e.tableId === vm.tableId) {
                //Refresh Table here
                if (vm.table) {
                    vm.table.ajax.reload(null, false);
                }
            }
        });
    },
    methods: {
        makeActionColumn(payload) {
            let vm = this;
            let actions = ``;
            vm.actionButtons.forEach((actionButton, key) => {
                actions = `
                    ${actions}
                    <${actionButton.tag} class="action-button ${actionButton.classes}"
                        href="${actionButton.href}"
                        data-action="${actionButton.event}"
                        data-url="${actionButton.url}"
                        data-id="${payload.id}"
                        data-tag="${actionButton.tag}"
                    ><i v-if="${actionButton.icon}" class="${actionButton.icon}"></i> ${actionButton.title}
                    </${actionButton.tag}>
                    `;
            });
            return actions;
        },
    },
});
</script>
<style>
/*Overrides for Tailwind CSS */

/*Form fields*/
.dataTables_wrapper .dataTables_filter input {
    color: #4a5568; /*text-gray-700*/
    padding-left: 1rem; /*pl-4*/
    padding-right: 1rem; /*pl-4*/
    padding-top: 0.5rem; /*pl-2*/
    padding-bottom: 0.5rem; /*pl-2*/
    line-height: 1.25; /*leading-tight*/
    border-width: 2px; /*border-2*/
    border-radius: 0.25rem;
    border-color: #edf2f7; /*border-gray-200*/
    background-color: #edf2f7; /*bg-gray-200*/
}
/*Form fields*/
.dataTables_wrapper select {
    color: #4a5568; /*text-gray-700*/
    padding-left: 2rem; /*pl-4*/
    padding-right: 2rem; /*pl-4*/
    padding-top: 0.5rem; /*pl-2*/
    padding-bottom: 0.5rem; /*pl-2*/
    line-height: 1.25; /*leading-tight*/
    min-width: 4rem;
    border-width: 2px; /*border-2*/
    border-radius: 0.25rem;
    border-color: #edf2f7; /*border-gray-200*/
    background-color: #edf2f7; /*bg-gray-200*/
}

/*Row Hover*/
table.dataTable.hover tbody tr:hover,
table.dataTable.display tbody tr:hover {
    background-color: #ebf4ff; /*bg-indigo-100*/
}

/*Pagination Buttons*/
.dataTables_wrapper .dataTables_paginate .paginate_button {
    font-weight: 700; /*font-bold*/
    border-radius: 0.25rem; /*rounded*/
    border: 1px solid transparent; /*border border-transparent*/
}

/*Pagination Buttons - Current selected */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    color: #fff !important; /*text-white*/
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /*shadow*/
    font-weight: 700; /*font-bold*/
    border-radius: 0.25rem; /*rounded*/
    background: #667eea !important; /*bg-indigo-500*/
    border: 1px solid transparent; /*border border-transparent*/
}

/*Pagination Buttons - Hover */
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    color: #fff !important; /*text-white*/
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /*shadow*/
    font-weight: 700; /*font-bold*/
    border-radius: 0.15rem; /*rounded*/
    background: #667eea !important; /*bg-indigo-500*/
    border: 1px solid transparent; /*border border-transparent*/
}

/*Add padding to bottom border */
table.dataTable.no-footer {
    border-bottom: 1px solid #e2e8f0; /*border-b-1 border-gray-300*/
    margin-top: 0.75em;
    margin-bottom: 0.75em;
}

/*Change colour of responsive icon*/
table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child:before,
table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child:before {
    background-color: #667eea !important; /*bg-indigo-500*/
}
.action-button {
    @apply p-2 shadow rounded;
}
</style>
