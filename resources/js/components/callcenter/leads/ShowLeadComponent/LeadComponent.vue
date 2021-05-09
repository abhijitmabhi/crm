<template>
  <div class="d-flex justify-content-between">
    <div v-if="loaded" class="d-flex flex-column w-100">
      <b-row>
        <b-col>
          <div v-if="editCompanyName" class="d-flex align-items-center">
            <b-input v-model="lead.company_name"/>
            <a
                href="#"
                title="Bearbeitung beenden"
                @click.prevent="editCompanyName = false"
                class="ml-3 h3 text-success"
            >
              <i class="fas fa-check"></i>
            </a>
          </div>
          <div v-else class="d-flex align-items-center">
            <a class="h3" target="_blank" :href="googleMapsSearchUrl">
              {{ lead.company_name }}
              <i
                  class="fas fa-external-link-square-alt"
                  style="font-size: 1rem"
              ></i>
            </a>
            <a
                href="#"
                class="ml-3 h5 text-danger"
                @click.prevent="editCompanyName = true"
            >
              <i class="fas fa-pen"></i>
            </a>
          </div>
          <p>{{ lead.zip }} {{ lead.street }} {{ lead.city }}</p>
        </b-col>
        <b-col v-if="!timer" class="d-flex justify-content-center">
          <div>
            <p v-if="expert">
              <span class="font-weight-bold">SAM:</span>
              {{ expert.name }}
            </p>
            <p v-if="isRecall() && agent" :class="leadStatusClass">
              Wiedervorlage für
              {{ agent.name }}
            </p>
            <p v-else :class="appointmentDate ? 'mb-0' : ''">
              <span class="font-weight-bold">Status:</span>
              <span :class="leadStatusClass">{{ leadStatus }}</span>
              <span
                  v-if="lead.status == this.$enums.leadState.APPOINTMENT"
                  :class="appointmentDate ? 'text-right' : 'text-center'"
              >
                <b-spinner v-if="!appointmentDate" small/>
                <span class="ml-1" v-else>( {{ appointmentDate }} )</span>
              </span>
            </p>
          </div>
        </b-col>
        <b-col v-else-if="lead.status == this.$enums.leadState.RECALL">
          <p class="h4 text-danger text-center">Wiedervorlage</p>
        </b-col>
        <b-col v-else-if="lead.status == this.$enums.leadState.COMPETITION_PROTECTION">
          <p class="h4 text-danger text-center">Konkurrenzschutz</p>
        </b-col>
        <b-col class="d-flex justify-content-end">
          <p class="h3 text-white">
            <a :href="phoneLink" @click="onClickPhone">
              <span class="fa-stack">
                  <i class="fas fa-circle fa-stack-2x text-success"></i>
                  <i class="fas fa-phone fa-stack-1x fa-inverse"></i>
              </span>
            </a>
          </p>
        </b-col>
      </b-row>
      <lead-form-view
          :lead="lead"
          :timer="timer"
          @change="updateLead"
          ref="leadForm"
      ></lead-form-view>
      <lead-state-change
          :lead="lead"
          :last-appointment="lastAppointment"
          @lead-state-changed="onLeadStateChanged"
      >
      </lead-state-change>
      <div v-if="sending" class="d-flex justify-content-center mb-3">
        <b-spinner/>
      </div>

      <div v-if="canConvertToCompany" style="margin-top: 20px; padding-bottom: 50px">
        <lead-to-customer :lead="lead" @lead-converted="reload"/>
      </div>
    </div>
    <div v-else class="d-flex justify-content-center flex-grow-1">
      <b-spinner></b-spinner>
    </div>
  </div>
</template>

<script>
import {cloneDeep} from "lodash";
import LeadFormView from "./lead";
import LeadStateChangeComponent from "./LeadStateChangeComponent";

