<template>
    <div class="sm:grid sm:grid-cols-3 sm:my-1" :class="classes">
        <dt
            class="p-4 mx-0 text-sm font-medium  sm:text-right sm:col-span-1 bg-blue-50"
            :class="dtClasses"
        >
            <slot name="dt"></slot>
        </dt>
        <dd
            class="p-4 mx-0 text-sm break-words bg-blue-100  sm:mt-0 sm:col-span-2"
            :class="ddClasses"
        >
            <slot></slot>
        </dd>
    </div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigDd",
    props: {
        dtClasses: String,
        ddClasses: String,
        classes: {},
    },
});
</script>

<style scoped></style>
