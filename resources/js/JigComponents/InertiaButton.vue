<template>
    <inertia-link v-if="href" :href="href" :class="`p-2 shadow-md ${shapeClass} ${classes}`">
        <slot></slot>
    </inertia-link>
    <button v-else :class="`p-2 shadow-md ${shapeClass} ${classes}`">
        <slot></slot>
    </button>

</template>

<script>
export default {
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
        classes: {
            required: false,
            default: "bg-gray-200"
        },
        square: {
            required: false,
            default: false
        },
        pill: {
            required: false,
            default: false
        },

    },
    data() {
        return {
            shapeClass: "rounded-md"
        }
    },
    created() {
        if (this.square!== false) {
            this.shapeClass = "rounded-none";
        }
        if (this.pill !==false) {
            this.shapeClass = "rounded-3xl"
        }
    }
}
</script>

<style scoped>

</style>
