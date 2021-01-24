{{'@'}}extends('savannabits/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.edit_profile'))

{{'@'}}section('body')

    <div class="container-xl">

        <div class="card">

            <{{ $modelJSName }}-form
                :action="'{{'{{'}} url('{{ $route }}') }}'"
                :data="{{'{{'}} ${{ $modelVariableName }}->toJson() }}"
                @if($hasTranslatable):locales="@{{ json_encode($locales) }}"
                :send-empty-locales="false"@endif

                inline-template>

                <form class="form-horizontal form-edit" method="post" {{'@'}}submit.prevent="onSubmit" :action="action">

                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.edit_profile') }}
                    </div>

                    <div class="card-body">

@php
    $columns = $columns->reject(function($column) {
        return in_array($column['name'], ['password', 'activated', 'forbidden']);
    });
@endphp
                        @include('savannabits/admin-generator::templates.profile.form')

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            @{{ trans('savannabits/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

            </{{ $modelJSName }}-form>

        </div>

    </div>

{{'@'}}endsection
