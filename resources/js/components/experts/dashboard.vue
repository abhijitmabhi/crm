<template>
    <div class="row">
        <div class="d-flex flex-column align-items-center col col-md-6">
            <expert-appointment-info
                v-if="nextAppointment"
                :appointment="nextAppointment"
                class="p-0 w-100"
            >
                <h5 slot="header" class="card-title">Nächster Termin:</h5>
            </expert-appointment-info>
            <expert-appointments :expert-id="expertId" />
        </div>
    </div>
</template>

<script>
import expertAppointmentInfo from "./show-appointment.vue";
import expertAppointments from "./appointments.vue";
export default {
    components: {
        expertAppointmentInfo,
        expertAppointments
    },
    data() {
        return {
            user: null,
            nextAppointment: false
        };
    },
    props: {
        expertId: Number
    },
    methods: {
        updateUser() {
            return axios
                .get(`/api/users/${this.expertId}`)
                .then(response => (this.user = response.data));
        },
        updateNextAppointment() {
            const params = {
                per_page: 1,
                sort_by: "closed_until",
                filter: 5,
                expert_status: 1,
                show_past: false
            };
            return axios
                .get(`/api/users/${this.expertId}/leads`, { params })
                .then(response => {
                    if (response.data.data.length > 0) {
                        this.nextAppointment = response.data.data[0];
                    }
                });
        }
    },
    mounted() {
        this.updateUser().then(this.updateNextAppointment);
    }
};
</script>
