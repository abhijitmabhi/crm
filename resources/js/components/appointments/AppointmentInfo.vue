<template>
    <b-row class="mt-3">
        <b-col cols="6">
            <strong>Beginn:</strong>
            {{ startDate }}
        </b-col>
        <b-col cols="6">
            <strong>Ende:</strong>
            {{ endDate }}
        </b-col>
        <b-col cols="6" class="mt-3">
            <p>
                <strong>Termin Beschreibung</strong>
            </p>
            <p>{{ event.body }}</p>
            <div v-if="owner">
                <hr />
                <div class="d-flex">
                    <p>
                        Ansprechpartner: {{ owner.name }} | Kontakt:
                        <a
                            :href="emailHref"
                        >{{ owner.email }}</a>
                    </p>
                </div>
            </div>
        </b-col>
        <b-col cols="6" class="mt-3">
            <b-row>
                <b-col cols="6">
                    <p class="font-weight-bold mb-2">Eingeladen</p>
                    <ul class="list-unstyled">
                        <li v-for="invitee in event.invitees" :key="invitee.id">{{ invitee.name }}</li>
                    </ul>
                </b-col>
                <b-col cols="6">
                    <p class="font-weight-bold mb-2">Teilnehmer</p>
                    <ul class="list-unstyled">
                        <li
                            v-for="attendee in event.attendees"
                            :key="attendee.id"
                        >{{ attendee.name }}</li>
                    </ul>
                </b-col>
            </b-row>
        </b-col>
        <div v-if="isOwner">
            <b-button
                v-if="event.isDeleted"
                class="btn-danger"
                @click="restoreAppointment"
            >Termin Wiederherstellen</b-button>
            <b-button v-else class="btn-danger" @click="deleteAppointment">Termin löschen</b-button>
        </div>
    </b-row>
</template>
<script>
export default {
    inject: ["userId", "userRole"],
    props: {
        event: {
            type: Object,
            required: true,
        },
        owner: {
            type: Object,
            required: false,
        },
    },
    computed: {
        startDate() {
            return this.$moment(this.event.start).format("D MMMM YYYY, H:mm");
        },
        endDate() {
            return this.$moment(this.event.end).format("D MMMM YYYY, H:mm");
        },
        emailHref() {
            return `mailto:${this.owner.email}`;
        },
        isOwner() {
            return (
                (this.owner && this.userId == this.owner.id) ||
                this.isAdmin() ||
                this.isSupervisor()
            );
        },
    },
    methods: {
        async deleteAppointment() {
            const confirmed = await this.$bvModal.msgBoxConfirm(
                "Wollen Sie den Termin wirklich löschen?",
                {
                    title: "Termin wirklich löschen?",
                    size: "sm",
                    buttonSize: "sm",
                    okVariant: "danger",
                    okTitle: "Ja",
                    cancelTitle: "Nein",
                    footerClass: "p-2",
                    hideHeaderClose: true,
                }
            );

            if (confirmed) {
                return axios
                    .delete(`/api/users/${this.userId}/appointments/${this.event.id}`)
                    .then(() => window.location.reload());
            }
        },
        async restoreAppointment() {
            const confirmed = await this.$bvModal.msgBoxConfirm(
                "Wollen Sie den Termin wirklich wieder herstellen?",
                {
                    title: "Termin wirklich wieder herstellen?",
                    size: "sm",
                    buttonSize: "sm",
                    okVariant: "danger",
                    okTitle: "Ja",
                    cancelTitle: "Nein",
                    footerClass: "p-2",
                    hideHeaderClose: true,
                }
            );

            if (confirmed) {
                await axios
                    .post(`/api/users/${this.userId}/appointments/${this.event.id}/restore`)
                    .then(() => window.location.reload())
                    .catch(() => []);
            }
        },
        isSupervisor() {
            return this.userRole[0].id === 5;
        },
        isAdmin() {
            return this.userRole[0].id === 1;
        },
    },
};
</script>
<style scoped>
button {
    margin: 0 15px;
}
</style>
