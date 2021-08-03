export default {
    methods: {
        displayNotification(type, message, title=null) {
            this.$notify({
                group: "top",
                type: type,
                title: title || type,
                text: message
            }, 3500);
        },
    }
}
