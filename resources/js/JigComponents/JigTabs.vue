<template>
    <div class="p-4 pb-4 bg-white border rounded-lg">
        <nav
            class="flex flex-col flex-wrap w-full sm:flex-row"
            :class="navClasses"
        >
            <slot name="nav"> </slot>
        </nav>
        <slot name="content"> </slot>
    </div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "JigTabs",
    props: {
        navClasses: String,
    },
});
</script>

<style scoped></style>
