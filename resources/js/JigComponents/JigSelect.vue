<template>
    <v-select
        class="px-2 py-1 border border-gray-200 bg-gray-50"
        :model-value="modelValue"
        @update:modelValue="onSelect"
        :multiple="multiple"
        :options="options"
        :label="label"
        :append-to-body="true"
        :close-on-select="closeOnSelect"
        :placeholder="placeholder"
        :reduce="reduce"
    >
        <template #search="{ attributes, events }">
            <input
                style="border-right: none !important"
                class="vs__search"
                v-bind="attributes"
                v-on="events"
            />
        </template>
    </v-select>
</template>

<script>
import vSelect from "vue-select/src/index";
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigSelect",
    emits: ["update:modelValue"],
    components: {
        vSelect,
    },
    props: {
        multiple: {
            default: false,
        },
        modelValue: {
            default: null,
        },
        options: {
            default: () => [],
        },
        placeholder: {
            default: "Search or Select",
        },
        label: {
            default: "name",
        },
        closeOnSelect: { default: true },
        reduce: {
            type: Function,
            default: (option) => option,
        },
    },
    model: {
        prop: "value",
        event: "input",
    },
    methods: {
        onSelect(value) {
            this.$emit("update:modelValue", value);
        },
    },
});
</script>

<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
$vs-colors: (
    lightest: rgba(60, 60, 60, 0.26),
    light: rgba(60, 60, 60, 0.5),
    dark: #333,
    darkest: rgba(0, 0, 0, 0.15),
) !default;

//  Global Component Variables
$vs-component-line-height: 1.4 !default;
$vs-component-placeholder-color: inherit !default;

//  Active State
$vs-state-active-bg: #5897fb !default;
$vs-state-active-color: #fff !default;

//  Disabled State
$vs-state-disabled-bg: rgb(248, 248, 248) !default;
$vs-state-disabled-color: map_get($vs-colors, "light") !default;
$vs-state-disabled-controls-color: map_get($vs-colors, "light") !default;
$vs-state-disabled-cursor: not-allowed !default;

//  Borders
$vs-border-width: 1px !default;
$vs-border-style: solid !default;
$vs-border-radius: 4px !default;
$vs-border-color: map_get($vs-colors, "lightest") !default;

//  Component Controls: Clear, Open Indicator
$vs-controls-color: map_get($vs-colors, "light") !default;
$vs-controls-size: 1 !default;
$vs-controls-deselect-text-shadow: 0 1px 0 #fff;

//  Selected
$vs-selected-bg: #f0f0f0 !default;
$vs-selected-border-color: $vs-border-color !default;
$vs-selected-border-style: $vs-border-style !default;
$vs-selected-border-width: $vs-border-width !default;

//  Dropdown
$vs-dropdown-z-index: 1001 !default;
$vs-dropdown-min-width: 100px !default;
$vs-dropdown-max-height: 350px !default;
$vs-dropdown-box-shadow: 0px 3px 6px 0px map_get($vs-colors, "darkest") !default;
$vs-dropdown-bg: #fff !default;
.loader {
    text-align: center;
    color: #bbbbbb;
}
.vs__dropdown-toggle {
    border: none !important;
}
</style>
