<template>
  <div>
    <h3>Kunden die noch keine Location registriert haben</h3>
    <b-alert variant="danger" :show="showErrorAlert">{{ errorAlertMessage }}</b-alert>
    <b-input v-model="searchString" name="searchBarSelectedLocations" type="text" class="form-control"
             id="searchBarSelectedLocations"
             placeholder="Kundenname eingeben.."/><br>
    <table class="table-striped table table-sm table-responsive-sm">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Kunde</th>
        <th scope="col">Website</th>
        <th scope="col" class="text-center">Location erstellen</th>
      </tr>
      </thead>
      <tbody class="tbody-dark">
      <tr v-for="(company, index) in filteredCompanies" :key="index">
        <td scope="col"><a :href="'/companies/' + company['id']">{{ company['name'] }}</a></td>
        <td scope="col"><a :href="company['url']" target="_blank">{{ company['url'] }}</a></td>
        <td scope="col" class="text-center">
          <a :href="'/companies/' + company['id'] + '/locations/create'" class="btn btn-white border font-weight-bolder border-secondary">Erstellen</a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "CompaniesWithoutLocationsTableComponent",
  props: {
    companiesNoLocations: {
      type: Array
    }
  },
  data() {
    return {
      searchString: '',
      showErrorAlert: false,
      errorAlertMessage: ""
    };
  },
  computed: {
    filteredCompanies: function () {
      let searchString = this.searchString;
      return this.companiesNoLocations.filter(function (company) {
        return company.name.toLowerCase().indexOf(searchString.toLowerCase()) >= 0;
      });
    },
  }
}
</script>

<style scoped>

</style>