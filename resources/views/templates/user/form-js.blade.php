import AppForm from '../app-components/Form/AppForm';

Vue.component('{{ $modelJSName }}-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                @foreach($columns as $column){{ $column['name'].':' }} @if($column['type'] == 'json') {{ '{}' }} @elseif($column['type'] == 'boolean') {!! "false" !!} @else {!! "''" !!} @endif,
                @endforeach

            }
        }
    }
});