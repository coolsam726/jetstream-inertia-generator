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
                <inertia-link :href="route('admin.{{$modelRouteAndViewName}}.index')" class="text-xl font-black text-white"><i class="fas fa-arrow-left"></i> Back | New
                    {{$modelTitle}}</inertia-link>
            </div>
        </template>
        <div class="flex flex-wrap px-4">
            <div class="z-10 flex-auto max-w-2xl p-4 mx-auto bg-white md:rounded-md md:shadow-md">
                <create-form {{'@'}}success="onSuccess" {{'@'}}error="onError"></create-form>
            </div>
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
        @if($hasCheckbox)
    import JetCheckbox from "@/Jetstream/Checkbox";
        @endif
        @if($hasInput)
    import JetInput from "@/Jetstream/Input";
        @endif
        @if($hasTextArea)
    import JigTextarea from "@/JigComponents/JigTextarea";
        @endif

        @if($hasSelect)
    import InfiniteSelect from '@/JigComponents/InfiniteSelect.vue';
        @endif
    import InertiaButton from "@/JigComponents/InertiaButton";
    import CreateForm from "./CreateForm";
    import DisplayMixin from "@/Mixins/DisplayMixin";
    export default {
        name: "Create",
        components: {
            InertiaButton,
            @if($hasInput) JetInput,{{"\r"}}@endif
            @if($hasCheckbox) JetCheckbox,{{"\r"}}@endif
            @if($hasTextArea) JigTextarea,{{"\r"}}@endif
            @if($hasSelect) InfiniteSelect,{{"\r"}}@endif
            JigLayout,
            CreateForm,
        },
        data() {
            return {
                form: this.$inertia.form({
                    @foreach($columns as $col)
                    "{{$col}}": null,
                    @endforeach
                        @if (count($relations))
                        @if(isset($relations['belongsTo']) && count($relations['belongsTo']))
                        @foreach($relations['belongsTo'] as $belongsTo)
                    "{{$belongsTo["relationship_variable"]}}": null,
                    @endforeach
                    @endif
                    @endif
                }),
            }
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
