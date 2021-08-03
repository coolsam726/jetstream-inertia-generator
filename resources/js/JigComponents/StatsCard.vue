<template>
    <div :class="`flex flex-col w-full rounded p-4 ${bgClass}`">
        <div class="flex flex-auto">
            <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                <h5 class="text-sm font-bold text-gray-500 uppercase">
                    <slot name="title"></slot>
                </h5>
                <h5
                    v-if="$slots.subtitle"
                    class="text-xs font-black uppercase"
                    :class="subtitleClasses"
                >
                    <slot name="subtitle"></slot>
                </h5>
                <div class="text-2xl font-black" :class="bodyClasses">
                    <slot></slot>
                </div>
            </div>
            <div class="relative flex-initial w-auto pl-4">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 p-3 text-center "
                    :class="iconClasses"
                >
                    <slot name="icon"
                        ><i class="fas fa-2x fa-money-check"></i
                    ></slot>
                </div>
            </div>
        </div>
        <slot name="footer"></slot>
    </div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    name: "StatsCard",
    props: {
        bgClass: {
            type: String,
            default: "bg-primary-100",
        },
        bodyClasses: {
            default: "text-primary",
        },
        iconClasses: {
            default: "text-white bg-primary rounded shadow-lg",
        },
        subtitleClasses: {
            default: "text-danger",
        },
    },
});
</script>

<style scoped></style>
