<template>
    <div style="overflow: scroll;">
        <p></p>
        <div v-if="comments && comments.length" class="timeline timeline-inverse">
            <div class="time-label">
                <span class="bg-light">Verlauf</span>
            </div>
            <comment-card :comment="comment" v-for="comment in comments" v-bind:key="comment.id"></comment-card>
        </div>
        <div v-else>
            <p class="text-center">
                <em>Kein Verlauf vorhanden</em>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            comments: null
        };
    },
    props: {
        leadId: {
            type: Number | String,
            required: true
        }
    },
    methods: {
        loadData() {
            return axios
                .get(`/api/leads/${this.leadId}/comments?sort_direction=desc`)
                .then(response => {
                    this.comments = response.data;
                });
        }
    },
    mounted() {
        this.loadData();
    }
};
</script>