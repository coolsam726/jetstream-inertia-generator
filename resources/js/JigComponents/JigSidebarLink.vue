<template>
    <inertia-link v-if="href" :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
    <button v-else class="w-full text-left" :class="classes">
        <slot></slot>
    </button>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    props: ["href", "active"],

    computed: {
        classes() {
            return this.active
                ? "text-indigo-500 hover:text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border-l-4 border-indigo-500 text-md py-3 font-bold block"
                : "text-gray-800 hover:text-gray-600 hover:bg-indigo-100 py-3 font-bold block";
        },
    },
});
</script>
