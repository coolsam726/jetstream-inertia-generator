                        @foreach($columns as $col)@if($col['name'] == 'password')<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <input type="password" v-model="form.{{ $col['name'] }}" v-validate="'required|{{ implode('|', collect($col['frontendRules'])->reject(function($rule) use ($col) { return $rule === 'confirmed:'.$col['name'];})->toArray()) }}'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}" ref="{{ $col['name'] }}">
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}_confirmation'), 'has-success': fields.{{ $col['name'] }}_confirmation && fields.{{ $col['name'] }}_confirmation.valid }">
                            <label for="{{ $col['name'] }}_confirmation" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}_repeat') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <input type="password" v-model="form.{{ $col['name'] }}_confirmation" v-validate="'required|{{ implode('|', $col['frontendRules']) }}'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}_confirmation'), 'form-control-success': fields.{{ $col['name'] }}_confirmation && fields.{{ $col['name'] }}_confirmation.valid}" id="{{ $col['name'] }}_confirmation" name="{{ $col['name'] }}_confirmation" placeholder="{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}" data-vv-as="{{ $col['name'] }}">
                                <div v-if="errors.has('{{ $col['name'] }}_confirmation')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}_confirmation') }}</div>
                            </div>
                        </div>
                        @elseif($col['name'] == 'language')<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <multiselect v-model="form.{{ $col['name'] }}" placeholder="@{{ trans('savannabits/admin-ui::admin.forms.select_an_option') }}" :options="@{{ $locales->toJson() }}" open-direction="bottom"></multiselect>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>@@{{ errors.first('@php echo $col['name']; @endphp') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'date')<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <datetime v-model="form.{{ $col['name'] }}" :config="datePickerConfig" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="flatpickr" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="@{{ trans('savannabits/admin-ui::admin.forms.select_a_date') }}"></datetime>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'time')<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <datetime v-model="form.{{ $col['name'] }}" :config="timePickerConfig" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="flatpickr" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="@{{ trans('savannabits/admin-ui::admin.forms.select_a_time') }}"></datetime>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'datetime')<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <datetime v-model="form.{{ $col['name'] }}" :config="datetimePickerConfig" v-validate="'{{ implode('|', $col['frontendRules']) }}'" class="flatpickr" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="@{{ trans('savannabits/admin-ui::admin.forms.select_date_and_time') }}"></datetime>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'text')<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <div>
                                    <textarea v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" @input="validate($event)" class="hidden-xs-up" id="{{ $col['name'] }}" name="{{ $col['name'] }}"></textarea>
                                    <quill-editor v-model="form.{{ $col['name'] }}" :options="wysiwygConfig" />
                                </div>
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @elseif($col['type'] == 'boolean')<div class="form-group row" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-9'">
                                <input class="form-check-input" id="{{ $col['name'] }}" type="checkbox" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" data-vv-name="{{ $col['name'] }}"  name="{{ $col['name'] }}_fake_element">
                                <label class="form-check-label" for="{{ $col['name'] }}">
                                    {{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}
                                </label>
                                <input type="hidden" name="{{ $col['name'] }}" :value="form.{{ $col['name'] }}">
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @else<div class="form-group row align-items-center" :class="{'has-danger': errors.has('{{ $col['name'] }}'), 'has-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid }">
                            <label for="{{ $col['name'] }}" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-3'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</label>
                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-7'">
                                <input type="text" v-model="form.{{ $col['name'] }}" v-validate="'{{ implode('|', $col['frontendRules']) }}'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('{{ $col['name'] }}'), 'form-control-success': fields.{{ $col['name'] }} && fields.{{ $col['name'] }}.valid}" id="{{ $col['name'] }}" name="{{ $col['name'] }}" placeholder="{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}">
                                <div v-if="errors.has('{{ $col['name'] }}')" class="form-control-feedback form-text" v-cloak>{{'@{{'}} errors.first('{{ $col['name'] }}') }}</div>
                            </div>
                        </div>
                        @endif

                        @endforeach
