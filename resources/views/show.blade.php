<template>
    <jig-layout>
        <template {{'#'}}header>
            <div
                class="flex flex-wrap items-center justify-between w-full px-4"
            >
                <inertia-link
                    :href="route('admin.{{$modelRouteAndViewName}}.index')"
                    class="text-2xl font-black text-white"
                ><i class="fas fa-arrow-left"></i> Back | Details of {{$modelTitleSingular}}
                    #@{{ model.id }}</inertia-link
                >
            </div>
        </template>
        <div v-if="model.can.view" class="flex flex-wrap px-4">
            <div
                class="z-10 flex-auto max-w-5xl p-4 mx-auto bg-white md:rounded-md md:shadow-md"
            >
                <div class="grid grid-cols-1 gap-4">
                    @foreach($columns as $column)
                        <div class="grid grid-cols-1 p-0 text-lg font-semibold sm:grid-cols-3">
                            <div class="p-4 sm:text-right sm:col-span-1 bg-blue-50">
                                {{$column['label']}}:
                            </div>
                            <div class="p-4 bg-blue-100 sm:col-span-2">
                                {{'{{'}} model.{{$column['name']}} }}
                            </div>
                        </div>
                    @endforeach
                    @if (isset($relations['belongsTo']) && count($relations["belongsTo"]))
                        <hr />
                        @foreach($relations["belongsTo"] as $parent)
                            <div class="grid grid-cols-1 p-0 text-lg font-semibold sm:grid-cols-3">
                                <div class="p-4 sm:text-right sm:col-span-1 bg-blue-50">
                                    {{$parent['related_model_title']}}:
                                </div>
                                <div class="p-4 bg-blue-100 sm:col-span-2">
                                    {{'{{'}} model.{{$parent['relationship_variable']}}.{{$parent["label_column"]}} }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div v-else class="text-center space-4 px-4 bg-white rounded-md shadow-md text-red-500 font-bold text-lg">You don't have permission to view this resource.</div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
    import InertiaButton from "@/JigComponents/InertiaButton";
    export default {
        name: "Create",
        components: {
            InertiaButton,
            JigLayout
        },
        props: {
            model: Object
        },
        data() {
            return {};
        },
        mounted() {},
        methods: {}
    };
</script>

<style {{'scoped'}}></style>

