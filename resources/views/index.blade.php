<template>
    <jig-layout>
        <template {{'#'}}header>
            <div class="flex flex-wrap items-center justify-between w-full px-4">
                <inertia-link :href="route('admin.dashboard')" class="text-xl font-black text-white"><i class="fas fa-arrow-left"></i> Back</inertia-link>
                <div class="gap-x-2 flex">
                    <inertia-button v-if="can.create" :href="route('admin.{{$modelRouteAndViewName}}.create')" classes="bg-green-100 hover:bg-green-200 text-primary"><i class="fas fa-plus"></i> New
                        {{$modelTitle}}</inertia-button>
                    <inertia-button @click.native="$refreshDt(tableId)" classes="bg-indigo-100 hover:bg-green-200 text-indigo"><i class="fas fa-redo"></i> Refresh</inertia-button>
                </div>

            </div>
        </template>
        <div v-if="can.viewAny" class="flex flex-wrap px-4">
            <div class="z-10 flex-auto p-4 bg-white md:rounded-md md:shadow-md">
                <dt-component
                    :table-id="tableId"
                    :ajax-url="ajaxUrl"
                    :columns="columns"
                    :ajax-params="tableParams"
                    {{'@'}}show-model="showModel"
                    {{'@'}}edit-model="editModel"
                    {{'@'}}delete-model="confirmDeletion"
                />
                <jet-confirmation-modal title="Confirm Deletion" :show="confirmDelete">
                    <template v-slot:content>
                        <div>Are you sure you want to delete this record?</div>
                    </template>
                    <template v-slot:footer>
                        <div class="flex gap-x-2 justify-end">
                            <inertia-button as="button" type="button" @click.native.stop="cancelDelete" class="bg-red-500">Cancel</inertia-button>
                            <inertia-button as="button" type="button" @click.native.prevent="deleteModel" class="bg-green-500">Yes, Delete</inertia-button>
                        </div>
                    </template>
                </jet-confirmation-modal>
            </div>
        </div>
        <div v-else class="p-4 rounded-md shadow-md bg-red-100 text-red-500 font-bold ">
            You are not authorized to view a list of {{$modelTitlePlural}}
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
    import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
    import JetDialogModal from "@/Jetstream/DialogModal";
    import InertiaButton from "@/JigComponents/InertiaButton";
    import JigToggle from "@/JigComponents/JigToggle";
    import DtComponent from "@/JigComponents/DtComponent";
    import DisplayMixin from "@/Mixins/DisplayMixin";
    export default {
        name: "Index",
        components: {DtComponent, JigToggle, InertiaButton, JetConfirmationModal,JetDialogModal, JigLayout },
        props: {
            can: Object,
            columns: Array,
        },
        inject: ["$refreshDt","$dayjs"],
        data() {
            return {
                tableId: '{{$modelRouteAndViewName}}-dt',
                tableParams: {},
                datatable: null,
                confirmDelete: false,
                currentModel: null,
                withDisabled: false,
            }
        },
        mixins: [
            DisplayMixin,
        ],
        mounted() {
        },
        computed: {
            ajaxUrl() {
                const url = new URL(this.route('api.{{$modelRouteAndViewName}}.dt'));
                /*if (this.withDisabled) {
                    url.searchParams.append('include-disabled',true);
                }*/
                return url.href;
            }
        },
        methods: {
            showModel(model) {
                this.$inertia.visit(this.route('admin.{{$modelRouteAndViewName}}.show',model.id));
            },
            editModel(model) {
                this.$inertia.visit(this.route('admin.{{$modelRouteAndViewName}}.edit',model.id));
            },
            confirmDeletion(model) {
                this.currentModel = model;
                this.confirmDelete = true;
            },
            cancelDelete() {
                this.currentModel = false;
                this.confirmDelete = false;
            },
            async deleteModel() {
                const vm = this;
                this.confirmDelete = false;
                if (this.currentModel) {
                    this.$inertia.delete(route('admin.{{$modelRouteAndViewName}}.destroy', vm.currentModel)).then((res) => {
                        this.displayNotification('success', "Item deleted.");
                        vm.$refreshDt(vm.tableId);
                    });
                }
            },
            async toggleActivation(enabled,model) {
                const vm = this;
                console.log(enabled);
                axios.put(route(`api.{{$modelRouteAndViewName}}.update`,model.id),{
                    enabled: enabled
                }).then(res => {
                    this.displayNotification('success', res.data.message);
                    this.$refreshDt(this.tableId);
                })
            }
        }
    }
</script>

<style scoped>

</style>
