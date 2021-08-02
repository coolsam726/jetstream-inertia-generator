@php
    $hasCheckbox = false;
    $hasSelect = false;
    $hasTextArea = false;
    $hasInput = false;
@endphp
<template>
    <jig-layout>
        <template {{"#"}}header>
            <div class="flex flex-wrap items-center justify-between w-full px-4">
                <inertia-link :href="route('admin.{{$modelRouteAndViewName}}.index')"
                              class="text-xl font-black text-white"><i
                        class="fas fa-arrow-left"></i> Back | New {{$modelTitle}}
                </inertia-link>
            </div>
        </template>
        <div class="flex flex-wrap px-4">
            <div class="z-10 flex-auto max-w-2xl p-4 mx-auto bg-white md:rounded-md md:shadow-md">
                <create-{{$modelRouteAndViewName}}-form {{'@'}}success="onSuccess" {{'@'}}error="onError"/>
            </div>
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import Create{{$modelPlural}}Form from "./CreateForm.vue";
    import DisplayMixin from "@/Mixins/DisplayMixin.js";
    export default {
        name: "Create{{$modelPlural}}",
        components: {
            InertiaButton,
            JigLayout,
            Create{{$modelPlural}}Form,
        },
        data() {
            return {}
        },
        mixins: [DisplayMixin],
        mounted() {},
        computed: {
            flash() {
                return this.$page.props.flash || {}
            }
        },
        methods: {
            onSuccess(msg) {
                this.displayNotification('success',msg);
                this.$inertia.visit(route('admin.{{$modelRouteAndViewName}}.index'));
            },
            onError(msg) {
                this.displayNotification('error',msg);
            }
        }
    }
</script>

<style scoped>

</style>
