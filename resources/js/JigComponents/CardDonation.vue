<template>
    <jet-dialog-modal :show="modalShown" @close="closeThisModal">
        <h3 slot="title" v-if="type ==='meal-allowance'" class="py-2 font-black border-b">Donate Your Meal Allowance</h3>
        <h3 slot="title" v-else class="py-2 font-black border-b">Donate Your Wallet Balance</h3>
        <div slot="content">
            <div class="p-3 text-2xl font-black bg-success-100 text-success">Balance: KES {{currentBalance | numeralFormat}}</div>
            <form ref="donationForm" @submit.prevent="makeDonation">
                <div class="my-2">
                    <jet-label>Enter Recipient Number</jet-label>
                    <jet-input ref="donationRecipient" type="text" class="w-full" @input="detectUser"></jet-input>
                </div>
                <div v-if="recipientNumber" class="my-2">
                    <jet-label>Enter Amount to Donate</jet-label>
                    <jet-input type="number" class="w-full" :max="currentBalance" v-model="amount"></jet-input>
                </div>
                <div v-if="recipientNumber && identifiedUser" class="p-2 my-2 text-sm font-bold bg-primary-100 text-primary">Identified User: {{identifiedUser.name}}</div>
                <div v-else-if="recipientNumber && !identifiedUser" class="my-2 font-bold bg-danger-100 text-danger">No Identified User!</div>

                <div v-if="recipientNumber" class="my-2">
                    <jet-label>Comment (Optional)</jet-label>
                    <jig-textarea class="w-full" v-model="comment"></jig-textarea>
                </div>
                <input v-if="recipientNumber && detectUser && !processing" type="submit" v-show="false">
            </form>
        </div>
        <div slot="footer" class="text-right gap-x-2">
            <inertia-button @click.native="$emit('close')" class="text-white bg-danger">Close</inertia-button>
            <inertia-button :disabled="!recipientNumber || !amount || processing" @click.native="makeDonation" class="text-white bg-success disabled:opacity-50">
                <i class="fas fa-spinner fa-spin" v-if="processing"></i>
                Make Donation
            </inertia-button>
        </div>
    </jet-dialog-modal>
</template>

<script>
import JetDialogModal from "@/Jetstream/DialogModal.vue"
import InertiaButton from "@/JigComponents/InertiaButton.vue";
import JetLabel from "@/Jetstream/Label.vue"
import JetInput from "@/Jetstream/Input.vue"
import DisplayMixin from "@/Mixins/DisplayMixin.js";
import JigTextarea from "@/JigComponents/JigTextarea.vue";
import { defineComponent } from "vue";
export default defineComponent({
    name: "CardDonation",
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        type: {
            type: String,
            default: 'meal-allowance'
        }
    },
    mixins: [
        DisplayMixin,
    ],
    components: {
        JigTextarea,
        InertiaButton,
        JetDialogModal,
        JetLabel,
        JetInput,
    },
    data() {
        return {
            amount: null,
            recipientNumber: null,
            identifiedUser: null,
            processing: false,
            comment: null,
        }
    },
    computed: {
        modalShown() {
            return this.show;
        },
        currentBalance() {
            return this.type ==='meal-allowance' ? this.$page.props.user.card_balance : this.$page.props.user.wallet_balance;
        }
    },
    mounted() {
    },
    methods: {
        detectUser: _.debounce(async function(val) {
            console.log(`${val} Identifying user...`);

            const vm = this;
            vm.identifiedUser = null;
            vm.recipientNumber = null;
            if (val.length >= 5) {
                vm.processing = true;
                axios.get(route('api.customers.by-card-number',val)).then(res => {
                    vm.identifiedUser = res.data.payload;
                    vm.recipientNumber = this.identifiedUser.user_number;
                }).catch(err => {
                    vm.identifiedUser = null;
                    vm.recipientNumber = null;
                    vm.displayNotification('error',err.response?.data.message || err.message || err);
                }).finally(() => {
                    vm.processing = false;
                });
            }
        },500),

        async makeDonation() {
            const vm = this;
            if (!this.recipientNumber || !this.amount ) return false;
            if (this.currentBalance < this.amount) {
                this.displayNotification('error', "You can only donate what you have.");
                return false;
            }
            this.processing = true;
            axios.post(this.route('api.me.donate'),{
                type: this.type,
                amount: this.amount,
                recipient_number: this.recipientNumber,
                comment: this.comment,
            }).then(res => {
                this.displayNotification('success', res.data.message);
                this.closeThisModal();
            }).catch(err => {
                this.displayNotification('error', err.response?.data?.message || err.message || err);
            }).finally(res => {
                this.processing = false;
            });
        },
        closeThisModal() {
           this.processing = false;
           this.recipientNumber = null;
           this.identifiedUser = null;
           this.amount = null;
           this.$emit('close');
        }
    }
})
</script>

<style scoped>

</style>
