<template>
  <b-modal :hide-footer="true" size="s" v-model="showModal" id="leadAppointmentInfoModal" :title="'Termin'"
           title-class="modal-title">
    <div class="d-flex flex-column w-100 px-3 pb-3">
      <b-row>
        <b-col class="p-0">
          <div>
            <p class="section-title">{{ leadData.company_name }}</p>
            <a class="section-text text-dark" target="_blank" :href="googleMapsSearchUrl">
              {{ leadData.street }}<br>
              {{ leadData.zip }} {{ leadData.city }}
            </a>
            <p class="section-text"></p>
          </div>
          <div>
            <p class="section-title">Kontakt</p>
            <p class="section-text">{{ leadData.contact_name }}</p>
            <a class="section-text text-dark" :href="phone">{{ leadData.phone1 }}</a>
          </div>
          <div>
            <p class="section-title">Termin Info</p>
            <p class="section-text">{{ appointmentDuration }}</p>
            <p class="section-text">{{ appointment.body }}</p>
          </div>
        </b-col>
      </b-row>
      <b-row>
        <b-col class="p-0 mt-3">
          <b-button
              variant="outline-dark"
              v-if="canUserDeleteAppointment && isAppointmentDeletable" @click="deleteAppointment"
              class="float-right">
            Termin löschen
          </b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col class="p-0 mt-2">
          <b-button
              @click="openLead"
              variant="primary"
              class="float-right">
            Lead öffnen
          </b-button>
        </b-col>
      </b-row>
    </div>
  </b-modal>
</template>
<script>
export default {
  name: "ShowLeadAppointmentInfoComponent",
  props: {
    leadId: {
      type: String | Number,
      required: true
    },
    appointment: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      leadData: {},
      showDelete: true,
      showModal: true
    }
  },
  methods: {
    getLeadInfo() {
      axios.get(`/api/leads/${this.leadId}`).then(response => {
        this.leadData = response.data;
      })
    },
    deleteAppointment() {
      axios.delete(this.route('api.deleteCalendarEvent', this.appointment.id)).then(() => {
        window.location.reload();
      });
    },
    openLead() {
      this.$bvModal.hide('leadAppointmentInfoModal');
      this.$bvModal.show('showLeadFormHistory');
    }
  },
  computed: {
    isAppointmentDeletable() {
      return !this.appointment.isDeleted && new Date(this.appointment.start) < new Date();
    },
    canUserDeleteAppointment() {
      let allowedRoles = [this.$enums.role.CALL_CENTER_SUPERVISOR, this.$enums.role.ADMIN, this.$enums.role.MANAGER];
      return allowedRoles.filter(role => this.hasRole(role)).length > 0;
    },
    appointmentDuration() {
      const dateFormat = {year: 'numeric', month: '2-digit', day: '2-digit'};
      const timeFormat = {hour: '2-digit', minute: '2-digit'};
      let appointmentStartDate = this.appointment.start.toLocaleDateString(undefined, dateFormat);
      let appointmentStartTime = this.appointment.start.toLocaleTimeString(undefined, timeFormat);
      let appointmentEndTime = this.appointment.end.toLocaleTimeString(undefined, timeFormat);
      return `${appointmentStartDate}, ${appointmentStartTime} - ${appointmentEndTime}`;
    },
    phone() {
      return this.getPhoneLink(this.leadData);
    },
    googleMapsSearchUrl() {
      return this.getGoogleSearchUrl(this.leadData);
    }
  },
  mounted() {
    this.getLeadInfo();
  },
  watch: {
    leadId() {
      this.getLeadInfo();
    }
  }
}
</script>

<style scoped>
.section-title {
  font-size: 16px;
  font-weight: 700;
  margin-top: 24px;
  margin-bottom: 8px;
}

.section-text {
  font-size: 16px;
  font-weight: 500;
  margin-bottom: 0;
}

base {
  border-radius: 4px;
  border: solid 1px #000000;
  background-color: white;
}
</style>
