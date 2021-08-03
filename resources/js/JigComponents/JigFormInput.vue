<template>
    <div>
        <jet-label :class="{ required: isRequired }" v-if="label" :for="id">
            <slot name="label">{{ label }}</slot>
        </jet-label>
        <slot>
            <jig-select
                v-if="type === 'select'"
                v-bind="attribs"
                :id="id"
                :model-value="modelValue"
                @update:modelValue="$emit('update:modelValue', $event)"
                :autocomplete="autocomplete"
                :required="isRequired"
            />
            <jig-textarea
                v-else-if="type === 'textarea'"
                v-bind="attribs"
                :id="id"
                :model-value="modelValue"
                @update:modelValue="$emit('update:modelValue', $event)"
                :autocomplete="autocomplete"
                :required="isRequired"
            />
            <jet-checkbox
                v-else-if="type === 'checkbox'"
                v-bind="attribs"
                :id="id"
                v-model="modelValue"
                :checked="modelValue"
                @update:checked="$emit('update:modelValue', $event)"
                :autocomplete="autocomplete"
                :required="isRequired"
            />
            <infinite-select
                v-else-if="type === 'infinite-select'"
                v-bind="attribs"
                :id="id"
                :model-value="modelValue"
                @update:modelValue="onSelectInput"
                :autocomplete="autocomplete"
                :required="isRequired"
            />
            <jet-input
                v-else
                :type="type"
                v-bind="attribs"
                :id="id"
                :model-value="modelValue"
                @update:modelValue="$emit('update:modelValue', $event)"
                :autocomplete="autocomplete"
                :required="isRequired"
            />
        </slot>
        <jet-input-error :message="error" />
    </div>
</template>

<script>
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JigSelect from "@/JigComponents/JigSelect.vue";
import JigTextarea from "@/JigComponents/JigTextarea.vue";
import InfiniteSelect from "@/JigComponents/InfiniteSelect.vue";
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigFormInput",
    components: {
        InfiniteSelect,
        JigTextarea,
        JigSelect,
        JetLabel,
        JetInput,
        JetInputError,
        JetCheckbox,
    },
    emits: ["update:modelValue"],
    props: {
        id: {
            required: true,
            type: String,
        },
        required: { default: false },
        modelValue: {},
        type: {
            default: "text",
        },
        autocomplete: { default: false },
        label: String,
        error: {},
        inputAttributes: Object,
    },
    methods: {
        onSelectInput(val) {
            this.$emit("update:modelValue", val);
        },
    },
    computed: {
        isRequired() {
            return (
                this.required !== undefined &&
                this.required !== false &&
                this.required !== "false"
            );
        },
        attribs() {
            return {
                ...this.inputAttributes,
                class: {
                    "mt-1 block w-full": true,
                    "border border-red-500 ring-4 ring-danger-50": this.error,
                },
            };
        },
    },
});
</script>

<style scoped></style>
