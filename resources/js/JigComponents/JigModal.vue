<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" static :open="isOpen" @close="toggleModal(false)">
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="min-h-screen px-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-75"
                        leave="duration-200 ease-in"
                        leave-from="opacity-75"
                        leave-to="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-gray-900 bg-opacity-60"
                        />
                    </TransitionChild>

                    <span
                        class="inline-block h-screen align-middle"
                        aria-hidden="true"
                    >
                        &#8203;
                    </span>

                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <div
                            class="inline-block w-full p-6 my-8 overflow-hidden text-left transition-all transform bg-white shadow-xl "
                            :class="`${cornerClass} ${positionClass} ${maxWidthClass}`"
                        >
                            <DialogTitle
                                as="h3"
                                class="flex items-center justify-between text-lg font-medium leading-6 text-gray-900 "
                            >
                                <span class="flex-1"
                                    ><slot name="title"></slot
                                ></span>
                                <button
                                    type="button"
                                    class="p-1 px-2"
                                    @click="toggleModal(false)"
                                >
                                    <i
                                        class="font-bold text-red-500  fas fa-times"
                                    ></i>
                                </button>
                            </DialogTitle>
                            <div class="mt-2">
                                <slot name="default"></slot>
                            </div>

                            <div class="mt-4 space-x-2 text-right">
                                <slot name="footer"></slot>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { ref, defineComponent } from "vue";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogOverlay,
    DialogTitle,
} from "@headlessui/vue";

export default defineComponent({
    name: "JigModal",
    props: {
        show: Boolean,
        cornerClass: {
            default: "rounded-2xl",
        },
        positionClass: {
            default: "align-middle",
        },
        maxWidthClass: {
            default: "max-w-2xl",
        },
    },
    components: {
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogOverlay,
        DialogTitle,
    },
    emits: ["close"],

    setup(props, ctx) {
        const isOpen = ref(true);

        return {
            isOpen,
            toggleModal(open) {
                isOpen.value = open;
                if (!open) {
                    ctx.emit("close");
                }
            },
        };
    },
    watch: {
        show(val) {
            this.toggleModal(val);
        },
    },
});
</script>
