<template>
  <modal :showModal="showModal" type="123" class="appointment-modal" hide-close>
    <template v-slot:modalTitle>Ein neuer Termin wurde für Sie vereinbart:</template>
    <div>
      <table class="table table-borderless">
        <tr>
          <td>Unternehmen</td>
          <td>
            <strong>{{ appointment.title }}</strong>
          </td>
        </tr>
        <tr>
          <td>Ort</td>
          <td>
            <strong>{{ appointment.city }}</strong>
          </td>
        </tr>
        <tr>
          <td>Datum</td>
          <td>
            <strong>{{ appointmentStart }} Uhr</strong>
          </td>
        </tr>
      </table>

      <textarea
          v-model="rejectionMessage"
          class="form-control mt-4"
          id="reject-reason"
          name="ablehnung"
          placeholder="Bei Ablehnung bitte Grund eintragen"
      ></textarea>
    </div>
    <h4 class="mt-4">Ihre Termine in der Woche</h4>
    <div style="overflow: scroll; max-height: 20vh">
      <FullCalendar
          v-if="appointment.start"
          defaultView="dayGridWeek"
          locale="de"
          :defaultDate="defaultDate"
          :header="false"
          :events="acceptedAppointments"
          :plugins="calendarPlugins"
          :firstDay="1"
      ></FullCalendar>
    </div>
    <template v-slot:footer>
      <button class="btn btn-success" @click="acceptAppointment">Akzeptieren</button>
      <button class="btn btn-danger" @click="rejectAppointment">Ablehnen</button>
    </template>
  </modal>
</template>
<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";

export default {
  components: {
    FullCalendar,
  },
  data() {
    return {
      calendarHeader: {left: "", right: ""},
      calendarPlugins: [dayGridPlugin],
      appointments: [],
      acceptedAppointments: [],
      rejectionMessage: "",
    };
  },
  props: {
    expertId: Number,
  },
  computed: {
    showModal() {
      return 0 < this.appointments.length;
    },
    appointment() {
      if (this.appointments[0]) {
        let appointment = this.appointments[0];
        appointment.backgroundColor = "rgb(50, 187, 96)";
        this.acceptedAppointments.push(appointment);
        return appointment;
      } else {
        return {title: "", start: "", city: ""};
      }
    },
    defaultDate() {
      if (!this.appointment.start) return 0;
      return parseInt(this.$moment(this.appointment.start).format("x"));
    },
    appointmentStart() {
      return this.$moment(this.appointment.start).format("dddd DD. MMMM YYYY, HH:mm");
    },
  },
  mounted() {
    this.getAppointments();
  },
  methods: {
    getAppointments() {
      axios
          .get("/api/appointments/" + this.expertId)
          .then(response => {
            this.appointments = response.data.filter(a => a.acceptance === 0);
            this.acceptedAppointments = response.data.filter(a => a.acceptance === 1);
          })
          .catch(error => console.error(error));
    },
    acceptAppointment() {
      this.updateAppointment(1);
    },
    rejectAppointment() {
      if (this.rejectionMessage.length > 10) {
        this.updateAppointment(2);
      } else {
        alert("Bitte Grund der Ablehnung angeben!");
        document.getElementById("reject-reason").focus();
      }
    },
    updateAppointment(status) {
      let oldAppointment = this.appointment;
      axios
          .put(this.route('api.leads.updateExpertStatus', oldAppointment.leadId), {
            expert_status: status,
            reasoning: this.rejectionMessage,
          })
          .catch(error => console.error(error))
          .then(() => {
            this.rejectionMessage = "";
            if (this.appointments.length < 1) location.reload();
          });
      this.acceptedAppointments.forEach(a => {
        if (a.backgroundColor) {
          delete a.backgroundColor;
        }
      });
      this.appointments = this.appointments.filter(
          appointment => oldAppointment !== appointment
      );
    },
  },
};
</script>
