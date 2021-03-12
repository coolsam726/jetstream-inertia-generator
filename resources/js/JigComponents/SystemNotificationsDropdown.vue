<template>
    <!-- component -->
    <div class="relative">
        <slot name="trigger">
            <button title="Notifications" @click="$emit('opened',!open)" class="relative z-10 block rounded-md p-2 focus:outline-none">
                <span v-if="notifications.filter(n=>!n.read_at).length" style="font-weight: 900" class="absolute z-20 bg-danger p-0.5 rounded -bottom-0.5 -right-1 px-2 font-black text-sm text-white">{{notifications.filter(n=>!n.read_at).length}}</span>
                <svg class="w-10 p-2 bg-gray-100 rounded-full text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                </svg>
            </button>
        </slot>
        <div v-show="open" @click="$emit('opened',false)" class="fixed inset-0 h-full w-full z-10"></div>

        <div v-show="open" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20" style="width:20rem;">
            <div class="py-2 mb-2" v-if="notifications.filter(n => !n.read_at).length">
                <button v-for="(noti, index) of notifications" v-if="!noti.read_at" :key="index"  @click="viewNotification(noti)"
                        class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2"
                >
                    <svg class="w-10 p-2 bg-gray-200 rounded-full text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                    <span class="text-gray-600 text-left text-sm mx-2 flex flex-wrap">
                        <span class="font-bold">{{noti.data.title}}</span>
                        <span class="text-gray-600">
                            {{$dayjs().to($dayjs(noti.created_at))}}
                        </span>
                    </span>
                </button>
            </div>
            <div v-else class="text-center py-5 mb-2">
                You have no unread notifications
            </div>
            <inertia-button :href="route('dashboard')" class="block bg-gray-800 text-white rounded-t-none text-center font-bold py-2">See all notifications</inertia-button>
        </div>
        <jet-dialog-modal max-width="lg" :show="notiModal" @close="closeNotification" v-if="currentNotification">
            <h3 slot="title" class="font-black text-lg">{{currentNotification.data.title}}</h3>
            <div class="text-sm" slot="content">
                <div class="font-semibold text-gray-600 border-b py-2 mb-2">
                    {{$dayjs(currentNotification.created_at)}}
                </div>
                {{currentNotification.data.body}}
            </div>
            <div slot="footer" class="inline-block text-right gap-x-2">
                <inertia-button @click.native.prevent="closeNotification" class="bg-gray-300">Close</inertia-button>
                <a target="_blank" v-if="currentNotification.data.load_separately" :href="currentNotification.data.action" class="rounded-lg text-gray-50 bg-primary focus:outline-none p-2 px-3">{{currentNotification.data.action_title}}</a>
                <inertia-button v-else :href="currentNotification.data.action" class="bg-primary text-gray-50">{{currentNotification.data.action_title}}</inertia-button>
            </div>
        </jet-dialog-modal>
    </div>
</template>

<script>
import InertiaButton from "@/JigComponents/InertiaButton";
import JetDialogModal from "@/Jetstream/DialogModal"
import DisplayMixin from "@/Mixins/DisplayMixin";

export default {
    name: "SystemNotificationsDropdown",
    components: {
        InertiaButton,
        JetDialogModal
    },
    model: {
        prop: 'open',
        event: 'opened'
    },
    props: {
        open: Boolean,
        unreadNotifications: {
            default: () => [],
            type: Array,
        },
    },
    mixins: [
        DisplayMixin,
    ],
    data() {
        return {
            currentNotification: null,
            notiModal: false,
            notifications: [],
            unreadCount: 0
        }
    },
    mounted() {
        this.notifications = this.unreadNotifications;
        this.unreadCount = this.notifications.length;
        if (this.$page.props.user) {
            Echo.private(`App.Models.User.${this.$page.props.user.id}`)
                .notification((notification) => {
                    console.log(notification);
                    this.fetchNotification(notification.id);
                });
        }
    },
    methods: {
        async viewNotification(noti) {
            this.currentNotification = {...noti};
            this.notiModal = true;
            let res = await axios.get(this.route('api.me.notifications.single.read', noti.id));
            console.log(res);
            let idx = this.notifications.findIndex(n => n.id === res.data?.payload.id);
            if (idx >=0) {
                this.notifications[idx] = res.data.payload;
            }
        },
        async fetchNotification(id) {
            axios.get(this.route('api.me.notifications.single.fetch',id)).then(res => {
                this.notifications.unshift(res.data.payload);
            });
        },
        closeNotification() {
            this.notiModal = false;
            this.currentNotification = null;
        }
    },
    watch: {
        "notifications": {
            handler: function(curr) {
                this.unreadCount = curr.filter(n => !n.read_at).length;
            },
            deep: false
        }
    }
}
</script>

<style scoped>

</style>