export default {
  components: {LeadFormView, LeadStateChangeComponent},
  data: function () {
    return {
      appointmentDate: null,
      commentMinLength: 5,
      editCompanyName: false,
      warningLevel: {
        level: 1,
      },
      lead: null,
      loaded: false,
      orginal: null,
      sending: false,
      expert: null,
      agent: null,
      lastAppointment: null,
      newComment: {
        reason: null,
        body: "",
      },
    };
  },
  props: {
    appointmentId: {
      type: Number,
      default: -1,
    },
    leadId: {
      type: Number | String,
      required: true,
    },
    timer: Boolean,
    showStorno: Boolean,
    inModal: {
      type: Boolean,
      default: false,
    },
    showRecall: {
      type: Boolean,
      default: false,
    },

  },
  watch: {
    leadId(newVal, oldVal) {
      this.loadData();
    },
  },
  computed: {
    canConvertToCompany() {
      return (
          !!this.lead &&
          this.lead.status != this.$enums.leadState.CLOSED &&
          this.lead.status != this.$enums.leadState.BLACKLIST &&
          this.lead.states != this.$enums.leadState.INVALID
      );
    },
    updateUrl() {
      return `/api/leads/${this.leadId}/comments`;
    },
    phone() {
      return this.lead.phone1.toString().replace(/\s/g, "");
    },
    phoneLink() {
      return this.getPhoneLink(this.lead);
    },
    makeAppointmentText() {
      if (this.lead && this.lead.status == this.$enums.leadState.APPOINTMENT) {
        return "Termin verschieben";
      }
      return "Termin";
    },
    leadStatus() {
      switch (parseInt(this.lead.status)) {
        case 1:
          return "Offen";
        case 2:
          return "Nicht erreicht";
        case 3:
          return "Wiedervorlage";
        case 4:
          return "Kein Interesse";
        case 5:
          return "Termin vereinbart";
        case 6:
          return "Blacklist";
        case 10:
          return "Termin nötig";
        case 11:
          return "Konkurrenzschutz";
        default:
          return null;
      }
    },
    leadStatusClass() {
      switch (parseInt(this.lead.status)) {
        case 2:
          return " text-warning";
        case 3:
        case 4:
        case 6:
          return " text-danger";
        case 5:
          return " text-success";
        default:
          return "";
      }
    },
    googleMapsSearchUrl() {
      return this.getGoogleSearchUrl(this.lead);
    },
  },
  methods: {
    updateLead(key, value) {
      this.lead[key] = value;
    },
    reload() {
      location.reload();
    },
    onClickPhone(e) {
      if ("icare_username" in this.getUserOptions()) {
        e.preventDefault();
        e.stopPropagation();
        let username = this.getUserOptions().icare_username;
        let apiAction = 'MANUELLER_ANRUF_RUFNUMMER';
        let apiKey = 'c5cdb71c237a3adebcd34c8ec17f7158';
        let phone = this.phone.replace('+49', '0');
        let requestUrl = `https://icare-gabcall-web3-telekom.easyfone.de/gabcall/api.php?aktion=${apiAction}&user=localhero&parameter1=${username}&parameter2=${phone}&key=${apiKey}`;
        axios.post(requestUrl);
      }
    },

    correction() {
      //TODO: refactor
      this.sending = true;
      this.setReason("CORRECTION");
      this.commit()
          .then(this.loadData)
          .then(() => this.emitComment("correction"))
          .then(() => {
            if (this.inModal) {
              this.reload();
            } else {
              this.sending = false;
            }
          });
    },

    isRecall() {
      return this.lead.status == this.$enums.leadState.RECALL;
    },
    /**
     * Helpers
     */

    onLeadStateChanged() {
      this.trackTime();
      //TODO: close modal?
      this.reload();
    },

    trackTime() {
      if (this.timer) {
        return axios.post(`/api/leads/${this.leadId}/intervals`, {
          time_spent: this.$refs.leadForm.interval,
        });
      }
    },
    setReason(reason) {
      this.lead.reason = this.newComment.reason = reason;
    },
    commit() {
      const fd = new FormData();
      const {reason, body} = this.newComment;
      const {closed_until, endDate} = this.lead;
      fd.append("lead_id", this.leadId);
      fd.append("date", closed_until);
      fd.append("endDate", endDate);
      fd.append("body", body || reason);
      fd.append("reason", reason);

      const comment = {};
      fd.forEach(function (value, key) {
        comment[key] = value;
      });

      const data = {};
      Object.keys(this.lead).map((key) => {
        if (this.lead[key] != this.orginal[key]) {
          data[key] = this.lead[key];
        }
      });

      this.params = {...this.params, ...data};
      this.params.comment = comment;

      return axios.put(`/api/lead/${this.leadId}/state/`, this.params);
    },

    loadData() {
      this.newComment.body = "";
      return axios.get(`/api/leads/${this.leadId}`).then((response) => {
        this.lead = response.data;
        this.orginal = cloneDeep(response.data);
        this.loaded = true;
        this.lead.closed_until = null;
        this.$emit("leadSaved");
      });
    },
    emitComment(eventName) {
      this.newComment.lead_id = this.leadId;
      this.$emit(eventName, this.newComment);
    },

    fetchExpert() {
      return axios.get("/api/users/" + this.lead.expert_id).then((response) => {
        this.expert = response.data;
      });
    },

    fetchAgent() {
      if (this.lead.agent_id) {
        return axios.get("/api/users/" + this.lead.agent_id).then((response) => {
          this.agent = response.data;
        });
      } else {
        return Promise.resolve();
      }
    },

    fetchAppointment() {
      return axios.get(`/api/leads/${this.lead.id}/lastAppointment`)
          .then((response) => {
            if (response.data) {
              this.lastAppointment = response.data;
              this.lastAppointment.options = [];
              this.appointmentDate =
                  this.$moment(this.lastAppointment.event_begin).format("DD.MM.YY HH:mm") +
                  " Uhr";
            }
          });
      // if (this.appointmentId != null && this.appointmentId >= 0) {
      //   return axios.get(`/api/calendar/event/${this.appointmentId}`).then((response) => {
      //     this.event = response.data;
      //     this.event.options = [];
      //     this.appointmentDate =
      //         this.$moment(this.event.event_begin).format("DD.MM.YY HH:mm") +
      //         " Uhr";
      //   });
      // }
    }
  },
  mounted: function () {
    this.loadData().then(() => {
      this.fetchExpert();
      this.fetchAgent();
      this.fetchAppointment();
    });
  },
};
</script>

<style scoped>

.competition-button {
  position: absolute;
  bottom: 50px;
  left: 300px;
  min-width: fit-content;
  min-height: 87px;
  max-width: 252px;
}

@media (max-width: 1199px) {
  .competition-button {
    left: 225px;
  }
}

@media (max-width: 991px) {
  .competition-button {
    left: 180px;
  }
}

@media (max-width: 637px) {
  .competition-button {
    left: 140px;
  }
}
</style>
