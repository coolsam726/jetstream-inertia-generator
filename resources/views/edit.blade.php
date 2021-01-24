{{'@'}}extends('savannabits/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.edit', ['name' => ${{ $modelVariableName }}->{{$modelTitle}}]))

{{'@'}}section('body')

    <div class="container-xl">
@if(!$isUsedTwoColumnsLayout)
        <div class="card">
@endif

            @if($hasTranslatable)<{{ $modelJSName }}-form
                :action="'{{'{{'}} ${{ $modelVariableName }}->resource_url }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJsonAllLocales() }}"
                :locales="@{{ json_encode($locales) }}"
                :send-empty-locales="false"
                v-cloak
                inline-template>
            @else<{{ $modelJSName }}-form
                :action="'{{'{{'}} ${{ $modelVariableName }}->resource_url }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJson() }}"
                v-cloak
                inline-template>
            @endif

                <form class="form-horizontal form-edit" method="post" {{'@'}}submit.prevent="onSubmit" :action="action" novalidate>

@if($isUsedTwoColumnsLayout)
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fa fa-pencil"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.edit', ['name' => ${{ $modelVariableName }}->{{$modelTitle}}]) }}
                                </div>
                                <div class="card-body">
                                    {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                            {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements-right', ['showHistory' => true])
                        </div>
                    </div>
                    @else

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.edit', ['name' => ${{ $modelVariableName }}->{{$modelTitle}}]) }}
                    </div>

                    <div class="card-body">
                        {{'@'}}include('admin.{{ $modelDotNotation }}.components.form-elements')
                    </div>
                    @endif

                    @if($isUsedTwoColumnsLayout)<button type="submit" class="btn btn-primary fixed-cta-button button-save" :disabled="submiting">
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

    @if(!$isUsedTwoColumnsLayout)
    </div>
    @endif

</div>

{{'@'}}endsection
