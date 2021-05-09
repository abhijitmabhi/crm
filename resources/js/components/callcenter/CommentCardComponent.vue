<template>
    <div v-if="loaded">
        <i class="fa" :class="type"></i>
        <div class="timeline-item">
            <div class="timeline-body">
                <div
                    class="comment-header d-flex justify-content-between border-bottom mb-2 border-primary"
                >
                    <small>{{ displayName }}</small>
                    <small class="ml-2">{{ user.name }}</small>
                </div>
                <p v-html="comment.body" />
                <div class="timeline-gallery d-flex justify-content-start flex-wrap">
                    <a
                        v-for="image in comment.images"
                        :key="image"
                        v-b-modal="image"
                        class="card d-flex justify-content-center align-items-center overflow-hidden"
                    >
                        <b-img fluid :src="image" />
                        <b-modal :id="image" size="xl" ok-only>
                            <div class="d-flex justify-content-center">
                                <b-img fluid :src="image" />
                            </div>
                        </b-modal>
                    </a>
                </div>
                <div class="comment-footer d-flex mt-1">
                    <small> <i class="fa fa-clock"></i> {{ comment.created_at }} </small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null,
            loaded: false,
            slide: 0,
        };
    },
    mounted() {
        axios
            .get(`/api/users/${this.comment.user_id}`)
            .then(response => {
                this.user = response.data;
                this.loaded = true;
            })
            .catch(error => {
                if (error.response && error.response.status === 404) {
                    this.user = { name: "Unbekannter Benutzer" };
                }
            });
    },
    computed: {
        type() {
          return this.$enums.commentIcon[this.comment.reason];
        },
        displayName() {
          return this.$enums.commentDisplayName[this.comment.reason];
        },
    },
    props: {
        comment: Object,
    },
};
</script>
