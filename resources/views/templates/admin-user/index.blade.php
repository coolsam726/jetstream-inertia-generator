{{'@'}}extends('savannabits/admin-ui::admin.layout.default')

{{'@'}}section('title', trans('admin.{{ $modelLangFormat }}.actions.index'))

{{'@'}}section('body')

    <{{ $modelJSName }}-listing
        :data="{{'{{'}} $data->toJson() }}"
        :activation="!!'@{{ $activation }}'"
        :url="'{{'{{'}} url('admin/{{ $resource }}') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.index') }}
@if($export)
                        <a class="btn btn-primary btn-sm pull-right m-b-0 ml-2" href="{{'{{'}} url('admin/{{ $resource }}/export') }}" role="button"><i class="fa fa-file-excel-o"></i>&nbsp; {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.export') }}</a>
@endif
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{'{{'}} url('admin/{{ $resource }}/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{'{{'}} trans('admin.{{ $modelLangFormat }}.actions.create') }}</a>
                    </div>
                    <div class="card-body" v-cloak>
                        <form @submit.prevent="">
                            <div class="row justify-content-md-between">
                                <div class="col col-lg-7 col-xl-5 form-group">
                                    <div class="input-group">
                                        <input class="form-control" placeholder="@{{ trans('savannabits/admin-ui::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; @{{ trans('savannabits/admin-ui::admin.btn.search') }}</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-sm-auto form-group ">
                                    <select class="form-control" v-model="pagination.state.per_page">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                            </div>
                        </form>

                        <table class="table table-hover table-listing">
                            <thead>
                                <tr>
                                    @foreach($columns as $col)<th is='sortable' :column="'{{ $col['name'] }}'"@if($col['name'] == 'activated') v-if="activation"@endif>{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.{{ $col['name'] }}') }}</th>
                                    @endforeach

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in collection">
                                    @foreach($columns as $col)<td @if($col['name'] == 'activated')v-if="activation"@endif>@if($col['switch'])

                                        <label class="switch switch-3d switch-success">
                                            <input type="checkbox" class="switch-input" v-model="collection[index].{{ $col['name'] }}" @change="toggleSwitch(item.resource_url, '{{ $col['name'] }}', collection[index])">
                                            <span class="switch-slider"></span>
                                        </label>
                                    @elseif($col['name'] == 'forbidden')

                                        <label class="switch switch-3d switch-danger">
                                            <input type="checkbox" class="switch-input" v-model="collection[index].{{ $col['name'] }}" @change="toggleSwitch(item.resource_url, '{{ $col['name'] }}', collection[index])">
                                            <span class="switch-slider"></span>
                                        </label>
                                    @else{{'@{{'}} item.{{ $col['name'] }}{{ $col['filters'] }} }}@endif</td>
                                    @endforeach

                                    <td>
                                        <div class="row no-gutters">
                                            {{'@'}}can('admin.admin-user.impersonal-login')
                                            <div class="col-auto">
                                                <button class="btn btn-sm btn-success" v-show="item.activated" @click="impersonalLogin(item.resource_url + '/impersonal-login', item)" title="Impersonal login" role="button"><i class="fa fa-user-o"></i></button>
                                            </div>
                                            {{'@'}}endcan
                                            <div class="col-auto">
                                                <button class="btn btn-sm btn-warning" v-show="!item.activated" @click="resendActivation(item.resource_url + '/resend-activation')" title="Resend activation" role="button"><i class="fa fa-envelope-o"></i></button>
                                            </div>
                                            <div class="col-auto">
                                                <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="@{{ trans('savannabits/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                            </div>
                                            <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                <button type="submit" class="btn btn-sm btn-danger" title="@{{ trans('savannabits/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row" v-if="pagination.state.total > 0">
                            <div class="col-sm">
                                <span class="pagination-caption">@{{ trans('savannabits/admin-ui::admin.pagination.overview') }}</span>
                            </div>
                            <div class="col-sm-auto">
                                <pagination></pagination>
                            </div>
                        </div>

	                    <div class="no-items-found" v-if="!collection.length > 0">
		                    <i class="icon-magnifier"></i>
                            <h3>@{{ trans('savannabits/admin-ui::admin.index.no_items') }}</h3>
                            <p>@{{ trans('savannabits/admin-ui::admin.index.try_changing_items') }}</p>
                            <a class="btn btn-primary btn-spinner" href="{{'{{'}} url('admin/{{ $resource }}/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; @{{ trans('savannabits/admin-ui::admin.btn.new') }} {{ $modelBaseName }}</a>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
    </{{ $modelJSName }}-listing>

{{'@'}}endsection
