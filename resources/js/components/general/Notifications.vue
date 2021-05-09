<template></template>
<script>
export default {
    data() {
        return {};
    },
    props: {
        userId: {
            type: String | Number,
            required: true
        }
    },
    mounted() {
        window.Echo.private(`user.${this.userId}.notifications`).listen(
            ".modalNotification",
            e => {
                let message = e.message;
                console.log(e);
                this.$bvModal
                    .msgBoxConfirm(message.body, {
                        title: message.title,
                        centered: true,
                        okTitle: "Zum Lead",
                        cancelTitle: "Abbrechen"
                    })
                    .then(() => {
                        if (message.link) {
                            window.location = message.link;
                        }
                    });
            }
        );
    }
};
</script>

