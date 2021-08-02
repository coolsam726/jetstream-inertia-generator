<template>
    <div>
        <div class="font-black text-xl mb-2 flex flex-wrap justify-between items-center">
            <h2 class="">Assign Permissions</h2>
            <div>
                <inertia-button class="bg-green-500" type="button" @click.native.prevent="toggleAll(true)">Assign All</inertia-button>
                <inertia-button class="bg-red-500 text-white" type="button" @click.native.prevent="toggleAll(false)">Revoke All</inertia-button>
            </div>
        </div>
        <hr>
        <div class="p-4 mt-2 md:grid grid-cols-2 border rounded">
            <div v-for="(perms, groupIndex) of permissions" :key="groupIndex" class="border-b-2 mb-2">
                <div class="flex flex-wrap">
                    <h3 class="font-black text-xl pb-2">@{{groupIndex}}</h3>
                    <div class="">
                        <inertia-button {{'@'}}click.native.prevent="togglePermGroup(perms,true)" class="bg-green-500 py-1 mx-1 text-sm">Assign</inertia-button>
                        <inertia-button {{'@'}}click.native.prevent="togglePermGroup(perms,false)" class="bg-red-500 py-1 mx-1 text-sm">Revoke</inertia-button>
                    </div>
                </div>
                <div style="cursor: pointer" v-for="(perm, idx) of perms" :key="idx" class=" sm:col-span-4 px-10 flex border-b border-gray-100 py-3 items-center my-2 text-gray-600">
                    <jet-checkbox {{'@'}}change="togglePerm(perm)" class="p-2 inline-block" type="checkbox" :id="perm.name" :name="perm.name" :checked="!!perm.checked" v-model="perm.checked"
                                  :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': !perm.checked}"
                    />
                    <jet-label style="cursor: pointer" :for="perm.name" :class="`text-gray-400 hover:text-gray-600`" class="inline-block font-black text-xl ml-3" :value="perm.title"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JetLabel from "@/Jetstream/Label.vue";
import InertiaButton from "@/JigComponents/InertiaButton.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetInput from "@/Jetstream/Input.vue";
import Axios from "axios";
import DisplayMixin from "@/Mixins/DisplayMixin.js";
export default {
    name: "AssignPerms",
    components: {
        InertiaButton,
        JetLabel,
        JetInputError,
        JetInput,
        JetCheckbox
    },
    props: {
        permissions: Object,
        role: {
            required: true,
            type: Object,
        }
    },
    mixins: [DisplayMixin],
    methods: {
        async togglePerm(perm) {
            const vm = this;
            Axios.post(this.route('api.roles.assign-permission',this.role.id),{permission: perm}).then(res => {
                this.$emit('toggle-success',res.data.message);
            }).catch(err => {
                this.$emit('toggle-error',(err.response?.data?.message || err.message || err));
            });
        },
        async toggleAll(checked) {
            const vm = this;
            for (const groupKey in vm.permissions) {
                if (!vm.permissions.hasOwnProperty(groupKey)) {return false;}
                let permGroup = vm.permissions[groupKey];
                await vm.togglePermGroup(permGroup, checked);
            }
            this.displayNotification('success',`All permissions have been ${checked ? 'Assigned.': 'Revoked.'}`)
        },
        async togglePermGroup(permGroup, checked) {
            const vm = this;
            for (const key in permGroup) {
                if (!permGroup.hasOwnProperty(key)) {return false;}
                let perm = permGroup[key];
                if (perm.checked && !checked) {
                    // Uncheck
                    perm.checked = false;
                    await vm.togglePerm(perm);
                } else if (checked && !perm.checked) {
                    // Check
                    perm.checked = true;
                    await vm.togglePerm(perm);
                } else {
                }
            }
            this.displayNotification('success',`All permissions in the group have been ${checked ? 'Assigned.': 'Revoked.'}`)
        }
    }
}
</script>

<style scoped>

</style>
