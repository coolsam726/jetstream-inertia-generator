<template>
    <jig-layout>
        <template {{'#'}}header>
            <div class="flex flex-wrap items-center justify-between w-full px-4">
                <inertia-link :href="route('admin.dashboard')" class="text-xl font-black text-white"><i class="fas fa-arrow-left"></i> Back</inertia-link>
                <inertia-button v-if="can.create" :href="route('admin.{{$modelRouteAndViewName}}.create')" classes="bg-green-100 hover:bg-green-200 text-green-500"><i class="fas fa-plus"></i> New
                    {{$modelTitle}}</inertia-button>
            </div>
        </template>
        <div v-if="can.viewAny" class="flex flex-wrap px-4">
            <div v-if="datatable" class="z-10 flex-auto p-4 bg-white md:rounded-md md:shadow-md">
                <pagetables table-classes="table w-full" :columns="datatable.columns" :rows="datatable"
                            {{'@'}}paginate="getDatatable" {{'@'}}search="getDatatable">
                    <template v-slot:row="{row}">
                        @foreach($columnsToQuery as $col)<td class="p-2">{{'{{'}}row.{{$col}} }}</td>
                        @endforeach{{"\r"}}
                        <td class="">
                            <div class="flex flex-wrap justify-end">
                                <inertia-button square aria-label="Show {{$modelTitle}}" title="Show {{$modelTitle}}" v-if="row.can.view" :href="route('admin.{{$modelRouteAndViewName}}.show',row)" classes="px-3 bg-gray-400"><i class="text-white fas fa-eye"></i></inertia-button>
                                <inertia-button square aria-label="Edit {{$modelTitle}}" title="Edit {{$modelTitle}}" v-if="row.can.update" :href="route('admin.{{$modelRouteAndViewName}}.edit',row)" class="px-3 bg-indigo-500"><i class="text-white fas fa-edit"></i></inertia-button>
                                <inertia-button square aria-label="Delete {{$modelTitle}}" as="button" title="Delete {{$modelTitle}}" v-if="row.can.delete" @click.native.prevent="confirmDeletion(row)" class="px-3 bg-red-500"><i class="text-white fas fa-times"></i></inertia-button>
                            </div>
                        </td>
                    </template>
                </pagetables>
                <jet-confirmation-modal title="Confirm Deletion" :show="confirmDelete">
                    <div slot="content">
                        Are you sure you want to delete this record?
                    </div>
                    <div class="text-right" slot="footer">
                        <inertia-button as="button" type="button" @click.native.stop="cancelDelete" class="bg-red-500">Cancel</inertia-button>
                        <inertia-button as="button" type="button" @click.native.prevent="deleteModel" class="bg-green-500">Yes, Delete</inertia-button>
                    </div>
                </jet-confirmation-modal>
            </div>
        </div>
        <div v-else class="p-4 rounded-md shadow-md bg-red-100 text-red-500 font-bold ">
            You are not authorized to view a list of {{$modelTitlePlural}}
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
    import { Pagetables } from "pagetables";
    import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
    import InertiaButton from "@/JigComponents/InertiaButton";
    export default {
        name: "Index",
        components: {InertiaButton, JetConfirmationModal, JigLayout, Pagetables},
        props: {
            can: Object,
        },
        data() {
            return {
                tableParams: null,
                datatable: null,
                confirmDelete: false,
                currentModel: null,
            }
        },
        mounted() {
            this.getDatatable();
        },
        methods: {
            async getDatatable (params = {}) {
                axios.get(this.route('api.{{$modelRouteAndViewName}}.index'),{params: params}).then(res => {
                    this.datatable = res.data.payload;
                    this.tableParams = params;
                }).catch(err => {
                    //TODO: Implement error catching
                })
            },
            confirmDeletion(model) {
                this.currentModel = model;
                this.confirmDelete = true;
            },
            cancelDelete() {
                this.currentModel = false;
                this.confirmDelete = false;
            },
            async deleteModel() {
                const vm = this;
                this.confirmDelete = false;
                if (this.currentModel) {
                    this.$inertia.delete(route('admin.{{$modelRouteAndViewName}}.destroy', vm.currentModel)).then(() => {
                        vm.getDatatable(vm.tableParams);
                    });
                }
            }
        }
    }
</script>

<style scoped>

</style>
