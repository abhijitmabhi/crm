<template>
  <div>
    <h3>Locations die noch freigeschaltet werden müssen</h3>
    <b-alert variant="danger" :show="showErrorAlert">{{ errorAlertMessage }}</b-alert>
    <b-input v-model="searchString" name="searchBarSelectedLocations" type="text" class="form-control"
             id="searchBarSelectedLocations"
             placeholder="Kundenname oder Adresse eingeben.."/>
    <br>
    <table class="table-striped table table-sm table-responsive-sm">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Location</th>
        <th scope="col">Kunde</th>
        <th scope="col" class="text-center">Google Auth</th>
        <th scope="col" class="text-center">Informationen</th>
        <th scope="col" class="text-center">Bilder</th>
        <th scope="col" class="text-center">Citations</th>
        <th scope="col" class="text-center">Statistiken</th>
        <th scope="col" class="text-center">Vorschau</th>
        <th scope="col" class="text-center">Zugangsdaten</th>
        <th scope="col" class="text-center">Freischalten</th>
      </tr>
      </thead>
      <tbody class="tbody-dark">
      <tr v-for="(location, index) in filteredLocations" :key="index">
        <td scope="col">
          <a :href="route('companies.locations.show', [location.company.id, location.id])">
            {{ location.name }}<br>
            {{location.address }}<br>
            {{ location.postcode + " " + location.city }}
          </a>
        </td>
        <td scope="col">
          <a :href="route('companies.show', location.company.id)">{{ location.company.name }}</a>
        </td>
        <td scope="col" class="text-center">
          <google-auth-status
              :hasAuth="location.company.google_auth != null"
              :companyId="location.company.id"
          ></google-auth-status>
        </td>
        <td scope="col" class="text-center">
          <div v-if="isStateIncluded(location, locationState.INFORMATION_EXIST)">
            <i class="fas fa-check text-success h3"></i>
            <a :href="route('companies.locations.show', {company: location.company.id, location: location.id, useCase: 'unfinished'})">
              <i class="fas fa-pen text-dark h3"></i>
            </a>
          </div>
          <a v-else :href="route('companies.locations.show', {company: location.company.id, location: location.id, useCase: 'unfinished'})"
             class="btn btn-white border font-weight-bolder border-secondary">Bearbeiten</a>
        </td>
        <td scope="col" class="text-center">
          <div v-if="isStateIncluded(location, locationState.PICTURES_EXIST)">
            <i class="fas fa-check text-success h3"></i>
            <a :href="route('companies.locations.show', {company: location.company.id, location: location.id, activeTab: 1, useCase: 'unfinished'})">
              <i class="fas fa-pen text-dark h3"></i>
            </a>
          </div>
          <a v-else :href="route('companies.locations.show', {company: location.company.id, location: location.id, activeTab: 1, useCase: 'unfinished'})"
             class="btn btn-white border font-weight-bolder border-secondary">Bearbeiten</a>
        </td>
        <td scope="col" class="text-center">
          <div v-if="isStateIncluded(location, locationState.CITATIONS_DONE)">
            <i class="fas fa-check text-success h3"></i>
            <a :href="route('locations.citations.show', location.id)"><i class="fas fa-pen text-dark h3"></i></a>
          </div>
          <a v-else :href="route('locations.citations.show', location.id)"
             class="btn btn-white border font-weight-bolder border-secondary">Bearbeiten</a>
        </td>
        <td scope="col" class="text-center">
          <i v-if="isStateIncluded(location, locationState.STATISTICS_READY)" class="fas fa-check text-success h4"></i>
          <i v-else class="fas fa-clock h4 mb-0 text-secondary"></i>
        </td>
        <td scope="col" class="text-center">
          <a :href="'/companies/' + location.company.id + '/locations/statistics'">
            <i class="fas fa-chart-pie h4 text-primary"></i>
          </a>
        </td>
        <td scope="col" class="text-center">
          <i v-if="isStateIncluded(location, locationState.ACCESS_DATA_SENT)" class="fas fa-check text-success h3"></i>
          <p v-else class="text-center">Bekommt der Kunde <br>mit der Freischaltung <br>geschickt.</p>
        </td>
        <td scope="col" class="text-center">
          <a v-if="location.company.google_auth != null
              && isStateIncluded(location, locationState.INFORMATION_EXIST)
              && isStateIncluded(location, locationState.PICTURES_EXIST)
              && isStateIncluded(location, locationState.CITATIONS_DONE)"
             @click.prevent="activateAccount(location)"
             class="btn btn-white border font-weight-bolder border-secondary">Freischalten</a>
          <p v-else scope="col" class="text-center">Noch nicht vollständig.</p>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "LocationsRequiringUnlockTableComponent",
  props: {
    allUnfinishedLocations: {
      type: Array
    },
    initialSearchString: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      searchString: this.initialSearchString,
      showErrorAlert: false,
      errorAlertMessage: "",
      locationState : this.$enums.locationState
    };
  },
  methods: {
    activateAccount(location) {
      axios.post(this.route('api.location.activate', location.id))
          .then(() => {
            window.location.reload();
          }).catch((error) => {
            this.showErrorAlert = true;
            this.errorAlertMessage = error.response.data.errors;
          });
    },
    isStateIncluded(location, state) {
      return location.states.includes(state);
    }
  },
  computed: {
    filteredLocations: function () {
      let searchString = this.searchString;
      return this.allUnfinishedLocations.filter(function (location) {
        return location.name.toLowerCase().indexOf(searchString.toLowerCase()) >= 0
            || location.address.toLowerCase().indexOf(searchString.toLowerCase()) >= 0
            || location.postcode.toLowerCase().indexOf(searchString.toLowerCase()) >= 0
            || location.city.toLowerCase().indexOf(searchString.toLowerCase()) >= 0
            || location.company.name.toLowerCase().indexOf(searchString.toLowerCase()) >= 0;
      });
    },
  }
}
</script>

<style scoped>

</style>