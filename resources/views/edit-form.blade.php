@php
    $hasCheckbox = false;
    $hasSelect = false;
    $hasTextArea = false;
    $hasInput = false;
    $hasPassword = false;
@endphp
<template>
    <jig-layout>
        <template {{"#"}}header>
            <div class="flex flex-wrap items-center justify-between w-full px-4">
                <inertia-link :href="route('admin.{{$modelRouteAndViewName}}.index')" class="text-xl font-black text-white"><i class="fas fa-arrow-left"></i> Back | Edit
                    {{$modelTitle}} #@{{form.id}}</inertia-link>
            </div>
        </template>
        <div class="flex flex-wrap px-4">
            <div class="z-10 flex-auto max-w-2xl p-4 mx-auto bg-white md:rounded-md md:shadow-md">
                <form {{'@submit'}}.prevent="updateModel">
                    @foreach($columns as $col)
                        @if($col['type'] === 'date' )@php $hasInput = true; echo "\r";@endphp
                    <div class=" sm:col-span-4">
                        <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
                        <jet-input class="w-full" type="date" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
                    </div>
                        @elseif($col['type'] === 'time')@php $hasInput = true;echo "\r"; @endphp
                    <div class=" sm:col-span-4">
                        <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
                        <jet-input class="w-full" type="time" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
                        ></jet-input>
                        <jet-input-error :message="form.errors.{{$col['name']}}" class="mt-2" />
                    </div>
                        @elseif($col['type'] === 'datetime')@php $hasInput = true;echo "\r"; @endphp
                    <div class=" sm:col-span-4">
                        <jet-label for="{{$col['name']}}" value="{{$col['label']}}" />
                        <jet-input class="w-full" type="datetime-local" id="{{$col['name']}}" name="{{$col['name']}}" v-model="form.{{$col['name']}}"
                                   :class="{'border-red-500 sm:focus:border-red-300 sm:focus:ring-red-100': form.errors.{{$col['name']}}}"
                        ></jet-input>
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
                        <jet-button type="submit" class="bg-green-500" :disabled="form.processing">Submit</jet-button>
                    </div>
                </form>
            </div>
        </div>
    </jig-layout>
</template>

<script>
    import JigLayout from "@/Layouts/JigLayout";
    import JetLabel from "@/Jetstream/Label";
    import InertiaButton from "@/JigComponents/InertiaButton";
    import JetInputError from "@/Jetstream/InputError";
    import JetButton from "@/Jetstream/Button";
    @if($hasCheckbox)import JetCheckbox from "@/Jetstream/Checkbox";
@endif
    @if($hasInput)import JetInput from "@/Jetstream/Input";
@endif
    @if($hasTextArea)import JigTextarea from "@/JigComponents/JigTextarea";
@endif
    @if($hasSelect)import InfiniteSelect from '@/JigComponents/InfiniteSelect.vue';
@endif

    export default {
        name: "Edit",
        props: {
            model: Object,
        },
        components: {
            InertiaButton,
            JetLabel,
            JetButton,
            JetInputError,
            JigLayout,
            @if($hasInput)JetInput,
@endif
            @if($hasCheckbox)JetCheckbox,
@endif
            @if($hasTextArea)JigTextarea,
@endif
            @if($hasSelect)InfiniteSelect,
@endif

        },
        data() {
            return {
                form: this.$inertia.form({
                    ...this.model,
@if($hasPassword)
                    "password_confirmation": null,
@endif
                }),
            }
        },
        mounted() {
        },
        methods: {
            async updateModel() {
                return new Promise((resolve, reject) => {
                    this.form.put(this.route('admin.{{$modelRouteAndViewName}}.update',this.form.id))
                })
            }
        }
    }
</script>

<style scoped>

</style>
