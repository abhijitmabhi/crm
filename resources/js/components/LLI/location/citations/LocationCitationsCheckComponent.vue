<template>
  <b-container fluid>
    <b-row>
      <b-col cols="4">
        <h2> {{ location.name }} Citations</h2>
        <p v-if="location.active_citations.length">
          Bitte trage diese Daten in allen unten aufgeführten Platformen ein.
        </p>
        <div class="mb-n1 mt-1">
          {{ location.name }}<br>
          {{ location.address }}<br>
          {{ location.city }} {{ location.postcode }}<br>
          Tel: {{ location.phone}}
        </div>
        <ol v-if="location.active_citations.length" class="mt-4">
          <li class="mb-3" v-for="(source, index) in location.active_citations" :key="index">
            <b>
              <a target="frameweb" :href="source.url">{{ source.name }}</a>
            </b>
            <b-form-select v-model="source.pivot.state" :options="options"></b-form-select>
          </li>
        </ol>
        <div v-else>
          <h4>Es existieren keine Citations!</h4>
        </div>
        <b-button
            variant="success"
            type="submit"
            @click="submit"
            class="mb-4"
        >
          Speichern
        </b-button>
        <b-alert variant="danger" :show="showErrorAlert">{{ errorAlertMessage }}</b-alert>
      </b-col>
      <div class="col-8">
        <iframe name="frameweb"></iframe>
      </div>
    </b-row>
  </b-container>
</template>

<script>
export default {
  name: "check-location-citations",
  props: {
    location: {
      type: Object,
      required: true,
      default: null,
    },
  },
  data() {
    return {
      options: [
        { value: this.$enums.LocationCitationState.TODO, text: 'Noch nicht Eingetragen.' },
        { value: this.$enums.LocationCitationState.DONE, text: 'Fertig eingetragen.' }
      ],
      showErrorAlert: false,
      errorAlertMessage: null
    };
  },
  methods: {
    submit: function() {
      this.$emit("showLoadingSpinner");
      const response = axios.post(
          this.route('api.update.citations', this.location.id),
          {
            citationSources: this.location.active_citations,
          })
          .then(() => {
            window.location.href = this.route('location.unfinished');
          })
          .catch((error) => {
            this.showErrorAlert = true;
            this.errorAlertMessage = error.response.data.message;
            this.$emit("showLoadingSpinner");
          });
    },
  }
}
</script>

<style scoped>

iframe{
  border:none;
  width:100%;
  height:1600px;
}

.invalid-feedback{
  font-size: 150%;
}
</style>