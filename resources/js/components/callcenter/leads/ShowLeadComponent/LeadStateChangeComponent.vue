<template>
  <div>
    <b-input-group class="lead-form-appointment-date">
      <b-form-group
          label="Beginn des Termin"
          :label-for="`start_date_${lead.id}`"
          class="flex-grow-1 form-row-gap"
          :invalid-feedback="startDateInvalidText"
          :state="startDateValidationState"
      >
        <flat-pickr
            v-model="params.startDate"
            :config="datePickerConfig"
            name="start_date"
            :id="`start_date_${lead.id}`"
            placeholder="Datum für Termin / Wiedervorlage"
            class="form-control"
            ref="startDatePicker"
        />
      </b-form-group>
      <b-form-group
          label="Ende des Termin"
          :label-for="`end_date_${lead.id}`"
          class="flex-grow-1 form-row-gap"
          :invalid-feedback="endDateInvalidText"
          :state="endDateValidationState"
      >
        <flat-pickr
            v-model="params.endDate"
            :config="datePickerConfig"
            name="end_date"
            :id="`end_date_${lead.id}`"
            placeholder="Ende des Termins"
            class="form-control"
            ref="endDatePicker"
        />
      </b-form-group>
    </b-input-group>

    <b-form-group
        label-for="comment"
        :label="`Kommentar (min. ${commentMinLength} Zeichen)`"
        invalid-feedback="mindestens 5 Zeichen"
        :state="commentValidationState"
    >
      <b-form-textarea
          name="comment"
          ref="comment"
          v-model="params.comment"
          style="min-height: 140px;"
          class="w-100 mb-1"
          placeholder="Kommentar eingeben"
          :state="commentValidationState"
      />
    </b-form-group>

    <b-button-group :vertical="830 >= windowWidth" class="w-100" style="min-height: 80px">
      <b-button
          v-if="isNotCustomer"
          variant="secondary"
          @click="onClickAppointment"
          class="border border-secondary text-success callcenter-button"
          :disabled="sending"
      >{{this.appointmentButtonText}}
      </b-button>

      <b-button
          v-if="leadStateAppointment"
          variant="secondary"
          @click="onClickSimpleLeadStateChange(leadStates.APPOINTMENT_NEEDED)"
          class="border border-secondary text-success callcenter-button"
          :disabled="sending"
      >Termin nötig
      </b-button>

      <b-button
          variant="secondary"
          @click="onClickComment"
          class="border border-secondary text-info callcenter-button"
          :disabled="sending"
      >
        Kommentar Speichern
      </b-button>

      <b-button
          v-if="isNotCustomer"
          variant="secondary"
          @click="onClickNotReached"
          class="border border-secondary text-secondary callcenter-button"
          :disabled="sending"
      >Nicht erreicht
      </b-button>

      <b-button
          v-if="isNotCustomer"
          variant="secondary"
          @click="onClickRecall"
          class="border border-secondary text-danger callcenter-button"
          :disabled="sending"
      >
        Wiedervorlage
      </b-button>

      <b-button
          v-if="isNotCustomer"
          variant="secondary"
          @click="onClickSimpleLeadStateChange(leadStates.NO_INTEREST)"
          class="border border-secondary text-primary callcenter-button"
          :disabled="sending"
      >Kein Interesse
      </b-button>

      <b-button
          v-if="showBlacklistButton"
          variant="secondary"
          @click="onClickSimpleLeadStateChange(leadStates.BLACKLIST)"
          class="border border-secondary text-dark callcenter-button"
          :disabled="sending"
      >Blacklist
      </b-button>

      <b-button
          v-if="showCompetitionProtectionButton"
          variant="secondary"
          @click="onClickSimpleLeadStateChange(leadStates.COMPETITION_PROTECTION)"
          class="border border-secondary text-dark callcenter-button"
          :disabled="sending"
      >Konkurrenzschutz
      </b-button>

      <b-button
          v-if="isFollowUpAgent"
          variant="secondary"
          @click="onClickSkip()"
          class="border border-secondary text-dark callcenter-button"
          :disabled="sending"
      >Überspringen
      </b-button>
    </b-button-group>

    <b-alert
        class="mt-3"
        variant="danger"
        :show="requestErrors.length > 0"
    >
      <p class="mb-0" v-for="error in requestErrors" :key="error">{{ error }}</p>
    </b-alert>
  </div>
