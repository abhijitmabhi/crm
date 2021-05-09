<template>
  <b-overlay :show="loading">
    <div class="container-fluid">
      <div class="pb-3">
        <h2>Neuen Lead erstellen</h2>
        <small>* Pflichtangaben</small>
      </div>

      <b-form>
        <div v-if="!isExpert" class="mb-4">
          <h4>SAM auswählen</h4>
          <b-form-group
              label="Name*"
              label-for="expert"
              label-class="mb-0"
          >
            <v-select
                id="expert"
                name="expert"
                :options="allExperts"
                label="name"
                placeholder="Bitte auswählen"
                v-model="selectedExpert"
            ></v-select>
          </b-form-group>
        </div>

        <h4 class="">Unternehmen</h4>
        <div class="mb-2">
          <b-form-group
              id="companyName"
              label="Name*"
              label-for="companyName"
              label-class="mb-0"
          >
            <b-form-input
                v-model="formData.company_name"
                name="companyName"
                id="companyName"
                type="text"
                :state="getCompanyNameValidState"
                class="form-control"
                placeholder="Name"
            />
            <b-form-invalid-feedback :state="getCompanyNameValidState">
              Bitte den Namen des Leads angeben!
            </b-form-invalid-feedback>
          </b-form-group>
        </div>
        <div class="mb-2">
          <b-form-group
              label="Kategorie*"
              label-for="category"
              label-class="mb-0"
          >
            <v-select
                id="category"
                name="category"
                :options="allCategories"
                placeholder="Bitte auswählen"
                v-model="formData.category"
            ></v-select>
          </b-form-group>
        </div>

        <h4 class="pt-5">Anschrift</h4>
        <div class="row mb-2">
          <div class="col-md-4">
            <b-form-group
                id="zipCode"
                label="PLZ*"
                label-for="zipCode"
                label-class="mb-0"
            >
              <b-form-input
                  v-model="formData.zip"
                  id="zipCode"
                  name="zipCode"
                  type="text"
                  :state="getZipCodeValidState"
                  placeholder="Postleizahl"
              />
              <b-form-invalid-feedback :state="getZipCodeValidState">
                Bitte PLZ (5 Ziffern) angeben!
              </b-form-invalid-feedback>
            </b-form-group>
          </div>
          <div class="col-md-8">
            <b-form-group
                id="city"
                label="Stadt*"
                label-for="city"
                label-class="mb-0"
            >
              <b-input
                  v-model="formData.city"
                  id="city"
                  name="city"
                  type="text"
                  :state="getCityValidState"
                  placeholder="Stadt"
              />
              <b-form-invalid-feedback :state="getCityValidState">
                Bitte den Namen der Stadt eingeben!
              </b-form-invalid-feedback>
            </b-form-group>
          </div>
        </div>
        <div class="mb-2">
          <b-form-group
              id="streetAndHouseNumber"
              label="Straße und Hausnummer*"
              label-for="streetAndHouseNumber"
              label-class="mb-0"
          >
            <b-input
                v-model="formData.street"
                id="streetAndHouseNumber"
                name="streetAndHouseNumber"
                type="text"
                :state="getStreetAndHouseNumberValidState"
                placeholder="Straße"
            />
            <b-form-invalid-feedback :state="getStreetAndHouseNumberValidState">
              Bitte eine Straße angeben!
            </b-form-invalid-feedback>
          </b-form-group>
        </div>

        <h4 class="pt-5">Informationen</h4>
        <div class="mb-3">
          <b-form-group
              id="contactPartner"
              label="Ansprechpartner*"
              label-for="contactPartner"
              label-class="mb-0"
          >
            <b-input
                v-model="formData.contact_name"
                id="contactPartner"
                name="contactPartner"
                type="text"
                :state="getContactPartnerValidState"
                placeholder="Ansprechpartner"
            />
            <b-form-invalid-feedback :state="getContactPartnerValidState">
              Bitte einen Ansprechpartner eingeben!
            </b-form-invalid-feedback>
          </b-form-group>
        </div>
        <div class="mb-3">
          <b-form-group
              id="contactPartnerPhoneNumber"
              label="Telefonnummer*"
              label-for="contactPartnerPhoneNumber"
              label-class="mb-0"
          >
            <b-input
                v-model="formData.phone1"
                id="contactPartnerPhoneNumber"
                name="contactPartnerPhoneNumber"
                type="text"
                :state="getContactPartnerPhoneNumberValidState"
                placeholder="Telefonnummer"
            />
            <b-form-invalid-feedback :state="getContactPartnerPhoneNumberValidState">
              Bitte eine Telefonnummer eingeben!
            </b-form-invalid-feedback>
          </b-form-group>
        </div>
        <div class="mb-3">
          <b-form-group
              id="contactPartnerEmail"
              label="Email"
              label-for="contactPartnerEmail"
              label-class="mb-0"
          >
            <b-input
                v-model="formData.email"
                id="contactPartnerEmail"
                name="contactPartnerEmail"
                type="text"
                :state="getContactPartnerEmailValidState"
                placeholder="Email"
            />
          </b-form-group>
        </div>
        <div class="mb-0">
          <b-form-group
              id="website"
              label="Webseite"
              label-for="website"
              label-class="mb-0"
          >
            <b-input
                v-model="formData.website"
                id="website"
                name="website"
                type="text"
                placeholder="Webseite"
                :state="this.getWebsiteValidState(this.formData.website)"
            />
          </b-form-group>
        </div>

        <h4 class="pt-5">Termin (wenn vereinbart)</h4>
        <div class="row">
          <div class="col-xl-6 col-sm-12">
            <b-form-group
                label="Termin Start"
                label-for="appointmentStart"
                label-class="mb-0"
            >
              <b-input-group>
                <flat-pickr
                    v-model="formData.closed_until"
                    :config="config"
                    class="form-control"
                    placeholder="Auswählen"
                    name="appointmentStart"
                    id="appointmentStart"
                    ref="appointmentStartPickr"
                    @on-close="setAppointmentEndMinDate"
                >
                </flat-pickr>
                <b-input-group-append>
                  <button class="btn btn-default" type="button" title="Toggle" data-toggle>
                    <i class="fa fa-calendar">
                      <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                  </button>
                  <button class="btn btn-default" type="button" title="Clear" data-clear>
                    <i class="fa fa-times">
                      <span aria-hidden="true" class="sr-only">Clear</span>
                    </i>
                  </button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </div>
          <div class="col-xl-6 col-sm-12">
            <b-form-group
                label="Termin Ende"
                label-for="appointmentEnd"
                label-class="mb-0"
            >
              <b-input-group>
                <flat-pickr
                    v-model="formData.appointment_end"
                    :config="config"
                    class="form-control"
                    placeholder="Auswählen"
                    name="appointmentEnd"
                    id="appointmentEnd"
                    ref="appointmentEndPickr"
                >
                </flat-pickr>
                <div class="input-group-append">
                  <button class="btn btn-default" type="button" title="Toggle" data-toggle>
                    <i class="fa fa-calendar">
                      <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                  </button>
                  <button class="btn btn-default" type="button" title="Clear" data-clear>
                    <i class="fa fa-times">
                      <span aria-hidden="true" class="sr-only">Clear</span>
                    </i>
                  </button>
                </div>
              </b-input-group>
            </b-form-group>
          </div>
        </div>
        <div class="mb-3">
          <b-form-group
              label="Kommentar"
              label-class="mb-0"
              label-for="comment"
          >
              <textarea
                  v-model="formData.appointment_comment"
                  class="form-control"
                  rows="5"
                  id="comment"
                  name="comment"
              ></textarea>
          </b-form-group>
        </div>

        <div class="col ml-n3">
          <a role="button" type="submit" class="btn btn-primary" @click.prevent="onClickSubmit">Lead erstellen</a>
          <div class="mt-2">
            <b-alert variant="danger" :show="errorMessages.length > 0">
              <p class="mb-0" v-for="error in errorMessages" :key="error">{{ error }}</p>
            </b-alert>
          </div>
        </div>
      </b-form>
    </div>
  </b-overlay>
