<template>
    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" :class="classes">
        <dt class="text-sm font-medium text-gray-500" :class="dtClasses">
            <slot name="dt"></slot>
        </dt>
        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" :class="ddClasses">
            <slot></slot>
        </dd>
    </div>
</template>

<script>
export default {
    name: "JigDd",
    props: {
        dtClasses: String,
        ddClasses: String,
        classes: {},
    }
}
</script>

<style scoped>

</style>
