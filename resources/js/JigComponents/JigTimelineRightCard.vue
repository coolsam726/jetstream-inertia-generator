<template>
    <div class="flex md:contents">
        <div class="col-start-1 col-end-4 text-gray-800 font-black">
            <div class="h-full flex items-center justify-end">
                <slot name="dot"></slot>
            </div>
        </div>
        <div class="col-start-4 col-end-5 mr-2 md:mx-auto relative">
            <div class="h-full w-6 flex items-center justify-center">
                <div class="h-full w-1 bg-blue-800 pointer-events-none"></div>
            </div>
            <div
                class="w-6 h-6 absolute top-1/2 -mt-3 rounded-full bg-red-500 shadow"
            >
            </div>
        </div>
        <div
            class="bg-blue-500 col-start-5 col-end-12 p-4 rounded-xl my-4 mr-auto shadow-md"
        >
            <h3 class="font-semibold text-lg mb-1"><slot name="title"></slot></h3>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "JigTimelineRightCard"
}
</script>

<style scoped>

</style>
