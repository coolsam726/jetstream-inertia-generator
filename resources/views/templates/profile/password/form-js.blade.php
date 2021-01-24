import AppForm from '../app-components/Form/AppForm';

Vue.component('{{ $modelJSName }}-form', {
    mixins: [AppForm],
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