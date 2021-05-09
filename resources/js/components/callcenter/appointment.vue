<template>
    <b-container fluid>
        <b-row>
            <b-col
                cols="6"
                v-if="data"
                class="d-flex align-content-start flex-wrap justify-content-center w-100 mb-3"
            >
                <lead-edit
                    v-if="edit"
                    edit
                    :lead="data"
                    @abort="edit = false"
                    @update_lead="leadUpdated"
                />
                <lead-show v-else :lead="data" />
                <b-button
                    v-if="!edit"
                    class="mt-4 d-flex justify-content-center border border-secondary"
                    @click="startEdit"
                >Lead bearbeiten</b-button>
            </b-col>
            <b-col v-else>
                <b-spinner />
            </b-col>

            <b-col class="d-flex justify-content-center flex-wrap mb-3" cols="6">
                <!-- Display:center, justify content center, flexwrap wrap -->

                <history
                    :lead-id="leadId"
                    ref="history"
                    style="overflow: scroll; height: 437px; 
                    word-wrap:break-word;"
                    class="w-100"
                />
                <div class="d-flex flex-wrap align-content-end mt-4">
                    <!-- Display:center, justify content center, flex-wrap wrap, align-content -->
                    <label for="comment">Dein Kommentar (min. {{commentMinLength}} Zeichen)</label>

                    <b-form-textarea
                        v-model="newComment.body"
                        name="comment"
                        id="textarea"
                        placeholder="Kommentar"
                        class="rounded"
                    ></b-form-textarea>
                    <small
                        :class="commentClass"
                    >{{newComment.body.length}} / {{commentMinLength}} Zeichen</small>
                </div>
                <b-button
                    class="mt-4 border border-secondary"
                    @click="postComment"
                >Kommentar hinzufügen</b-button>
            </b-col>
            <b-col v-if="data" cols="12" class="mt-4">
                <lead-to-customer :lead="data" />
            </b-col>
        </b-row>
    </b-container>
</template>
<script>
export default {
    props: {
        userId: {
            type: String | Number,
            required: true
        },
        leadId: {
            type: String | Number,
            required: true
        },
        commentMinLength: {
            type: Number,
            default: 5
        }
    },
    data() {
        return {
            edit: false,
            data: null,
            comment: "",
            newComment: {
                reason: null,
                body: ""
            }
        };
    },
    methods: {
        updateData() {
            return axios
                .get(`/api/leads/${this.leadId}`)
                .then(response => {
                    this.data = response.data;
                })
                .catch(() => {});
        },
        startEdit(e) {
            e.preventDefault();
            e.stopPropagation();
            this.edit = !this.edit;
        },
        postComment(e) {
            e.preventDefault();
            e.stopPropagation();
            const commentText = {
                reason: "COMMENT",
                body: this.newComment.body,
                user_id: this.userId
            };
            axios
                .post(`/api/leads/${this.leadId}/comments`, commentText)
                .then(() => {
                    this.newComment.body = "";
                    this.$refs.history.loadData();
                });
        },
        emitComment(e) {
            this.$emit("change-comment", this.newComment);
        },
        leadUpdated() {
            this.updateData();
            this.edit = false;
        }
    },
    mounted() {
        this.updateData();
    },
    computed: {
        commentClass() {
            return this.newComment.body.length < this.commentMinLength
                ? "text-danger"
                : "text-success";
        }
    }
};
</script>
