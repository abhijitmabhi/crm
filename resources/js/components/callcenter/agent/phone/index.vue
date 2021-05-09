<template>
    <div class="container-fluid">
        <div class="row">
            <div id="leadCol" class="col">
                <div class="d-flex justify-content-between h3 text-dark mb-2">
                    <div>SAM: {{ expertName }}</div>
                    <div>{{ appointments }} Termine</div>
                    <div>{{ pipeline }} in Pipeline</div>
                </div>
                <b-card
                    header="AKTUELLER LEAD"
                    header-class="h4 simple-card-header"
                    no-body
                >
                    <lead-form-history
                        :expert-id="expertId"
                        :lead-id="leadId"
                        :timer="timer"
                        recall
                        :appointment-id="appointmentId"
                    />
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    components: {
        history,
    },
    provide: function () {
      return {
        userId: this.userId,
      };
    },
    data() {
        return {
            expert: {},
            leads: {},
            createForm: true,
        };
    },
    props: {
        agentId: String | Number,
        expertId: String | Number,
        expertAvatar: String,
        expertName: String,
        expertAppointments: String | Number,
        expertLeads: String | Number,
        leadId: String | Number,
        timer: Object | JSON,
        appointmentId: Number,
        userId: Number,
    },
    computed: {
        avatar() {
            if (this.expertAvatar) {
                return this.expertAvatar;
            }
            return "/storage/avatars/default.png";
        },
        appointments() {
            if (this.expertAppointments) {
                return this.expertAppointments;
            }
            return 0;
        },
        pipeline() {
            if (this.expertLeads) {
                return this.expertLeads;
            }
            return 0;
        },
    },
    methods: {
        getExpert() {
            return axios
                .get(`/api/users/${this.expertId}`)
                .then(response => (this.expert = response.data));
        },
    },
};
</script>

<style></style>
