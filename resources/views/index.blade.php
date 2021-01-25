<template>
    <jig-layout>
        <template {{'#'}}header>
            <div class="flex flex-wrap items-center justify-between w-full px-4">
                <inertia-link :href="route('admin.dashboard')" class="text-xl font-black text-white"><i class="fas fa-arrow-left"></i> Back</inertia-link>
                <inertia-button :href="route('admin.{{$modelRouteAndViewName}}.create')" classes="bg-green-100 hover:bg-green-200 text-green-500"><i class="fas fa-plus"></i> New
                    {{$modelTitle}}</inertia-button>
            </div>
        </template>
        <div class="flex flex-wrap px-4">
            <div v-if="datatable" class="z-10 flex-auto p-4 bg-white md:rounded-md md:shadow-md">
                <pagetables table-classes="table table-fixed w-full" :columns="datatable.columns" :rows="datatable"
                            {{'@'}}paginate="getDatatable" {{'@'}}search="getDatatable">
                    <template v-slot:row="{row}">
                        @foreach($columnsToQuery as $col)<td class="p-2">{{'{{'}}row.{{$col}} }}</td>
                        @endforeach{{"\r"}}
                        <td class="p-2 text-right">
                            <inertia-button aria-label="Show Church Type" title="Show {{$modelTitle}}" v-if="row.can.view" :href="route('admin.{{$modelRouteAndViewName}}.show',row)" class="mx-1 bg-gray-500"><i class="text-white fas fa-eye"></i></inertia-button>
                            <inertia-button v-if="row.can.update" :href="route('admin.{{$modelRouteAndViewName}}.edit',row)" class="mx-1 bg-indigo-500"><i class="text-white fas fa-edit"></i></inertia-button>
                            <jet-button v-if="row.can.delete" @click.native.prevent="deleteModel(row)" class="mx-1 my-0 bg-red-500 shadow-md sm:py-3"><i class="text-white shadow-md fas fa-trash"></i></jet-button>
                        </td>
                    </template>
                </pagetables>
            </div>
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
    import { Pagetables } from "pagetables";
    import JetButton from "@/Jetstream/Button";
    import InertiaButton from "@/JigComponents/InertiaButton";
    export default {
        name: "Index",
        components: {InertiaButton, JetButton, JigLayout, Pagetables},
        data() {
            return {
                tableParams: null,
                datatable: null,
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
            async deleteModel(model) {
                const vm = this;
                this.$inertia.delete(route('admin.church-types.destroy', model)).then(() => {
                    vm.getDatatable(vm.tableParams);
                });
            }
        }
    }
</script>

<style scoped>

</style>
