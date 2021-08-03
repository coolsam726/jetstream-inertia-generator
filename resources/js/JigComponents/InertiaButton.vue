<template>
    <inertia-link
        v-if="href && !fullReload"
        :href="href"
        :class="`p-2 shadow-md focus:outline-none focus:ring-0 ${shapeClass} ${classes}`"
    >
        <slot></slot>
    </inertia-link>
    <a
        v-else-if="href && fullReload"
        :href="href"
        :class="`p-2 shadow-md focus:outline-none focus:ring-0 ${shapeClass} ${classes}`"
    >
        <slot></slot>
    </a>

    <button
        v-else
        :class="`p-2 focus:outline-none focus:ring-0  shadow-md ${shapeClass} ${classes}`"
    >
        <slot></slot>
    </button>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "InertiaButton",
    props: {
        href: {
            required: false,
            default: "",
        },
        as: {
            type: String,
            required: false,
            default: "a",
        },
        fullReload: {
            required: false,
            type: Boolean,
            default: false,
        },
        classes: {
            required: false,
            default: "bg-gray-200",
        },
        square: {
            required: false,
            default: false,
        },
        pill: {
            required: false,
            default: false,
        },
    },
    data() {
        return {
            shapeClass: "rounded-md",
        };
    },
    created() {
        if (this.square !== false) {
            this.shapeClass = "rounded-none";
        }
        if (this.pill !== false) {
            this.shapeClass = "rounded-3xl";
        }
    },
});
</script>

<style scoped></style>
