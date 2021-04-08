<template>
    <div class="py-4 transition ease-in-out" v-if="name===tabController">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "JigTab",
    props: {
        name: {
            type: String,
            required: true,
        },
        tabController: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
