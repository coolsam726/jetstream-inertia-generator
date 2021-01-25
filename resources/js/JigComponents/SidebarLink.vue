<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                            ? 'text-primary-500 hover:text-primary-600 text-xs uppercase py-3 font-bold block'
                            : 'text-gray-800 hover:text-gray-600 text-xs uppercase py-3 font-bold block'
            }
        }
    }
</script>
