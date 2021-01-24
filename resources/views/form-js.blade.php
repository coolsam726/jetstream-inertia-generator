import BackendModule from "../components/BackendModule";
import InfiniteSelect from "../components/InfiniteSelect";

export default {
    mixins:[BackendModule],
    components: {
        InfiniteSelect,
    },
    data: () => ({
        model: {
            @foreach($columns as $column)
{{ $column['name'].':' }}@if($column['type'] == 'json') {{ 'this.getLocalizedFormDefaults()' }}@elseif($column['type'] == 'boolean') {!! "false" !!} @else {!! 'null' !!} @endif,
@if($column['name'] ==='password')
            password_confirmation: null,
            @endif
            @endforeach
@if(isset($relations['belongsTo']) && count($relations["belongsTo"]))
            @foreach($relations["belongsTo"] as $relation){{$relation['relationship_variable'].': null'}},
            @endforeach
            @endif

        },
    }),
}
