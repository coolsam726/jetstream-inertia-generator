<template>
    <!-- component -->
    <jet-dropdown align="right" content-classes="p-0 bg-white w-full">
        <template #trigger>
            <slot name="trigger">
                <button
                    title="Notifications"
                    class="relative z-10 block p-2 rounded-md  focus:outline-none"
                >
                    <span
                        v-if="notifications.filter((n) => !n.read_at).length"
                        style="font-weight: 900"
                        class="
                            absolute
                            z-20
                            bg-danger
                            p-0.5
                            rounded
                            -bottom-0.5
                            -right-1
                            px-2
                            font-black
                            text-sm text-white
                        "
                        >{{
                            notifications.filter((n) => !n.read_at).length
                        }}</span
                    >
                    <svg
                        class="w-10 p-2 bg-gray-100 rounded-full text-primary"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                        />
                    </svg>
                </button>
            </slot>
        </template>
        <template #content>
            <div
                class="py-2 mb-2"
                v-if="notifications.filter((n) => !n.read_at).length"
            >
                <template v-for="(noti, index) of notifications" :key="index">
                </template>
                <button
                    v-if="!noti.read_at"
                    @click="viewNotification(noti)"
                    class="flex items-center px-4 py-3 -mx-2 border-b  hover:bg-gray-100"
                >
                    <svg
                        class="w-10 p-2 bg-gray-200 rounded-full text-primary"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                        />
                    </svg>
                    <span
                        class="flex flex-wrap mx-2 text-sm text-left text-gray-600 "
                    >
                        <span class="font-bold">{{ noti.data.title }}</span>
                        <span class="text-gray-600">
                            {{ $dayjs().to($dayjs(noti.created_at)) }}
                        </span>
                    </span>
                </button>
            </div>
            <div v-else class="py-5 mb-2 text-center">
                You have no unread notifications
            </div>
            <inertia-button
                :href="route('dashboard')"
                class="block py-2 font-bold text-center text-white bg-green-500 rounded-t-none "
                >See all notifications</inertia-button
            >
        </template>
        <!--        <div v-show="open" @click="$emit('opened',false)" class="fixed inset-0 z-10 w-full h-full"></div>-->

        <jet-dialog-modal
            max-width="lg"
            :show="notiModal"
            @close="closeNotification"
            v-if="currentNotification"
        >
            <template #title class="text-lg font-black">{{
                currentNotification.data.title
            }}</template>
            <template class="text-sm" #content>
                <div class="py-2 mb-2 font-semibold text-gray-600 border-b">
                    {{ $dayjs(currentNotification.created_at) }}
                </div>
                {{ currentNotification.data.body }}
            </template>
            <template #footer class="inline-block text-right gap-x-2">
                <inertia-button
                    @click.native.prevent="closeNotification"
                    class="bg-gray-300"
                    >Close</inertia-button
                >
                <a
                    target="_blank"
                    v-if="currentNotification.data.load_separately"
                    :href="currentNotification.data.action"
                    class="p-2 px-3 rounded-lg  text-gray-50 bg-primary focus:outline-none"
                    >{{ currentNotification.data.action_title }}</a
                >
                <inertia-button
                    v-else
                    :href="currentNotification.data.action"
                    class="bg-primary text-gray-50"
                    >{{ currentNotification.data.action_title }}</inertia-button
                >
            </template>
        </jet-dialog-modal>
    </jet-dropdown>
</template>

<script>
import InertiaButton from "@/JigComponents/InertiaButton.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import DisplayMixin from "@/Mixins/DisplayMixin.js";
import JetDropdown from "@/Jetstream/Dropdown.vue";

import { defineComponent } from "vue";
export default defineComponent({
    name: "SystemNotificationsDropdown",
    components: {
        InertiaButton,
        JetDialogModal,
        JetDropdown,
    },
    emits: ["opened"],
    model: {
        prop: "open",
        event: "opened",
    },
    props: {
        open: Boolean,
        unreadNotifications: {
            default: () => [],
            type: Array,
        },
    },
    mixins: [DisplayMixin],
    data() {
        return {
            currentNotification: null,
            notiModal: false,
            notifications: [],
            unreadCount: 0,
        };
    },
    mounted() {
        this.notifications = this.unreadNotifications;
        this.unreadCount = this.notifications.length;
        if (this.$page.props.user) {
            /*Echo.private(`App.Models.User.${this.$page.props.user.id}`)
                .notification((notification) => {
                    console.log(notification);
                    this.fetchNotification(notification.id);
                });*/
        }
    },
    methods: {
        async viewNotification(noti) {
            this.currentNotification = { ...noti };
            this.notiModal = true;
            let res = await axios.get(
                this.route("api.me.notifications.single.read", noti.id)
            );
            console.log(res);
            let idx = this.notifications.findIndex(
                (n) => n.id === res.data?.payload.id
            );
            if (idx >= 0) {
                this.notifications[idx] = res.data.payload;
            }
        },
        async fetchNotification(id) {
            axios
                .get(this.route("api.me.notifications.single.fetch", id))
                .then((res) => {
                    this.notifications.unshift(res.data.payload);
                });
        },
        closeNotification() {
            this.notiModal = false;
            this.currentNotification = null;
        },
        toggleDrawer() {
            this.$emit("opened", !this.open);
        },
    },
    watch: {
        notifications: {
            handler: function (curr) {
                this.unreadCount = curr.filter((n) => !n.read_at).length;
            },
            deep: false,
        },
    },
});
</script>

<style scoped></style>
