<template>
  <div class="container-fluid card">
    <div class="row">
      <div class="col">
        <b-alert variant="danger" :show="showErrorAlert">Leider ist ein Fehler aufgetreten!</b-alert>
        <b-input-group class="mb-2">
          <b-form-input
              v-model="searchInput"
              placeholder="Email oder Name eingeben.."
              @keyup.enter="refreshTableItems()"
          ></b-form-input>
          <b-input-group-append>
            <b-button variant="dark"  @click="searchInput = ''; refreshTableItems();" v-if="searchInput"><i class="fa fa-times px-1"></i></b-button>
            <b-button variant="dark"  @click="refreshTableItems()"><i class="fa fa-search px-1"></i></b-button>
          </b-input-group-append>
        </b-input-group>

        <b-table
            :busy="isTableInBusyState"
            :items="fetchCompanies"
            :fields="usedColumns"
            :per-page="shownEntriesPerPage"
            :current-page="currentPage"
            head-variant="dark"
            ref="table"
            responsive
            hover
        >
          <template #table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Laden...</strong>
            </div>
          </template>

          <template #cell(google_auth)="data" class="text-center">
            <google-auth-status
                :hasAuth="data.item.hasAuth"
                :companyId="data.item.id"
            ></google-auth-status>
          </template>

          <template #cell(email)="data">
            <a :href="'mailto:' + data.item.email">{{ data.item.email }}</a>
          </template>

          <template #cell(phone)="data">
            <a :href="'tel:' + data.item.phone ? data.item.phone : ''">{{ data.item.phone }}</a>
          </template>

          <template #cell(actions)="data">
            <a :href="'/companies/' + data.item.id + '/locations'">
              <i class="fas fa-map-marker-alt"></i>
            </a>
            <a :href="'/companies/' + data.item.id + '/locations/statistics'"
               class="ml-3"
            >
              <i class="fas fa-chart-pie"></i>
            </a>
            <a :href="'/companies/' + data.item.id"
               class="ml-3"
            >
              <i class="fas fa-arrow-right"></i>
            </a>
            <a :href="'/users/' + data.item.user_id"
               class="ml-3"
            >
              <i class="fas fa-user"></i>
            </a>
          </template>
        </b-table>

        <b-pagination
            :total-rows="totalEntriesOfCurrentArray"
            :per-page="shownEntriesPerPage"
            v-model="currentPage"
        ></b-pagination>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "CustomerListComponent",

  data() {
    return {
      usedColumns: [
        'name',
        { key: 'google_auth', label: 'Google Auth', class: 'text-center' },
        'email',
        { key: 'phone', label: 'Telefon' },
        { key: 'actions', label: 'Aktionen' },
      ],
      isTableInBusyState: false,
      currentPage: 1,
      shownEntriesPerPage: 20,
      searchInput: "",
      totalEntriesOfCurrentArray: 0,
      showErrorAlert: false,
      test: null
    };
  },

  methods: {
    //essentially just calls the fetchCompanies method
    //seems to be the only way to provide the tableContextObj
    refreshTableItems: function() {
      this.currentPage = 1
      this.$refs.table.refresh()
    },

    fetchCompanies: async function(tableContextObj) {
      try {
        let response = await axios.get(this.route('api.fetch.companies', {
          page: tableContextObj.currentPage,
          size: tableContextObj.perPage,
          searchInput: this.searchInput
        }));

        this.totalEntriesOfCurrentArray = response.data.meta.total
        return response.data.data

      } catch (error) {
        this.showErrorAlert = true;
        return [];

      } finally {
        this.isTableInBusyState = false;
      }
    }
  },

  watch: {
    searchInput: function (val) {
      if(val === "") {
        this.refreshTableItems()
      }
    }
  }
};
</script>

<style scoped>
.btn {
  border-radius: 0;
}
</style>
