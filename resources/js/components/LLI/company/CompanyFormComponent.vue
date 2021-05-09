<template>
  <form id="companyForm" v-on:submit.prevent="submit">
    <b-overlay :show="isLoading">
      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Name">
            <b-form-input
                v-model="company.name"
                name="name"
                id="name"
                placeholder="Name"
                required>
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Email">
            <b-form-input
                v-model="company.email"
                type="email"
                name="email"
                id="email"
                placeholder="Email">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Telefon">
            <b-form-input
                v-model="company.phone"
                type="tel"
                name="phone"
                id="phone"
                placeholder="Telefon">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="URL">
            <b-form-input
                v-model="website"
                type="text"
                name="url"
                id="url"
                placeholder="URL"
                :state="this.getWebsiteValidState(website)">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Straße">
            <b-form-input
                v-model="company.street"
                id="street"
                name="street"
                placeholder="Straße">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="PLZ">
            <b-form-input
                v-model="company.zip"
                id="zip"
                name="zip"
                placeholder="PLZ">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Stadt">
            <b-form-input
                v-model="company.city"
                id="city"
                name="city"
                placeholder="Stadt">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <div class="my-4" v-if="showSuccess || showFailure">
        <b-alert :show="showSuccess" variant="success" dismissible @dismissed="showSuccess = 0">
          Erfolgreich gespeichert.
        </b-alert>
        <b-alert :show="showFailure" variant="danger" dismissible @dismissed="showFailure = 0">
          <p v-for="error in this.requestErrors">{{ error }}</p>
        </b-alert>
      </div>
      <input type="submit" value="Speichern" class="btn btn-primary mt-4">
    </b-overlay>
  </form>
</template>
<script>

export default {
  props: {
    company: {
      type: Object,
      default: () => {
        return {
          name: "",
          url: "",
          email: "",
          phone: "",
          street: "",
          zip: "",
          city: "",
          logo: ""
        };
      },
    },
    method: {
      type: String,
      default: "post"
    },
    shouldRedirect: {
      type: Boolean,
      default: false
    },
    submitUrl: {
      type: String,
      default: ""
    },
    redirectUrl: {
      type: String,
      default: ""
    },


  },
  data() {
    return {
      showSuccess: 0,
      showFailure: 0,
      requestErrors: [],
      isLoading: false,
      website: ""
    };
  },
  mounted() {
    this.website = this.company.url;
  },
  methods: {
    // Event handlers
    countDownChanged(dismissCountDown) {
      this.showAlert = dismissCountDown;
    },

    submit() {
      this.isLoading = true;
      this.showSuccess = 0;
      this.showFailure = 0;
      this.requestErrors = [];
      this.company.url = this.website;

      return axios({
            method: this.method,
            url: this.submitUrl,
            data: this.company
          })
          .then(response => {
            if (this.shouldRedirect) {
              let redirectUrl = this.redirectUrl;
              if (this.method === 'post') {
                redirectUrl = redirectUrl + '/' + response.data.id;
              }
              window.location.href = redirectUrl;
            } else {
              this.showSuccess = 8;
            }
          })
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
            this.showFailure = 8;
          })
          .finally(() => {
            this.isLoading = false;
          });
    },
  },
};
</script>

<style>
@media (max-width: 1528px) {
  .media-1528px-ptb-25 {
    padding-top: 25px;
    padding-bottom: 25px;
  }
}
</style>