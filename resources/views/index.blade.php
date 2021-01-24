{{"@"}}extends('layouts.backend')
{{"@"}}push('styles')
{{"@"}}endpush

{{"@"}}section('content')
    <div class="container-fluid">
        <{{$modelJSName}}-component
            table-id="{{$modelJSName}}-dt"
            form-dialog-ref="{{$modelVariableName}}FormDialog"
            details-dialog-ref="{{$modelVariableName}}DetailsDialog"
            delete-dialog-ref="{{$modelVariableName}}DeleteDialog"
            app-url="@{{config('app.url')}}"
@if(config('savadmin.tenancy.use_tenancy'))
            tenant="@{{ tenant('id') }}"
            tenant-header-name="@{{ config('savadmin.tenancy.header_name') }}"
            tenant-query-param="@{{ config('savadmin.tenancy.query_parameter_name') }}"
            @endif
@php
            echo 'api-route="{{route(\'api.'.$modelRouteAndViewName.'.index\')}}"'.PHP_EOL;
            @endphp
            v-cloak inline-template
        >
            <b-row>
                <b-col>
                    <b-card title="{{\Illuminate\Support\Str::pluralStudly($modelTitle)}} List">
                        <div class="text-right mb-2">
                            {{'@'}}can('{{$modelRouteAndViewName}}.create')<b-button v-on:click="showFormDialog()" variant="primary"><i class="mdi mdi-plus"></i> New {{$modelTitle}}</b-button>
                            {{'@'}}endcan
                        </div>
                        {{'@'}}can('{{$modelRouteAndViewName}}.index')
                        <dt-component table-id="{{$modelRouteAndViewName}}-dt"
                                      @php
                                      echo 'ajax-url="{{route(\'api.'.$modelRouteAndViewName.'.dt\')}}"'.PHP_EOL
                                      @endphp
                                      v-cloak
                                      :columns="@{{json_encode($columns)}}"
                                      table-classes="table table-hover"
@if(config('savadmin.tenancy.use_tenancy'))
                                      tenant="@{{ tenant('id') }}"
                                      tenant-header-name="@{{ config('savadmin.tenancy.header_name') }}"
                                      tenant-query-param="@{{ config('savadmin.tenancy.query_parameter_name') }}"
@endif
                                      v-on:edit-{{str_singular($modelRouteAndViewName)}}="showFormDialog"
                                      v-on:show-{{str_singular($modelRouteAndViewName)}}="showDetailsDialog"
                                      v-on:delete-{{str_singular($modelRouteAndViewName)}}="showDeleteDialog"
                        ></dt-component>
                        {{'@'}}endcan
                    </b-card>
                    {{'@'}}canany(['{{$modelRouteAndViewName}}.create','{{$modelRouteAndViewName}}.edit'])
                    <b-modal size="lg" v-if="form" v-on:ok.prevent="onFormSubmit" no-close-on-backdrop v-cloak ref="{{$modelVariableName}}FormDialog">
                        <template v-slot:modal-title>
                            <h4 v-if="form.id" class="font-weight-bolder">Edit {{$modelTitle}} @@{{ form.id }}</h4>
                            <h4 v-else class="font-weight-bolder">Create New {{$modelTitle}}</h4>
                        </template>
                        <template v-slot:default="{ ok, cancel }">
                            {{"@"}}include("backend.{{$modelJSName}}.form")
                        </template>
                    </b-modal>
                    {{'@'}}endcanany
                    {{'@'}}can('{{$modelRouteAndViewName}}.show')
                    <b-modal size="lg" v-if="form" scrollable v-cloak ref="{{$modelVariableName}}DetailsDialog">
                        {{"@"}}include('backend.{{$modelJSName}}.show')
                    </b-modal>
                    {{'@'}}endcan
                    {{'@'}}can('{{$modelRouteAndViewName}}.delete')
                    <b-modal size="sm" v-on:ok.prevent="deleteItem" hide-footer hide-header body-bg-variant="danger" body-text-variant="light" centered v-if="form" scrollable v-cloak ref="{{$modelVariableName}}DeleteDialog">
                        <template v-slot:default="{ok,hide}">
                            Are you sure you want to delete this {{$modelTitle}}?
                            <div class="text-right">
                                <b-button v-on:click="hide()" variant="light">No</b-button>
                                <b-button v-on:click="ok()" variant="primary">Yes</b-button>
                            </div>
                        </template>
                    </b-modal>
                    {{'@'}}endcan
                </b-col>
            </b-row>
        </{{$modelJSName}}-component>
    </div>
{{"@"}}endsection

{{"@"}}push('scripts')
{{"@"}}endpush
