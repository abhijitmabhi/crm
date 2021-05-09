<template>
  <form>
    <b-overlay :show="isLoading">
      <div class="mt-4">
        <b-form-group
            label="Betreff"
            label-for="subject">
          <b-form-select
              v-model="message.subject"
              :options="options"
              name="subject"
              id="subject"
          ></b-form-select>
        </b-form-group>
      </div>
      <div v-if="message.subject === 'other'">
        <b-form-group
            label="Eigener Betreff"
            label-for="otherSubject">
          <b-form-input
              v-model="otherSubject"
              type="text"
              name="otherSubject"
              id="otherSubject"
              class="form-control"
          ></b-form-input>
        </b-form-group>
        <span v-if="!!validationErrors.otherSubject">
          <small
              v-for="(error, index) in validationErrors.otherSubject"
              :key="index"
              class="text-danger"
          >{{ error }}
          </small>
      </span>
      </div>
      <div>
        <b-form-group
            label="Nachricht"
            label-for="message">
          <b-form-textarea
              v-model="message.message"
              name="message"
              id="message"
              cols="30"
              rows="10"
              class="form-control"
          ></b-form-textarea>
        </b-form-group>
        <span v-if="!!validationErrors.message">
          <small
              v-for="(error, index) in validationErrors.message"
              :key="index"
              class="text-danger"
          >{{ error }}
          </small>
      </span>
      </div>
      <b-alert
          v-model="showAlert"
          :variant="alertType"
          dismissible
          @dismissed="isSuccess = false"
      >{{ alertMessage }}
      </b-alert>
      <button
          v-if="!isSuccess"
          class="btn btn-primary mt-2"
          @click.prevent="submit"
      >Absenden
      </button>
    </b-overlay>
  </form>
</template>

<script>
export default {
  data() {
    return {
      isSuccess: false,
      showAlert: false,
      alertMessage: "",
      isLoading: false,
      selected: null,
      options: [
        {value: "general", text: "Allgemeine Frage"},
        {value: "technical", text: "Technisches Problem"},
        {value: "other", text: "Ein anderes Thema"}
      ],
      message: {
        subject: "general",
        message: ""
      },
      params: {
        subject: "",
        message: ""
      },
      otherSubject: "",
      otherSubjectMinChars: 1,
      validationErrors: {},
    };
  },
  props: {
    companyId: {
      type: String | Number,
      required: true
    },
  },
  computed: {
    alertType() {
      return this.isSuccess ? 'success' : 'danger';
    }
  },
  methods: {
    submit() {
      if (this.validateInput()) {
        if (this.message.subject === "other") {
          this.params.subject = this.otherSubject;
        } else {
          this.params.subject = this.message.subject;
        }
        this.params.message = this.message.message;

        this.isLoading = true;
        this.showAlert = false;
        this.isSuccess = false;
        this.alertMessage = "";

        return axios
            .post(`/support-request`, this.params)
            .then(() => {
              this.isSuccess = true;
              this.alertMessage = "Anfrage erfolgreich gesendet."
            })
            .catch(() => {
              this.isSuccess = false;
              this.alertMessage = "Anfrage konnte nicht verschickt werden. Versuchen sie es später nochmal."
            })
            .finally(() => {
              this.isLoading = false;
              this.showAlert = true;
              this.validationErrors = {};
            });
      }
    },
    validateInput() {
      let errors = false;
      if (
          this.message.subject === "other" &&
          this.otherSubject.length < this.otherSubjectMinChars
      ) {
        this.$set(this.validationErrors, "otherSubject", [
          "Bitte einen Grund angeben."
        ]);
        errors = true;
      }
      if (!this.message.message) {
        this.$set(this.validationErrors, "message", [
          "Bitte eine Nachricht angeben."
        ]);
        errors = true;
      }
      return !errors;
    },
  }
};
</script>