<template>
    <button
        v-if="active"
        :id="tab"
        class="block px-6 py-4 font-medium transition transition-transform duration-1000 ease-in-out transform translate-x-0  focus:outline-none"
        :class="activeClasses"
        @click="$emit('activate', tab)"
    >
        <slot></slot>
    </button>
    <button
        v-else
        :id="tab"
        class="block px-6 py-4 font-medium transition duration-700 duration-1000 ease-in-out border-b  focus:outline-none"
        @click="$emit('activate', tab)"
    >
        <slot></slot>
    </button>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigTabLink",
    emits: ["activate"],
    props: {
        tab: {
            type: String,
        },
        tabController: {
            type: String,
        },
        activeClasses: {
            default:
                "text-gray-600 border-t-2 border-l-2 border-r-2 sm:border-b-none border-indigo-500 text-indigo-500 hover:text-indigo-600 translate-x-0",
        },
    },
    computed: {
        active() {
            return this.tab === this.tabController;
        },
    },
});
</script>

<style>
.arrow-up {
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;

    border-bottom: 5px solid black;
}

.arrow-down {
    width: 0;
    height: 0;
    border-left: 20px solid transparent;
    border-right: 20px solid transparent;

    border-top: 20px solid #f00;
}

.arrow-right {
    width: 0;
    height: 0;
    border-top: 60px solid transparent;
    border-bottom: 60px solid transparent;

    border-left: 60px solid green;
}

.arrow-left {
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;

    border-right: 10px solid blue;
}
</style>
