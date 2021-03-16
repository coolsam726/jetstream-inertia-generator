<template>
    <textarea class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" v-model="value" ref="txt"></textarea>
</template>

<script>
export default {
    name: "JigTextarea",
    props: ['value'],
    emits: ['input'],
    model: {
        prop: 'value',
        event: 'input'
    },
    methods: {
        focus() {
            this.$refs.txt.focus()
        }
    }
}
</script>

<style scoped>

</style>
