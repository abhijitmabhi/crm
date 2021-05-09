<template>
  <form :id="`form_lead_${lead.id}`" method="POST" action>
    <div
        class="lead-form-company-info d-flex flex-column flex-md-row"
        style="margin-bottom:40px;"
    >
      <a
          :href="googleMapsSearchUrl"
          target="_blank"
      >
        <img class="mr-4 image_lead_map" :src="lead.screenshot"/>
      </a>
      <b-form-textarea
          v-model="lead.important_note"
          placeholder="Wichtige Notiz hinterlegen..."
      ></b-form-textarea>
    </div>

    <div class="lead-form-form">
      <b-form-group label="Name GF / Inhaber" :label-for="`lead_${lead.id}_contact_name`">
        <b-form-input
            :id="`lead_${lead.id}_contact_name`"
            v-model="lead.contact_name"
            name="contact_name"
            placeholder="name angeben"
            @input="emit"
        />
      </b-form-group>
      <b-form-group
          label="Name weiterer Ansprechpartner"
          :label-for="`lead_${lead.id}_additional_contacts`"
      >
        <b-form-input
            :id="`lead_${lead.id}_additional_contacts`"
            v-model="lead.additional_contacts"
            name="additional_contacts"
            placeholder="Zusätzlichen Kontakt angeben (falls vorhanden)"
            @input="emit"
        />
      </b-form-group>

      <div class="d-flex flex-column flex-md-row">
        <b-form-group
            label="Branche"
            :label-for="`lead_${lead.id}_category`"
            class="flex-grow-1 form-row-gap"
        >
          <b-form-input
              :id="`lead_${lead.id}_category`"
              v-model="lead.category"
              readonly
              name="category"
              placeholder="Branche angeben"
              @input="emit"
          />
        </b-form-group>
      </div>
      <b-form-group label="Adresse" :label-for="`lead_${lead.id}_street`">
        <b-form-input
            :id="`lead_${lead.id}_street`"
            v-model="lead.street"
            name="street"
            placeholder="Straße angeben"
            @input="emit"
        />
      </b-form-group>
      <b-form-group label="Potleitzahl" :label-for="`lead_${lead.id}_zip`">
        <b-input
            :id="`lead_${lead.id}_zip`"
            v-model="lead.zip"
            onkeyup="this.value=this.value.replace(/[^\d]/,'')"
            name="zip"
            placeholder="PLZ angeben"
            @input="emit"
        />
      </b-form-group>
      <b-input-group class="mb-3">
        <label :for="`lead_${lead.id}_email`" class="d-block w-100">E-Mail</label>
        <b-form-input
            :id="`lead_${lead.id}_email`"
            v-model="lead.email"
            name="email"
            placeholder="Email"
            @input="emit"
        />
        <b-input-group-append>
          <b-button variant="primary" @click="mailto" class="lead-form-append-button">
            <i class="fas fa-envelope"></i>
          </b-button>
        </b-input-group-append>
      </b-input-group>
      <b-input-group class="mb-3">
        <label :for="`lead_${lead.id}_website`" class="d-block w-100">Impressum</label>
        <b-input
            :id="`lead_${lead.id}_website`"
            v-model="lead.website"
            name="website"
            placeholder="Impressum angeben (sofern bekannt)"
            @input="emit"
        />
        <b-input-group-append>
          <b-button
              variant="primary"
              @click.prevent="onClickOpenWebsite"
              class="lead-form-append-button"
          >
            <i class="fas fa-globe-europe"></i>
          </b-button>
        </b-input-group-append>
      </b-input-group>
      <b-form-group label="Telefon" :label-for="`lead_${lead.id}_phone1`">
        <b-form-input
            type="phone"
            :id="`lead_${lead.id}_phone1`"
            v-model="lead.phone1"
            onkeyup="this.value=this.value.replace(/[^\d+ ]/, '')"
            name="phone1"
            placeholder="Telefonnummer"
            @input="emit"
        />
      </b-form-group>
      <div class="d-flex justify-content-end">
        <b-button variant="primary" @click="correction">
          Änderungen speichern
        </b-button>
      </div>
    </div>
    <hr/>
    <p class="lead-form-appointment-info h4 mb-3">
      Neuen Termin vereinbaren für
      <a :href="'tel:' + lead.phone1">{{ lead.phone1 }}</a>
    </p>
    <lead-timer
        v-if="timer"
        :lead-id="leadId"
        @levelChanged="handleLevelChange"
        @tick="updateInterval"
        ref="timer"
        class="mb-3 rounded overflow-hidden"
    ></lead-timer>
  </form>
</template>
<script>
import {validURL} from "@utils/functions";
import LeadTimer from "./timer";

export default {
  components: {LeadTimer},
  props: {
    lead: {
      type: Object,
      required: true,
    },
    timer: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      leadId: this.lead.id,
    };
  },
  computed: {
    phone() {
      if (this.lead && this.lead.phone1) {
        var cleanedNumber = this.lead.phone1.toString().replace(/\(|\)|\-/g, "");
        return "tel:" + cleanedNumber;
      }
      return "#";
    },
    googleMapsSearchUrl() {
      this.getGoogleSearchUrl(this.lead);
    }
  },
  methods: {
    emit(e) {
      let key = e.target.id;
      this.$emit("change", key, this.lead[key]);
    },
    mailto(e) {
      e.preventDefault();
      var email = document.getElementById(`lead_${this.lead.id}_email`).value;
      var element = document.createElement("a");
      element.href = `mailto:${email}`;
      element.click();
    },
    onClickOpenWebsite() {
      if (validURL(this.lead.website)) {
        window.open(this.lead.website, '_blank');
      } else {
        alert("Bitte eine gültige URL als Impressum angeben!");
      }
    },
    correction() {
      this.lead.reason = "CORRECTION";
      axios.put(`/api/leads/${this.leadId}`, this.lead)
          .then(() => location.reload())
          .catch(e => {
            this.showErrorMessage(e.response.data.errors);
          });
    },
    showErrorMessage(data) {
      let error_message = '';
      for (var key in data) {
        if (data.hasOwnProperty(key)) {
          error_message += `${data[key]} `;
        }
      }
      this.$alert(error_message);
    },
    handleLevelChange(event) {
      this.warningLevel = event;
    },
    updateInterval(interval) {
      this.interval = interval;
    },
  },
};
</script>

<style lang="scss" scoped>
.image_lead_map {
  max-width: 100%;
}

.lead-form-append-button {
  min-width: 45px;
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
