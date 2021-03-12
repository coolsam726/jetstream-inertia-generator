<template>
    <div class="inline-block relative align-middle w-12 mr-2 ">
        <label :for="id" class="relative select-none transition flex duration-200 ease-in transform transition block overflow-hidden rounded-full bg-gray-300 cursor-pointer"
               :class="{'bg-green-500': checked, 'bg-gray-400': !checked}"
        >
            <input type="checkbox" :name="id" :id="id" :checked="checked"
                   @change="$emit('change',$event.target.checked)"
                   class="toggle-checkbox ring-0 active:ring-0
                focus:outline-none active:outline-none focus:ring-0 focus:ring-offset-0
                border-none active:border-none focus:border-none w-6 h-6
                 rounded-full bg-white border-none appearance-none cursor-pointer transition-transform transform"
                   :class="{'ml-auto': checked,'mr-auto': !checked}"
            />
        </label>
    </div>
</template>

<script>
export default {
    name: "JigToggle",
    model: {
        prop: 'checked',
        event: 'change'
    },
    props: {
        checked: Boolean,
        id: {
            required: true,
        }
    },
    watch: {}
}
</script>

<style scoped>
    /* CHECKBOX TOGGLE SWITCH */
    /* @apply rules for documentation, these do not work as inline style */
    [type="checkbox"] {
        background: white;
        outline-style: none!important;
        border-style: none !important;
    }
    [type="checkbox"]:checked {
        background-image: none;
        outline-offset: 0;
        outline-style: none;
    }
    [type="checkbox"]:focus {
        background: white;
        outline-style: none!important;
        border-style: none !important;
        border-width: 0;
        --tw-ring-color: transparent !important;
        --tw-ring-offset: none;
    }

</style>
