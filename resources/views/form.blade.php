<b-form v-on:submit.prevent="ok()">
@foreach($columns as $col)
@if($col['type'] === 'date' )
<b-form-group
    label-class="font-weight-bold" label="{{$col['label']}}"
    :invalid-feedback="errors.first('{{$col['name']}}')"
>
    <date-picker
        name="{{$col['name']}}" id="{{$col['name']}}"
        :config="dateConfig" v-model="form.{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :class="{'is-invalid': validateState('{{$col['name']}}')===false, 'is-valid': validateState('{{$col['name']}}')===true}"
    ></date-picker>
</b-form-group>
    @elseif($col['type'] === 'time')
<b-form-group
    label-class="font-weight-bold" label="{{$col['label']}}"
    :invalid-feedback="errors.first('{{$col['name']}}')"
>
    <date-picker
        name="{{$col['name']}}" id="{{$col['name']}}"
        :config="timeConfig" v-model="form.{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :class="{'is-invalid': validateState('{{$col['name']}}')===false, 'is-valid': validateState('{{$col['name']}}')===true}"
    ></date-picker>
</b-form-group>
    @elseif($col['type'] === 'datetime')
<b-form-group
    label-class="font-weight-bold" label="{{$col['name']}}"
    :invalid-feedback="errors.first('{{$col['name']}}')"
>
    <date-picker
        name="{{$col['name']}}" id="{{$col['name']}}"
        :config="dateTimeConfig" v-model="form.{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :class="{'is-invalid': validateState('{{$col['name']}}')===false, 'is-valid': validateState('{{$col['name']}}')===true}"
    ></date-picker>
</b-form-group>
    @elseif($col['type'] === 'boolean')
<b-form-group label-cols="4" label-class="font-weight-bolder" label="{{$col['label']}}">
    <b-form-checkbox
        size="lg"
        name="{{$col['name']}}" id="{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :state="validateState('{{$col['name']}}')" v-model="form.{{$col['name']}}"
    ></b-form-checkbox>
    <b-form-invalid-feedback v-if="errors.has('{{$col['name']}}')">
        @php
            echo '@{{errors.first(\''.$col['name'].'\')}}';
        @endphp
    </b-form-invalid-feedback>
</b-form-group>
    @elseif($col['type'] === 'text')
<b-form-group label="{{$col['label']}}" label-class="font-weight-bolder">
    <b-form-textarea
        name="{{$col['name']}}" id="{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :state="validateState('{{$col['name']}}')" v-model="form.{{$col['name']}}"
    ></b-form-textarea>
    <b-form-invalid-feedback v-if="errors.has('{{$col['name']}}')">
        @php
            echo '@{{errors.first(\''.$col['name'].'\')}}';
        @endphp
    </b-form-invalid-feedback>
</b-form-group>

        @elseif($col['name'] === 'password')
<b-form-group label="{{$col['label']}}" label-class="font-weight-bolder">
    <b-form-input
        type="password" name="{{$col['name']}}" ref="{{$col['name']}}" id="{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :state="validateState('{{$col['name']}}')" v-model="form.{{$col['name']}}"
    ></b-form-input>
    <b-form-invalid-feedback v-if="errors.has('{{$col['name']}}')">
        @php
            echo '@{{errors.first(\''.$col['name'].'\')}}';
        @endphp
    </b-form-invalid-feedback>
</b-form-group>

<b-form-group label="Confirm Password" label-class="font-weight-bolder">
    <b-form-input
        type="password" name="password_confirmation" id="password_confirmation"
        data-vv-as="password"
        v-validate="'confirmed:password|{{ implode('|', $col['frontendRules']) }}'"
        :state="validateState('password_confirmation')" v-model="form.password_confirmation"
    ></b-form-input>
    <b-form-invalid-feedback v-if="errors.has('password_confirmation')">
        @php
            echo '@{{errors.first(\'password_confirmation\')}}';
        @endphp
    </b-form-invalid-feedback>
</b-form-group>

    @else
<b-form-group label-class="font-weight-bolder" label="{{$col['label']}}">
    <b-form-input
        type="text" name="{{$col['name']}}" id="{{$col['name']}}"
        v-validate="'{{ implode('|', $col['frontendRules']) }}'"
        :state="validateState('{{$col['name']}}')" v-model="form.{{$col['name']}}"
    ></b-form-input>
    <b-form-invalid-feedback v-if="errors.has('{{$col['name']}}')">
        @php
            echo '@{{errors.first(\''.$col['name'].'\')}}';
        @endphp
    </b-form-invalid-feedback>
</b-form-group>
@endif
@endforeach
@if (count($relations))
@if(isset($relations['belongsTo']) && count($relations['belongsTo']))
@foreach($relations['belongsTo'] as $belongsTo)
<b-form-group label-class="font-weight-bolder" label="{{$belongsTo['related_model_title']}}">
    <infinite-select
        label="{{$belongsTo["label_column"]}}" v-model="form.{{$belongsTo['relationship_variable']}}" name="{{$belongsTo['relationship_variable']}}"
         @php
             echo 'api-url="{{route(\'api.'.$belongsTo['related_route_name'].'.index\')}}"'.PHP_EOL;
         @endphp
         :per-page="10"
        v-validate="'{{ implode('|', collect($relatable[$belongsTo['foreign_key']]['frontendRules'])->reject(function($rule){ return str_contains($rule,'integer');})->toArray()) }}'"
        :class="{'is-invalid': validateState('{{$belongsTo['relationship_variable']}}')===false, 'is-valid': validateState('{{$belongsTo['relationship_variable']}}')===true}"
    >
    </infinite-select>
    <b-form-invalid-feedback v-if="errors.has('{{$belongsTo['relationship_variable']}}')">
        @php
            echo '@{{errors.first(\''.$belongsTo['relationship_variable'].'\')}}';
        @endphp
    </b-form-invalid-feedback>
</b-form-group>
@endforeach
@endif
@endif
    <b-button class="d-none" type="submit"></b-button>
</b-form>

