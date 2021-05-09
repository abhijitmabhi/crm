<template>
  <b-card no-body>
    <b-tabs ref="form" card v-model="activeTab">
      <b-tab title="Informationen">
        <location-address
            :address-data="addressData"
            ref="addressForm"
            class="mt-3"
        ></location-address>
        <location-contact
            :contact-data="contactData"
            ref="contactForm"
            class="mt-3"
        ></location-contact>
        <location-info
            :info-data="infoData"
            ref="infoForm"
            class="mt-3"
        ></location-info>
        <h5 class="mb-2">Öffnungszeiten</h5>
        <business-hours
            ref="openingHours"
            color="#db0630"
            :hourFormat24="true"
            :localization="localization"
            :days="openingHours"
            :switch-width="120"
            :type="'select'"
            name="businessHours"
        ></business-hours>
      </b-tab>
      <b-tab v-show="createdLocation" title="Bilder">
        <location-images
            :images-url="imagesUrl"
            ref="photosForm"
            class="mt-3"
        ></location-images>
      </b-tab>
      <div class="my-4" v-if="showSuccess || showFailure">
        <b-alert :show="showSuccess" variant="success" dismissible @dismissed="showSuccess = 0">
          Standort gespeichert
        </b-alert>
        <b-alert :show="showFailure" variant="danger" dismissible @dismissed="showFailure = 0">
          <p v-for="error in this.requestErrors">{{ error }}</p>
        </b-alert>
      </div>
      <div class="pb-4 d-flex justify-content-end">
        <a
            class="btn btn-outline-dark mr-3"
            :disabled="saving"
            :href="route('location.unfinished')"
        >Abbrechen</a>
        <button
            class="btn btn-primary"
            :disabled="saving"
            v-on:click.prevent="onClickSubmit"
            ref="theButton"
        >
          <span v-if="!saving && useCase === ''">Speichern</span>
          <span v-else-if="!saving && useCase === 'unfinished'">Speichern und Zurück</span>
          <span v-else class="d-flex justify-content-center align-items-center h-100">
            <b-spinner></b-spinner>
          </span>
        </button>
      </div>
    </b-tabs>
  </b-card>
</template>
<script>
import {merge} from "lodash";

import LocationAddress from "./LocationAddressComponent";
import LocationContact from "./LocationContactComponent";
import LocationInfo from "./LocationInfoComponent";
import LocationImages from "@LLI/images";
import localization from '@metadata/openingHoursLocalization.json';
import default_days from '@metadata/defaultOpeningHoursDays.json';

export default {
  components: {
    LocationAddress,
    LocationContact,
    LocationInfo,
    LocationImages,
  },
  data() {
    return {
      showSuccess: 0,
      showFailure: 0,
      requestErrors: [],
      saving: false,
      arrays: [
        "openinghours",
        "services",
        "languages",
        "brands",
        "payment_methods",
        "category",
      ],
      brands: [],
      photos: {},
      form_data: null,
      openingHours: default_days,
      localization: localization,
      loading: true,
      errored: false,
      createdLocation: false
    };
  },
  props: {
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
    location: {
      type: Object,
      default: () => {
        return {
          id: -1,
          name: "",
          address: "",
          address_addition: "",
          postcode: "",
          city: "",
          state: "",
          country: "",
          phone: "",
          mobilephone: "",
          fax: "",
          email: "",
          website: "",
          coordinates: {
            lat: "",
            lng: ""
          },
          description: "",
          keywordsDeleted: [],
          keywordsActive: [],
          selectedCitationCategories: []
        };
      },
    },
    companyId: {
      types: String | Number,
      required: true,
    },
    allCategories: {
      types: Array,
      required: true,
    },
    allCitationCategories: {
      types: Array,
      required: true,
    },
    activeTab: {
      types: String | Number,
      default: 0
    },
    useCase: {
      types: String,
      default: ''
    }
  },
  computed: {
    imagesUrl() {
      return `/api/companies/${this.companyId}/locations/${this.location.id}/images`;
    },
    addressData() {
      return this.createObjectFromKeys([
        "name",
        "address",
        "address_addition",
        "city",
        "postcode",
        "country",
        "state",
        "coordinates"
      ]);
    },
    contactData() {
      return this.createObjectFromKeys([
        "phone",
        "mobilephone",
        "fax",
        "email",
        "website"
      ]);
    },
    infoData() {
      let infoData = this.createObjectFromKeys([
        "mainCategory",
        "additionalCategories",
        "description",
        "keywordsDeleted",
        "keywordsActive",
        "selectedCitationCategories"
      ]);
      infoData['allCategories'] = this.allCategories;
      infoData['allCitationCategories'] = this.allCitationCategories;

      return infoData;
    },
  },
  methods: {
    createObjectFromKeys(keys) {
      const obj = {};
      keys.forEach(key => {
        obj[key] = this.location[key];
      });
      return obj;
    },
    onClickSubmit() {
      this.sendRequest(this.createRequestData());
      if (Object.keys(this.$refs.photosForm.$data.photos).length) {
        this.savePhotoUpdate();
      }
    },
    sendRequest(data) {
      this.setSaving();
      return axios({
        method: this.method,
        url: this.submitUrl,
        data: data
      }).then(() => {
        this.showSuccess = 8;
        if (this.useCase === 'unfinished') {
          window.location.href = this.route('location.unfinished');
        } else {
          window.location.href = this.redirectUrl;
        }
      }).catch(e => {
        this.requestErrors = this.getRequestErrors(e);
        this.showFailure = 8;
      }).finally(this.stopSaving);
    },
    async savePhotoUpdate() {
      const url = `/api/companies/${this.companyId}/locations/${this.location.id}/images`;
      for (let id in this.$refs.photosForm.$data.photos) {
        const data = {location_association: this.$refs.photosForm.$data.photos[id]};
        await axios.put(url + "/" + id, data);
      }
    },
    createRequestData() {
      const form = {};
      merge(
          form,
          this.$refs.addressForm.addressData,
          this.$refs.contactForm.contactData,
          this.$refs.infoForm.getInfoData()
      );
      form.openinghours = this.$refs.openingHours.days;

      this.arrays.forEach(array => {
        if (form[array] && !Array.isArray(form[array])) {
          console.error(`Value of ${array} is not an array`);
        }
      });
      return form;
    },
    setSaving() {
      let tb = this.$refs.theButton;
      tb.style.width = tb.offsetWidth + "px";
      tb.style.height = tb.offsetHeight + "px";
      this.saving = true;
    },
    stopSaving() {
      let tb = this.$refs.theButton;
      tb.style.width = "auto";
      tb.style.height = "auto";
      this.saving = false;
    },
  },
  mounted() {
    if (this.location.id > -1) {
      this.createdLocation = true
    }
    if (this.location.openinghours) {
      let openingHours = this.location.openinghours;
      for (let day of Object.values(openingHours)) {
        for (let timeRange of day) {
          if (timeRange.open == null) {
            timeRange.open = '';
          }
          if (timeRange.close == null) {
            timeRange.close = '';
          }
        }
      }
      this.openingHours = openingHours;
    }
  },
};
</script>
<style lang="scss">
@import "./resources/sass/business-hours.scss";
</style>
