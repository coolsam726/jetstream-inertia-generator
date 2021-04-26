<template>
    <div class="sm:grid sm:grid-cols-3 sm:my-1" :class="classes">
        <dt class="text-sm font-medium p-4 mx-0 sm:text-right sm:col-span-1 bg-blue-50" :class="dtClasses">
            <slot name="dt"></slot>
        </dt>
        <dd class="p-4 mx-0 bg-blue-100 break-words text-sm sm:mt-0 sm:col-span-2" :class="ddClasses">
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
