@php
    $hasCheckbox = false;
    $hasSelect = false;
    $hasTextArea = false;
    $hasInput = false;
    $hasDate = false;
    $hasPassword = false;
@endphp
<template>
    <dl class="gap-4">
@foreach($columns as $column)
        <jig-dd>
            <template #dt>{{$column['label']}}:</template>
            {{'{{'}} model.{{$column['name']}} }}
        </jig-dd>
@endforeach
    @if (isset($relations['belongsTo']) && count($relations["belongsTo"]))
@foreach($relations["belongsTo"] as $parent)
        <jig-dd>
            <template #dt>{{$parent['related_model_title']}}:</template>
            {{'{{'}} model.{{$parent['relationship_variable']}} ? model.{{$parent['relationship_variable']}}.{{$parent["label_column"]}} : '-' }}
        </jig-dd>
@endforeach
    @endif
</dl>
</template>

<script>
    import JigDd from "@/JigComponents/JigDd.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";

    export default {
        name: "Show{{$modelPlural}}Form",
        props: {
            model: Object,
        },
        components: {
            InertiaButton,
            JigDd,
        },
        data() {
            return {}
        },
        mounted() {},
        computed: {
            flash() {
                return this.$page.props.flash || {}
            }
        },
        methods: {}
    }
</script>

<style scoped>

</style>
