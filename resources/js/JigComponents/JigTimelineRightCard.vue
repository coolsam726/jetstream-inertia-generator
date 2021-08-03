<template>
    <div class="flex md:contents">
        <div class="col-start-1 col-end-4 font-black text-gray-800">
            <div class="flex items-center justify-end h-full">
                <slot name="dot"></slot>
            </div>
        </div>
        <div class="relative col-start-4 col-end-5 mr-2 md:mx-auto">
            <div class="flex items-center justify-center w-6 h-full">
                <div class="w-1 h-full bg-blue-800 pointer-events-none"></div>
            </div>
            <div
                class="absolute w-6 h-6 -mt-3 bg-red-500 rounded-full shadow  top-1/2"
            ></div>
        </div>
        <div
            class="col-start-5 col-end-12 p-4 my-4 mr-auto bg-blue-500 shadow-md  rounded-xl"
        >
            <h3 class="mb-1 text-lg font-semibold">
                <slot name="title"></slot>
            </h3>
            <slot></slot>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigTimelineRightCard",
});
</script>

<style scoped></style>