</template>
<script>
export default {
  props: {
    lead: {
      type: Object,
      required: true,
    },
    lastAppointment: {
      type: Object,
      required: false,
    },
  },
  data: function () {
    return {
      windowWidth: window.innerWidth,
      leadStates: this.$enums.leadState,
      commentMinLength: 5,
      params: {
        comment: "",
        state: -1,
        startDate: null,
        endDate: null,
      },
      sending: false,
      requestErrors: [],
      datePickerConfig: {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
      },
      commentValidationState: null,
      startDateValidationState: null,
      startDateInvalidText: "",
      endDateValidationState: null,
      endDateInvalidText: "",
      isFollowUpAgent: this.hasRole(this.$enums.role.FOLLOW_UP_AGENT),
    }
  },
  computed: {
    appointmentButtonText() {
      return this.hasAppointmentInFuture ? 'Termin verschieben' : 'Termin erstellen';
    },
    hasAppointmentInFuture() {
      return this.leadStateAppointment
          && this.lastAppointment
          && this.$moment(this.lastAppointment.event_begin).isAfter(this.$moment())
    },
    isNotCustomer() {
      return this.lead.status != this.$enums.leadState.CLOSED;
    },
    leadStateAppointment() {
      return this.lead.status == this.$enums.leadState.APPOINTMENT;
    },
    showBlacklistButton() {
      return this.isNotCustomer && this.lead.status != this.$enums.leadState.BLACKLIST;
    },
    showCompetitionProtectionButton() {
      return this.showBlacklistButton && !this.isFollowUpAgent
          && this.lead.status != this.$enums.leadState.COMPETITION_PROTECTION;
    },
  },
  methods: {
    updateWindowWidth() {
      this.windowWidth = window.innerWidth;
    },
    isCommentValid() {
      if (this.params.comment.length >= this.commentMinLength) {
        this.commentValidationState = true;
        return true;
      } else {
        this.commentValidationState = false;
        this.focusComment();
        return false;
      }
    },
    isStartDateValid() {
      if (!this.params.startDate) {
        this.focusStartDatePicker();
        this.startDateValidationState = false;
        this.startDateInvalidText = "Bitte ein Datum angeben!";
        return false;
      } else if (this.$moment(this.params.startDate).isBefore(this.$moment())) {
        this.focusStartDatePicker();
        this.startDateValidationState = false;
        this.startDateInvalidText = "Datum liegt in der Vergangenheit!";
        return false;
      }
      return true;
    },
    isEndDateValid() {
      if (!this.params.endDate) {
        this.focusEndDatePicker();
        this.endDateValidationState = false;
        this.endDateInvalidText = "Bitte ein Datum angeben!";
        return false;
      } else if (this.$moment(this.params.endDate).isBefore(this.$moment(this.params.startDate))) {
        this.focusEndDatePicker();
        this.endDateValidationState = false;
        this.endDateInvalidText = "Datum liegt vor dem Start Datum!";
        return false;
      }
      return true;
    },
    focusStartDatePicker() {
      this.$refs.startDatePicker.$el.focus();
    },
    focusEndDatePicker() {
      this.$refs.endDatePicker.$el.focus();
    },
    focusComment() {
      this.$refs.comment.$el.focus();
    },
    resetValidationStates() {
      this.commentValidationState = null;
      this.startDateValidationState = null;
      this.endDateValidationState = null;
    },
    validateAppointmentData() {
      this.resetValidationStates();
      return this.isStartDateValid() && this.isEndDateValid() && this.isCommentValid();
    },
    validateRecallData() {
      this.resetValidationStates();
      return this.isStartDateValid() && this.isCommentValid();
    },

    onClickAppointment() {
      if (this.validateAppointmentData()) {
        this.params.state = this.$enums.leadState.APPOINTMENT;
        this.sendLeadStateChangeRequest();
      }
    },

    onClickRecall() {
      if (this.validateRecallData()) {
        this.params.state = this.$enums.leadState.RECALL;
        this.sendLeadStateChangeRequest();
      }
    },

    onClickSimpleLeadStateChange(state) {
      if (this.isCommentValid()) {
        this.params.state = state;
        this.sendLeadStateChangeRequest();
      }
    },

    onClickNotReached() {
      this.params.state = this.$enums.leadState.NOT_REACHED;
      this.params.comment = 'Nicht erreicht.'
      this.sendLeadStateChangeRequest();
    },

    // async confirmLeadStateChange() {
    //   if (confirm("Sind Sie sicher?")) {
    //     await this.sendLeadStateChangeRequest();
    //   }
    // },
    sendLeadStateChangeRequest() {
      this.sending = true;
      return axios.put(this.route('api.lead.state.change', this.lead.id), this.params)
          .then(() => this.$emit("lead-state-changed"))
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
          });
    },

    onClickComment() {
      if (this.isCommentValid()) {
        this.sendCommentRequest();
      }
    },
    sendCommentRequest() {
      const requestData = new FormData();

      requestData.append("lead_id", this.lead.id);
      requestData.append("date", this.params.startDate);
      requestData.append("body", this.params.comment);
      requestData.append("reason", this.$enums.commentReason.COMMENT);

      this.sending = true;
      return axios.post(this.route('api.leads.comments.store', this.lead.id), requestData)
          .then(() => this.$emit("lead-state-changed"))
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
          });
    },
    onClickSkip() {
      this.sending = true;
      return axios.put(this.route('api.lead.state.followup.skip', this.lead.id))
          .then(() => this.$emit("lead-state-changed"))
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
          });
    }
  },
  mounted: function () {
    window.addEventListener("resize", this.updateWindowWidth);
    // this.isFollowUpAgent = this.hasRole(this.$enums.role.FOLLOW_UP_AGENT);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.updateWindowWidth);
  },
}
</script>

<style lang="scss" scoped>
.callcenter-button {
  min-height: 60px;
}

@media (min-width: 835px) {
  .callcenter-button {
    min-height: auto;
  }
}

@media (min-width: 768px) {
  .form-row-gap {
    padding-left: 5px;
    padding-right: 5px;

    &:first-child {
      padding-left: 0;
    }

    &:last-child {
      padding-right: 0;
    }
  }
}
</style>