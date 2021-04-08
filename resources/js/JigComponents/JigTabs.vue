<template>
    <div class="bg-white border rounded-lg pb-4 p-4">
        <nav class="flex flex-col sm:flex-row flex-wrap w-full" :class="navClasses">
            <slot name="nav">
            </slot>
        </nav>
        <slot name="content">
        </slot>
    </div>
</template>

<script>
export default {
    name: "JigTabs",
    props: {
        navClasses: String,
    }
}
</script>

<style scoped>

</style>
