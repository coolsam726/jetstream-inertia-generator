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
                        <td class="p-2 text-right"><inertia-button :href="route('admin.{{$modelRouteAndViewName}}.edit',row)" class="bg-gray-500"><i class="text-white fas fa-edit"></i></inertia-button></td>
                    </template>
                </pagetables>
            </div>
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
    import { Pagetables } from "pagetables";
    import SecondaryButton from "@/Jetstream/SecondaryButton";
    import InertiaButton from "@/JigComponents/InertiaButton";
    export default {
        name: "Index",
        components: {InertiaButton, SecondaryButton, JigLayout, Pagetables},
        data() {
            return {
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
                }).catch(err => {
                    //TODO: Implement error catching
                })
            }
        }
    }
</script>

<style scoped>

</style>
