<div class="card">
    <div class="card-header">
        <i class="fa fa-check"></i>{{'{{'}} trans('savannabits/admin-ui::admin.forms.publish') }}
    </div>
    <div class="card-block">

        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('published_at'), 'has-success': fields.published_at && fields.published_at.valid }">
            <label for="published_at" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-2' : 'col-md-4'">{{'{{'}} trans('admin.{{ $modelLangFormat }}.columns.published_at') }}</label>
            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
                <div class="input-group input-group--custom">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <datetime v-model="form.published_at" :config="datetimePickerConfig" v-validate="'date_format:yyyy-MM-dd HH:mm:ss'" class="flatpickr" :class="{'form-control-danger': errors.has('published_at'), 'form-control-success': fields.published_at && fields.published_at.valid}" id="published_at" name="published_at" placeholder="{{ trans('savannabits/admin-ui::admin.forms.select_date_and_time') }}"></datetime>
                </div>
                <div v-if="errors.has('published_at')" class="form-control-feedback form-text" v-cloak>{{'@'}}{{'{{'}}errors.first('published_at') }}</div>
            </div>
        </div>
    </div>
</div>
@if(in_array("created_by_admin_user_id", array_column($columns->toArray(), 'name')) && in_array("updated_by_admin_user_id", array_column($columns->toArray(), 'name')))
{{ '@'.'if(isset($showHistory))' }}
<div class="card">
    <div class="card-header">
        <i class="fa fa-history"></i> @{{ trans('savannabits/admin-ui::admin.forms.history') }}
    </div>
    <div class="card-body">
        @if(in_array("created_by_admin_user_id", array_column($columns->toArray(), 'name')))

        <div class="form-group row align-items-center">
            <label for="author_id" class="col-form-label text-right col-md-4 col-lg-3">{{'{{'}} trans('savannabits/admin-ui::admin.forms.created_by') }} :</label>
            <user-detail-tooltip :user="form.created_by_admin_user" :edit="true" :datetime="form.created_at" v-if="form.created_by_admin_user">
                <p>{{'{{'}} trans('savannabits/admin-ui::admin.forms.created_on') }} {{'@'}}{{'{{'}} form.created_at  }}</p>
            </user-detail-tooltip>
        </div>
        @endif
        @if(in_array("updated_by_admin_user_id", array_column($columns->toArray(), 'name')))

        <div class="form-group row align-items-center">
            <label for="author_id" class="col-form-label text-right col-md-4 col-lg-3">{{'{{'}} trans('savannabits/admin-ui::admin.forms.updated_by') }} :</label>
            <user-detail-tooltip :user="form.updated_by_admin_user" :edit="true" :datetime="form.updated_at" v-if="form.updated_by_admin_user">
                <p>{{'{{'}} trans('savannabits/admin-ui::admin.forms.updated_on') }} {{'@'}}{{'{{'}} form.updated_at }}</p>
            </user-detail-tooltip>
        </div>
        @endif

    </div>
</div>
{{ '@'.'endif' }}
@endif


