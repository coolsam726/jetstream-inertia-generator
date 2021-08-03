<template>
    <div class="relative inline-block w-12 mr-2 align-middle">
        <label
            :for="id"
            class="relative flex block overflow-hidden transition duration-200 ease-in transform bg-gray-300 rounded-full cursor-pointer select-none "
            :class="{ 'bg-green-500': checked, 'bg-gray-400': !checked }"
        >
            <input
                type="checkbox"
                :name="id"
                :id="id"
                :checked="checked"
                @change="$emit('change', $event.target.checked)"
                class="w-6 h-6 transition-transform transform bg-white border-none rounded-full appearance-none cursor-pointer  toggle-checkbox ring-0 active:ring-0 focus:outline-none active:outline-none focus:ring-0 focus:ring-offset-0 active:border-none focus:border-none"
                :class="{ 'ml-auto': checked, 'mr-auto': !checked }"
            />
        </label>
    </div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigToggle",
    model: {
        prop: "checked",
        event: "change",
    },
    props: {
        checked: Boolean,
        id: {
            required: true,
        },
    },
    watch: {},
});
</script>

<style scoped>
/* CHECKBOX TOGGLE SWITCH */
/* @apply rules for documentation, these do not work as inline style */
[type="checkbox"] {
    background: white;
    outline-style: none !important;
    border-style: none !important;
}
[type="checkbox"]:checked {
    background-image: none;
    outline-offset: 0;
    outline-style: none;
}
[type="checkbox"]:focus {
    background: white;
    outline-style: none !important;
    border-style: none !important;
    border-width: 0;
    --tw-ring-color: transparent !important;
    --tw-ring-offset: none;
}
</style>
