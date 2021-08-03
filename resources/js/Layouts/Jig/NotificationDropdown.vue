<template>
    <div>
        <button
            class="block px-3 py-1 text-gray-600"
            v-on:click="toggleDropdown($event)"
            ref="btnDropdownRef"
        >
            <i class="fas fa-bell"></i>
        </button>
        <div
            ref="popoverDropdownRef"
            class="z-50 float-left py-2 mt-1 text-base text-left list-none bg-white rounded shadow-lg "
            v-bind:class="{
                hidden: !dropdownPopoverShow,
                block: dropdownPopoverShow,
            }"
            style="min-width: 12rem"
        >
            <a
                href="#pablo"
                class="block w-full px-4 py-2 text-sm font-normal text-gray-800 whitespace-no-wrap bg-transparent "
            >
                Action
            </a>
            <a
                href="#pablo"
                class="block w-full px-4 py-2 text-sm font-normal text-gray-800 whitespace-no-wrap bg-transparent "
            >
                Another action
            </a>
            <a
                href="#pablo"
                class="block w-full px-4 py-2 text-sm font-normal text-gray-800 whitespace-no-wrap bg-transparent "
            >
                Something else here
            </a>
            <div class="h-0 my-2 border border-gray-200 border-solid" />
            <a
                href="#pablo"
                class="block w-full px-4 py-2 text-sm font-normal text-gray-800 whitespace-no-wrap bg-transparent "
            >
                Seprated link
            </a>
        </div>
    </div>
</template>
<script>
import Popper from "popper.js";
import { defineComponent } from "vue";

export default defineComponent({
    data() {
        return {
            dropdownPopoverShow: false,
        };
    },
    methods: {
        toggleDropdown: function (event) {
            event.preventDefault();
            if (this.dropdownPopoverShow) {
                this.dropdownPopoverShow = false;
            } else {
                this.dropdownPopoverShow = true;
                new Popper(
                    this.$refs.btnDropdownRef,
                    this.$refs.popoverDropdownRef,
                    {
                        placement: "bottom-end",
                    }
                );
            }
        },
    },
});
</script>