</template>

<script>

export default {
  name: 'CreateLeadComponent',
  props: {
    allCategories: {
      type: Array
    },
    allExperts: {
      type: Array
    },
    isExpert: {
      type: Boolean,
      default: false
    },
    userId: {
      type: String | Number,
      required: false
    },
    routeSuccess: "",
    formData: {
      type: Object,
      default: () => {
        return {
          company_name: null,
          zip: null,
          city: null,
          street: null,
          contact_name: null,
          phone1: null,
          email: "",
          website: "",
          category: null,
          appointment_comment: "",
          closed_until: null,
          appointment_end: null,
          expert_status: 0,
          status: 1,
        };
      },
    },
  },

  data() {
    return {
      config: {
        wrap: true,
        altInput: true,
        enableTime: true,
        altFormat: "Y-m-d, H:i",
        minDate: "today"
      },

      selectedExpert: null,
      errorMessages: [],

      loading: false
    }
  },

  methods: {
    onClickSubmit() {
      let formValid = !!(this.getCompanyNameValidState && this.getZipCodeValidState && this.getStreetAndHouseNumberValidState
          && this.getCityValidState && this.getContactPartnerValidState && this.getContactPartnerPhoneNumberValidState
          && (this.getContactPartnerEmailValidState || this.getContactPartnerEmailValidState === null)
          && (this.getWebsiteValidState(this.formData.website) || this.getWebsiteValidState(this.formData.website) == null));

      if (formValid) {
        this.submitForm();
      } else {
        this.errorMessages = ["Bitte alle (Pflicht-)Felder korrekt ausfüllen."];
      }
    },

    setAppointmentEndMinDate() {
      let endPickr = this.$refs.appointmentEndPickr.fp;
      endPickr.set("minDate", this.formData.closed_until);
    },

    submitForm() {
      if (this.expertId && this.isCategorySelected) {
        this.loading = true;
        axios
            .put(`/api/users/${this.expertId}/leads`, this.formData)
            .then(() => {
              window.location.href = this.routeSuccess;
            }).catch((error) => {
              this.errorMessages = this.getRequestErrors(error);
              this.loading = false;
            });
      } else {
        if (!this.expertId) {
          this.errorMessages = ["Bitte SAM auswählen."];
        } else {
          this.errorMessages = ["Bitte Kategorie auswählen."];
        }
      }
    },
  },

  computed: {
    expertId() {
      let expertId = null;
      if (this.isExpert) {
        expertId = this.userId;
      } else if (this.selectedExpert) {
        expertId = this.selectedExpert['id'];
      }
      return expertId;
    },

    isCategorySelected() {
      return !!this.formData.category;
    },

    getCompanyNameValidState() {
      const regExp = /.+/;
      return this.formData.company_name !== null ? regExp.test(this.formData.company_name) : null;
    },

    getZipCodeValidState() {
      const regExp = /^([0]{1}[1-9]{1}|[1-9]{1}[0-9]{1})[0-9]{3}$/;
      return this.formData.zip !== null ? regExp.test(this.formData.zip) : null;
    },

    getStreetAndHouseNumberValidState() {
      const regExp = /[a-zA-ZäöüÄÖÜ \.]+ [0-9]+[a-zA-Z]?/;
      return this.formData.street !== null ? regExp.test(this.formData.street) : null;
    },

    getCityValidState() {
      const regExp = /^[a-zA-ZäöüÄÖÜ]+(?:[\s-][a-zA-ZäöüÄÖÜ]+)*$/;
      return this.formData.city !== null ? regExp.test(this.formData.city) : null;
    },

    getContactPartnerValidState() {
      const regExp = /.+/;
      return this.formData.contact_name !== null ? regExp.test(this.formData.contact_name) : null;
    },

    getContactPartnerPhoneNumberValidState() {
      const regExp = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
      return this.formData.phone1 !== null ? regExp.test(this.formData.phone1) : null;
    },

    getContactPartnerEmailValidState() {
      if (this.formData.email === "") {
        return null;
      } else if (this.formData.email !== null) {
        const regExp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regExp.test(this.formData.email);
      }
    },
  }
}
</script>

<style scoped>

.btn-primary {
  background-color: #ae0022;
}

/* remove the border of the spinner overlay */
/deep/ .bg-light {
  border: none;
}

</style>


