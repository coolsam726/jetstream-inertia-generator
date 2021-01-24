{{'@'}}extends('savannabits/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.create'))

{{'@'}}section('body')

    <div class="container-xl">

        @if(!$isUsedTwoColumnsLayout)
        <div class="card">
        @endif

        <{{ $modelJSName }}-form
            :action="'{{'{{'}} url('admin/{{ $resource }}') }}'"
@if($hasTranslatable)
            :locales="@{{ json_encode($locales) }}"
            :send-empty-locales="false"
@endif
            v-cloak
            inline-template>

            <form class="form-horizontal form-create" method="post" {{'@'}}submit.prevent="onSubmit" :action="action" novalidate>
                @if($isUsedTwoColumnsLayout)

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <i class="fa fa-plus"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}
                            </div>
                            <div class="card-body">
                                {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                        {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements-right')
                    </div>
                </div>
                @else

                <div class="card-header">
                    <i class="fa fa-plus"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}
                </div>

                <div class="card-body">
                    {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')
                </div>
                @endif
                @if($isUsedTwoColumnsLayout)

                <button type="submit" class="btn btn-primary fixed-cta-button button-save" :disabled="submiting">
                    <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-save'"></i>
                    @{{ trans('savannabits/admin-ui::admin.btn.save') }}
                </button>
                <button type="submit" style="display: none" class="btn btn-success fixed-cta-button button-saved" :disabled="submiting" :class="">
                    <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-check'"></i>
                    <span>@{{ trans('savannabits/admin-ui::admin.btn.saved') }}</span>
                </button>
                @else

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="submiting">
                        <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                        @{{ trans('savannabits/admin-ui::admin.btn.save') }}
                    </button>
                </div>
                @endif

            </form>

        </{{ $modelJSName }}-form>

        </div>

    @if(!$isUsedTwoColumnsLayout)
    </div>

    @endif

{{'@'}}endsection
