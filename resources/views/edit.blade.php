@php
    $hasCheckbox = false;
    $hasSelect = false;
    $hasTextArea = false;
    $hasInput = false;
    $hasPassword = false;
@endphp
<template>
    <jig-layout>
        <template {{"#"}}header>
            <div class="flex flex-wrap items-center justify-between w-full px-4">
                <inertia-link :href="route('admin.{{$modelRouteAndViewName}}.index')" class="text-xl font-black text-white"><i class="fas fa-arrow-left"></i> Back | Edit
                    {{$modelTitle}} #@{{model.id}}</inertia-link>
            </div>
        </template>
        <div class="flex flex-wrap px-4">
            <div class="z-10 flex-auto max-w-2xl p-4 mx-auto bg-white md:rounded-md md:shadow-md">
                <edit-{{$modelRouteAndViewName}}-form :model="model" {{'@'}}success="onSuccess" {{'@'}}error="onError"/>
            </div>
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout.vue";
    import JetLabel from "@/Jetstream/Label.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import JetInputError from "@/Jetstream/InputError.vue";
    import JetButton from "@/Jetstream/Button.vue";
    import Edit{{$modelPlural}}Form from "./EditForm.vue";
    import DisplayMixin from "@/Mixins/DisplayMixin.js";
    import { defineComponent } from "vue";

    export default defineComponent({
        name: "Edit{{$modelPlural}}",
        props: {
            model: Object,
        },
        components: {
            InertiaButton,
            JetLabel,
            JetButton,
            JetInputError,
            JigLayout,
            Edit{{$modelPlural}}Form,
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
        },methods: {
            onSuccess(msg) {
                this.displayNotification('success',msg);
                this.$inertia.visit(route('admin.{{$modelRouteAndViewName}}.index'));
            },
            onError(msg) {
                this.displayNotification('error',msg);
            }
        }
    });
</script>

<style scoped>

</style>
