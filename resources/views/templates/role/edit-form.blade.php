@php
    $hasCheckbox = false;
    $hasSelect = false;
    $hasTextArea = false;
    $hasInput = false;
    $hasDate = false;
    $hasPassword = false;
@endphp
<template>
    <jig-tabs :class="`border-none`" nav-classes="bg-secondary-300 rounded-t-lg border-b-4 border-primary">
        <template #nav>
            <jig-tab-link @activate="setTab" :active-classes="tabActiveClasses" :tab-controller="activeTab"
                          tab="basic-info">Basic Info
            </jig-tab-link>
            <jig-tab-link @activate="setTab" :active-classes="tabActiveClasses" :tab-controller="activeTab"
                          tab="assign-permissions">Assign Permissions
            </jig-tab-link>
        </template>
        <template #content>
            <jig-tab name="basic-info" :tab-controller="activeTab">
                <form {{'@submit'}}.prevent="updateModel">
                @foreach($columns as $col)
@if($col['type'] === 'date' )@php $hasDate = true; echo "\r";@endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jig-datepicker
                class="w-full"
                id="{{$col['name']}}"
                name="{{$col['name']}}"
                v-model="form.{{$col['name']}}"
                data-date-format="Y-m-d"
                :data-alt-input="true"
                data-alt-format="l M J, Y"
                :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jig-datepicker>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['type'] === 'time')@php $hasDate = true;echo "\r"; @endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jig-datepicker class="w-full"
                            data-date-format="H:i"
                            :data-alt-input="true"
                            data-alt-format="h:i K"
                            data-default-hour="9"
                            :data-enable-time="true"
                            :data-no-calendar="true"
                            name="{{$col['name']}}"
                            v-model="form.{{$col['name']}}"
                            id="{{$col['name']}}"
                            :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jig-datepicker>
        <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
    </div>
@elseif($col['type'] === 'datetime')@php $hasDate = true;echo "\r"; @endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jig-datepicker class="w-full"
                            data-date-format="Y-m-d H:i:s"
                            :data-alt-input="true"
                            data-alt-format="l M J, Y at h:i K"
                            data-default-hour="9"
                            :data-enable-time="true"
                            name="{{$col['name']}}"
                            v-model="form.{{$col['name']}}"
                            id="{{$col['name']}}"
                            :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jig-datepicker>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['type'] === 'boolean')@php $hasCheckbox = true; echo "\r"; @endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jet-checkbox class="p-2" type="checkbox" id="{{$col['name']}}" name="{{$col['name']}}" :checked="form.{{$col['name']}}" v-model="form.{{$col['name']}}"
                          :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jet-checkbox>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['type'] === 'text')@php $hasTextArea = true; echo "\r"; @endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jig-textarea class="w-full" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                          :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jig-textarea>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['type'] === 'double'|| $col['type'] ==='integer')@php $hasInput = true; echo "\r";@endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jet-input class="w-full" type="number" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jet-input>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['name'] === 'password') @php $hasInput = true; $hasPassword = true; echo "\r";@endphp
                    <div class=" sm:col-span-4">
                        <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
                        <jet-input class="w-full" type="password" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
                    </div>
                    <div class=" sm:col-span-4">
                        <jet-label for="{{$col['name']}}_confirmation" value="Repeat {{$col['label']}}" />
                        <jet-input class="w-full" type="password" id="{{$col['name']}}_confirmation" name="{{$col['name']}}_confirmation" v-model="form.{{$col['name']}}_confirmation"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}_confirmation}"
                        ></jet-input>
                    </div>
@else @php $hasInput = true; echo "\r"; @endphp
                    <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jet-input class="w-full" type="text" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jet-input>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@endif
                @endforeach
                @if (count($relations))
@if(isset($relations['belongsTo']) && count($relations['belongsTo']))
    @foreach($relations['belongsTo'] as $belongsTo)@php $hasSelect = true; echo "\r"; @endphp
    <div class=" sm:col-span-4">
        <jet-label for="{{$belongsTo['relationship_variable']}}" value="{{$belongsTo['related_model_title']}}" />
        <infinite-select class="w-full" :per-page="15" :api-url="route('api.{{$belongsTo['related_route_name']}}.index')"
                         id="{{$belongsTo['relationship_variable']}}" name="{{$belongsTo['relationship_variable']}}"
                         v-model="form.{{$belongsTo['relationship_variable']}}" label="{{$belongsTo["label_column"]}}"
                         :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$belongsTo['relationship_variable']}}}"
        ></infinite-select>
        <jet-input-error :message="form.errors.{{$belongsTo['relationship_variable']}}" class="mt-2" />
    </div>
    @endforeach
@endif
                @endif

                    <div class="mt-2 text-right">
                        <inertia-button type="submit" class="bg-primary font-semibold text-white" :disabled="form.processing">Submit</inertia-button>
                    </div>
                </form>
            </jig-tab>
            <jig-tab name="assign-permissions" :tab-controller="activeTab">
                <assign-perms :role="model" :permissions="permissions"></assign-perms>
            </jig-tab>
        </template>
    </jig-tabs>
</template>

<script>
import JetLabel from "@/Jetstream/Label.vue";
import InertiaButton from "@/JigComponents/InertiaButton.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import JetInput from "@/Jetstream/Input.vue";
import JigTabs from "@/JigComponents/JigTabs.vue";
import JigTabLink from "@/JigComponents/JigTabLink.vue";
import JigTab from "@/JigComponents/JigTab.vue";
import AssignPerms from "@/Pages/Roles/AssignPerms.vue";

export default {
    name: "Edit{{$modelPlural}}Form",
    props: {
        model: Object,
        permissions: Object,
    },
    components: {
        AssignPerms,
        JigTab,
        JigTabLink,
        JigTabs,
        InertiaButton,
        JetLabel,
        JetInputError,
        JetInput,

    },
    data() {
        return {
            form: useForm({
                ...this.model,
            }, {remember: false}),
            activeTab: 'basic-info',
            tabActiveClasses: "bg-primary font-bold text-secondary rounded-t-lg hover:bg-primary-200 hover:text-gray-800"
        }
    },
    mounted() {
    },
    computed: {
        flash() {
            return this.$page.props.flash || {}
        }
    },
    methods: {
        async updateModel() {
            this.form.put(this.route('admin.roles.update', this.form.id),
                {
                    onSuccess: res => {
                        if (this.flash.success) {
                            this.$emit('success', this.flash.success);
                        } else if (this.flash.error) {
                            this.$emit('error', this.flash.error);
                        } else {
                            this.$emit('error', "Unknown server error.")
                        }
                    },
                    onError: res => {
                        this.$emit('error', "A server error occurred");
                    }
                }, {remember: false, preserveState: true})
        },
        setTab(tab){
            this.activeTab = tab;
        }
    }
}
</script>

<style scoped>

</style>
