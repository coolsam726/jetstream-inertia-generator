<template>
    <textarea
        :value="modelValue"
        @input="onInput"
        class="border-gray-100 rounded-md shadow-sm  bg-gray-50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        ref="txt"
    ></textarea>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigTextarea",
    props: ["modelValue"],
    emits: ["update:modelValue"],
    model: {
        prop: "modelValue",
        event: "update:modelValue",
    },
    methods: {
        focus() {
            this.$refs.txt.focus();
        },
        onInput(e) {
            console.log(e);
            this.$emit("update:modelValue", e.target.value);
        },
    },
});
</script>

<style scoped></style>
