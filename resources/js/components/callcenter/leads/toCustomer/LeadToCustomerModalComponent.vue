<template>
  <form>
    <div>
      <div>
        <h4>Lead einem Kunde zuweisen</h4>
      </div>
      <div class="mt-3 mb-2">
        <h5>Ausgewählter Lead</h5>
        <div>{{ lead.company_name }}</div>
        <div>{{ lead.street }}</div>
        <div>{{ lead.zip }} {{ lead.city }}</div>
      </div>
      <div class="mb-2">
        <b-row>
          <b-col sm="2">Tel.:</b-col>
          <b-col col sm="10">
            <a :href="'tel:'+lead.phone1">{{ lead.phone1 }}</a>
          </b-col>
        </b-row>
        <b-row v-if="lead.email">
          <b-col sm="2">Email:</b-col>
          <b-col col sm="10">
            <a :href="'mailto:' + lead.email">{{ lead.email }}</a>
          </b-col>
        </b-row>
        <b-row v-if="lead.website">
          <b-col sm="2">Website:</b-col>
          <b-col col sm="10">
            <a :href="lead.website" target="_blank">{{ lead.website }}</a>
          </b-col>
        </b-row>
      </div>
    </div>

    <div class="mt-4">
      <h5>Kommentar</h5>
      <b-form-group
          :state="commentLongEnough"
          invalid-feedback="mindestens 5 Zeichen"
      >
        <b-form-textarea v-model="data.comment"/>
      </b-form-group>
    </div>

    <div class="mt-4 row">
      <div class="col-6">
        <h5>Bestandskunde</h5>
        <b-input-group>
          <b-form-input
              v-model="searchTerm"
          ></b-form-input>
          <b-input-group-append>
            <b-button variant="primary" @click.prevent="getSimilarCustomers">
              <i class="fas fa-search"></i>
            </b-button>
          </b-input-group-append>
        </b-input-group>
        <div
            v-if="isLoading"
            class="d-flex justify-content-center align-items-center mt-2"
        >
          <b-spinner/>
        </div>
        <div v-else>
          <p v-if="!showConversionExistingCustomer" class="mt-2">
            Keine Kunden gefunden.
          </p>
          <div v-else class="mt-2">
            <div>
              <v-select
                  id="selectCustomer"
                  ref="selectCustomer"
                  :options="companies"
                  v-model="data.company_id"
                  label="name"
                  :reduce="company => company.id"
                  placeholder="Bestandskunde auswählen"
              ></v-select>
            </div>
            <button
                @click.prevent="askConversionConfirmationExistingUser"
                class="btn btn-primary mt-2"
                :disabled="!commentLongEnough || data.company_id === null"
            >Jetzt zuweisen
            </button>
          </div>
        </div>
      </div>

      <div class="col-6">
        <h5>Neuer Kunde</h5>
        <b-form-group
            :state="emailOk"
            invalid-feedback="Bitte eine gültige E-Mail angeben."
        >
          <b-input
              v-model="data.email"
              type="email"
              name="email"
              id="email"
              placeholder="Email"
          />
        </b-form-group>
        <button
            @click.prevent="askConversionConfirmationNewUser"
            :disabled="!emailOk || !commentLongEnough"
            class="btn btn-primary"
        >Neuer Kunde erstellen und zuweisen
        </button>
      </div>
    </div>

    <b-alert
        class="mt-3"
        variant="danger"
        :show="requestErrors.length > 0"
    >
      <p class="mb-0" v-for="error in requestErrors" :key="error">{{ error }}</p>
    </b-alert>

    <div class="pt-4">
      <center>
        <button
            @click.prevent="abortCreation"
            class="btn btn-white text-dark border border-secondary">Abbrechen
        </button>
      </center>
    </div>
  </form>
</template>

<script>
import {validateEmail} from "@utils/functions";

export default {
  props: {
    lead: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      data: {
        email: this.lead.email || "",
        comment: "",
        company_id: null
      },
      requestErrors: [],
      companies: [],
      isLoading: false,
      wasCustomerCheckSuccessful: true,
      searchTerm: this.lead.company_name
    };
  },
  computed: {
    showConversionExistingCustomer() {
      return this.companies.length > 0;
    },
    emailOk() {
      return validateEmail(this.data.email);
    },
    commentLongEnough() {
      return this.data.comment.length >= 5;
    }
  },
  mounted() {
    this.getSimilarCustomers();
  },
  methods: {
    abortCreation() {
      this.$emit("abort");
    },
    getSimilarCustomers() {
      let requestData = {
        searchTerm: this.searchTerm,
        phone: this.lead.phone,
        email: this.lead.email
      }
      this.isLoading = true;
      return axios
          .post(this.route('api.search.company'), requestData)
          .then((response) => {
            this.companies = response.data;
            this.$refs.selectCustomer.focus();
          })
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
          })
          .finally(() => this.isLoading = false);
    },
    askConversionConfirmationNewUser() {
      this.$bvModal
          .msgBoxConfirm("Neuer Kunde erstellen und Lead zuweisen?", {
            bodyClass: 'h5',
            okVariant: 'primary',
            okTitle: 'Ja',
            cancelTitle: 'Abbrechen',
            hideHeaderClose: true,
          })
          .then((result) => {
            if (result === true) {
              this.postConversionNewCustomer();
            }
          });
    },
    askConversionConfirmationExistingUser() {
      this.$bvModal
          .msgBoxConfirm("Lead jetzt zuweisen?", {
            bodyClass: 'h5',
            okVariant: 'primary',
            okTitle: 'Ja',
            cancelTitle: 'Abbrechen',
            hideHeaderClose: true,
          })
          .then((result) => {
            if (result === true) {
              this.postConversionExistingCustomer();
            }
          });
    },
    postConversionNewCustomer() {
      let route = this.route('api.convertLeadToNewCustomer', this.lead.id);
      return axios
          .post(route, this.data)
          .then(response => {
            // window.location.href = this.route('companies.locations.show', [response.data.company_id, response.data.id]);
            window.location.href = this.route('location.unfinished', {searchTerm: this.lead.company_name});
          })
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
          });
    },
    postConversionExistingCustomer() {
      let route = this.route('api.convertLeadToExistingCustomer', [this.lead.id, this.data.company_id]);
      return axios
          .post(route, this.data)
          .then(response => {
            // window.location.href = this.route('companies.locations.show', [this.data.company_id, response.data.id]);
            window.location.href = this.route('location.unfinished', {searchTerm: this.lead.company_name});
          })
          .catch((error) => {
            this.requestErrors = this.getRequestErrors(error);
          });
    },
  },
};
</script>