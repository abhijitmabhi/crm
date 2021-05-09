<template>
    <div v-if="showSelf" class="w-100 card">
        <div class="table-responsive">
            <table class="table table-borderless table-striped table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Datum / Uhrzeit</th>
                        <th>Unternehmen</th>
                        <th>Ort</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="appointment in appointments.data" :key="appointment.id">
                        <td>{{date(appointment.closed_until)}}</td>
                        <td>{{appointment.company_name}}</td>
                        <td>{{appointment.city}}</td>
                        <td class="d-flex align-items-center justify-content-start">
                            <button
                                class="btn btn-white fs-22"
                                :data-test="appointment.id"
                                @click="selectInspectAppointment"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <pagination
                :data="appointments"
                @pagination-change-page="updateAppointments"
                :limit="2"
            ></pagination>
        </div>
        <modal :show-modal="showAppointment" @update:showModal="toggleModal">
            <expert-appointment-info :appointment="inspectAppointment"></expert-appointment-info>
            <div slot="footer">
                <button @click="toggleModal" class="btn btn-white">Schließen</button>
            </div>
        </modal>
    </div>
</template>

<script>
import expertAppointmentInfo from "./show-appointment.vue";
export default {
    components: {
        expertAppointmentInfo
    },
    props: {
        expertId: Number,
        pagination: String,
        status: String,
        expertState: String
    },
    data() {
        return {
            appointments: false,
            showAppointment: false,
            inspectAppointmentId: false
        };
    },
    computed: {
        inspectAppointment() {
            if (this.inspectAppointmentId) {
                let app = this.appointments.data.filter(
                    appo => appo.id == this.inspectAppointmentId
                )[0];
                if (app) return app;
            }
            return {};
        },
        showSelf() {
            return this.appointments && this.appointments.data.length > 0;
        },
        expert_state() {
            return this.defaultVal(this.expertState, 5);
        },
        state() {
            return this.defaultVal(this.status, 5);
        },
        pagination_nr() {
            return this.defaultVal(this.pagination, 5);
        },
        page_nr() {
            if (this.page) {
                return this.page;
            }
            return 1;
        }
    },
    methods: {
        defaultVal(obj, defaultVal) {
            if (obj || obj === 0) {
                return obj;
            }
            return defaultVal;
        },
        date(date) {
            return this.$moment(date).format("DD.MM.YY / HH:mm");
        },
        updateAppointments(page = 1) {
            let url = `/api/users/${this.expertId}/leads`;
            let args = [
                "page=" + page,
                "per_page=" + this.pagination_nr,
                "filter=" + 5,
                "expert_status=" + 1,
                "sort_by=closed_until",
                "show_past=false"
            ];
            axios
                .get(url + "?" + args.join("&"))
                .then(response => (this.appointments = response.data));
        },
        selectInspectAppointment(e) {
            let button = e.target;
            if (button.tagName === "I") {
                button = button.parentNode;
            }
            this.inspectAppointmentId = button.dataset.test;
            this.showAppointment = true;
        },
        toggleModal() {
            this.showAppointment = !this.showAppointment;
        }
    },
    mounted() {
        this.updateAppointments(this.page_nr);
    }
};
</script>
