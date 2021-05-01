<template>
    <form class="w-full" {{'@'}}submit.prevent="storeModel">
        <div class=" sm:col-span-4">
            <jet-label class="required" for="title" value="Title" />
            <jet-input class="w-full" type="text" id="title" name="title" v-model="form.title"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.title}"
            ></jet-input>
            <jet-input-error :message="form.errors.title" class="mt-2" />
        </div>

        <div class="mt-2 text-right">
            <inertia-button type="submit" class="bg-success font-semibold disabled:opacity-25" :disabled="form.processing">Submit</inertia-button>
        </div>
    </form>
</template>

<script>
    import JetInput from "@/Jetstream/Input";
    import JetLabel from "@/Jetstream/Label";
    import InertiaButton from "@/JigComponents/InertiaButton";
    import JetInputError from "@/Jetstream/InputError"
    import {useForm} from "@inertiajs/inertia-vue3";
    export default {
        name: "CreateRolesForm",
        components: {
            InertiaButton,
            JetInputError,
            JetLabel,
                         JetInput,

        },
        data() {
            return {
                form: useForm({
                    title: null,
                }, {remember: false}),
            }
        },
        mounted() {
        },
        computed: {
            flash() {
                return this.{{'$'}}page.props.flash || {}
            }
        },
        methods: {
            async storeModel() {
                this.form.post(this.route('admin.roles.store'),{
                    onSuccess: res => {
                        if (this.flash.success) {
                            this.{{'$'}}emit('success',this.flash.success);
                        } else if (this.flash.error) {
                            this.{{'$'}}emit('error',this.flash.error);
                        } else {
                            this.{{'$'}}emit('error',"Unknown server error.")
                        }
                    },
                    onError: res => {
                        this.{{'$'}}emit('error',"A server error occurred");
                    }
                },{remember: false, preserveState: true})
            }
        }
    }
</script>

<style scoped>

</style>
