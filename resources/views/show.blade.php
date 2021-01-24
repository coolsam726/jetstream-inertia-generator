@foreach($columns as $column)
<b-card v-if="form.{{$column['name']}}" no-body>
    <b-card-title class="font-weight-bolder card-title p-1 m-1">{{$column['label']}}</b-card-title>
    <span class="p-1 m-1">
        @php
echo '@{{ form.'.$column['name'].'}}';
        @endphp
    </span>
</b-card>
@endforeach
@if (isset($relations['belongsTo']) && count($relations["belongsTo"]))
@foreach($relations["belongsTo"] as $parent)
<b-card v-if="form.{{$parent['relationship_variable']}}" no-body>
    <b-card-title class="font-weight-bolder card-title p-1 m-1">{{$parent['related_model_title']}}</b-card-title>
    <span class="p-1 m-1">
    @php
echo '@{{ form.'.$parent['relationship_variable'].'.'.$parent["label_column"].'}}';
    @endphp
    </span>
</b-card>
@endforeach
@endif
