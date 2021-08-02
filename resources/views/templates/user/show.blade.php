<template>
    <jig-layout>
        <template {{'#'}}header>
            <div
                class="flex flex-wrap items-center justify-between w-full px-4"
            >
                <inertia-link
                    :href="route('admin.{{$modelRouteAndViewName}}.index')"
                    class="text-2xl font-black text-white"
                ><i class="fas fa-arrow-left"></i> Back | Details of {{$modelTitle}}
                    #@{{ model.id }}</inertia-link>
            </div>
        </template>
        <div v-if="model.can.view" class="flex flex-wrap px-4">
            <div
                class="z-10 flex-auto max-w-5xl p-4 mx-auto bg-white md:rounded-md md:shadow-md"
            >
                <show-{{$modelRouteAndViewName}}-form :model="model"></show-{{$modelRouteAndViewName}}-form>
            </div>
        </div>
        <div v-else class="text-center space-4 px-4 bg-white rounded-md shadow-md text-red-500 font-bold text-lg">
            You don't have permission to view this resource.
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import Show{{$modelPlural}}Form from "./ShowForm.vue"
    export default {
        name: "Show{{$modelPlural}}",
        components: {
            InertiaButton,
            JigLayout,
            Show{{$modelPlural}}Form,
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

