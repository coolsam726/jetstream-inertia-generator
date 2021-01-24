import AppForm from '../app-components/Form/AppForm';

Vue.component('{{ $modelJSName }}-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                @foreach($columns as $column){{ $column['name'].':' }} @if($column['type'] == 'json') {{ '{}' }} @elseif($column['type'] == 'boolean') {!! "false" !!} @else {!! "''" !!} @endif,
                @endforeach

            },
            mediaCollections: ['avatar']
        }
    },
    methods: {
        onSuccess(data) {
            if(data.notify) {
                this.$notify({ type: data.notify.type, title: data.notify.title, text: data.notify.message});
            } else if (data.redirect) {
                window.location.replace(data.redirect);
            }
        }
    }
});