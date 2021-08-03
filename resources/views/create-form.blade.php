@php
    $hasCheckbox = false;
    $hasSelect = false;
    $hasTextArea = false;
    $hasInput = false;
    $hasDate = false;
@endphp
<template>
    <form class="w-full" {{'@submit'}}.prevent="storeModel">
    @foreach($columns as $col)
    @if($col['type'] === 'date' )
    @php $hasDate = true; @endphp

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
@elseif($col['type'] === 'time'){{"\r"}}
@php $hasDate = true; @endphp

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
@elseif($col['type'] === 'datetime')
@php $hasDate = true; @endphp

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
@elseif($col['type'] === 'boolean')
@php $hasCheckbox = true; @endphp

        <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jet-checkbox class="p-2" type="checkbox" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                          :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jet-checkbox>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['type'] === 'text')
@php $hasTextArea = true; @endphp

        <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jig-textarea class="w-full" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                          :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jig-textarea>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['type'] === 'double'|| $col['type'] ==='integer')
@php $hasInput = true; @endphp

        <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jet-input class="w-full" type="number" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jet-input>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
@elseif($col['name'] === 'password')
@php $hasInput = true; @endphp

        <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
            <jet-input type="time" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
            ></jet-input>
            <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
        </div>
        <div class=" sm:col-span-4">
            <jet-label for="{{$col['name']}}_confirmation" value="Repeat {{$col['label']}}" />
            <jet-input type="time" id="{{$col['name']}}_confirmation" name="{{$col['name']}}_confirmation" v-model="form.{{$col['name']}}_confirmation"
                       :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}_confirmation}"
            ></jet-input>
        </div>
@else
@php $hasInput = true; @endphp

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
@foreach($relations['belongsTo'] as $belongsTo)
    @php $hasSelect = true; @endphp
        <div class=" sm:col-span-4">
            <jet-label for="{{$belongsTo['relationship_variable']}}" value="{{$belongsTo['related_model_title']}}" />
            <infinite-select :per-page="15" :api-url="route('api.{{$belongsTo['related_route_name']}}.index')"
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
            <inertia-button type="submit" class="font-semibold bg-success disabled:opacity-25" :disabled="form.processing">Submit</inertia-button>
        </div>
    </form>
</template>

<script>
@if($hasCheckbox)
    import JetCheckbox from "@/Jetstream/Checkbox.vue";
@endif
@if($hasDate)
    import JigDatepicker from "@/JigComponents/JigDatepicker.vue";
@endif
@if($hasInput)
    import JetInput from "@/Jetstream/Input.vue";
@endif
@if($hasTextArea)
    import JigTextarea from "@/JigComponents/JigTextarea.vue";
@endif
@if($hasSelect)
    import InfiniteSelect from '@/JigComponents/InfiniteSelect.vue';
@endif
    import JetLabel from "@/Jetstream/Label.vue";
    import InertiaButton from "@/JigComponents/InertiaButton.vue";
    import JetInputError from "@/Jetstream/InputError.vue"
    import {useForm} from "@inertiajs/inertia-vue3";
    export default {
        name: "Create{{$modelPlural}}Form",
        components: {
            InertiaButton,
            JetInputError,
            JetLabel,
            @if($hasDate) JigDatepicker,{{"\r"}}@endif
            @if($hasInput) JetInput,{{"\r"}}@endif
            @if($hasCheckbox) JetCheckbox,{{"\r"}}@endif
            @if($hasTextArea) JigTextarea,{{"\r"}}@endif
            @if($hasSelect) InfiniteSelect,{{"\r"}}@endif

        },
        data() {
            return {
                form: useForm({
                    @foreach($columns as $key => $col)
@if($col["type"] ==='boolean')
{{$key}}: false,
@else
{{$key}}: null,
@endif
                    @endforeach
                    @if (count($relations))
@if(isset($relations['belongsTo']) && count($relations['belongsTo']))
@foreach($relations['belongsTo'] as $belongsTo)
"{{$belongsTo["relationship_variable"]}}": null,
@endforeach
@endif
                    @endif

                }, {remember: false}),
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
            async storeModel() {
                this.form.post(this.route('admin.{{$modelRouteAndViewName}}.store'),{
                    onSuccess: res => {
                        if (this.flash.success) {
                            this.$emit('success',this.flash.success);
                        } else if (this.flash.error) {
                            this.$emit('error',this.flash.error);
                        } else {
                            this.$emit('error',"Unknown server error.")
                        }
                    },
                    onError: res => {
                        this.$emit('error',"A server error occurred");
                    }
                },{remember: false, preserveState: true})
            }
        }
    }
</script>

<style scoped>

</style>
