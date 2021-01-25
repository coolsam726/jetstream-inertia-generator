<template>
    <inertia-link :href="href" class="p-2 px-4 sm:rounded-md sm:shadow-md" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
    name: "InertiaButton",
    props: {
        href: {
            type: String,
            required: true,
            default: "",
        },
        classes: {
            required: false,
            default: "bg-gray-200"
        }
    }
}
</script>

<style scoped>

</style>
