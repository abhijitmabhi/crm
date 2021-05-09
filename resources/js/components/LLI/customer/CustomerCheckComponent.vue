<template>
  <div>
    <b-card header="Hinzufügen: Kunde" header-class="simple-card-header" header-tag="h4">
      <form id="form">
        <b-row>
          <b-col>
            <b-form-group
                label-class="font-weight-bold"
                label="Name">
              <b-form-input
                  v-model="check.searchTerm"
                  name="searchTerm"
                  id="searchTerm">
              </b-form-input>
            </b-form-group>
          </b-col>
          <b-col>
            <b-form-group
                label-class="font-weight-bold"
                label="Telefon">
              <b-form-input
                  v-model="check.phone"
                  type="tel"
                  name="phone"
                  id="phone">
              </b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <input v-on:click="onClickSubmit" type="button" value="Daten prüfen"
               class="btn btn-white border font-weight-bolder border-secondary">
      </form>
    </b-card>

    <div v-if="this.showExistingComponent">
      <h2 style="margin-top: 20px; margin-bottom: 20px">Um Duplikate zu vermeiden: Bitte überprüfe die unten
        aufgeführten Kunden, Standorte und Leads.</h2>
      <b-row class="d-flex flex-nowrap">
        <b-col>
          <b-card header-class="simple-card-header" header-tag="h4">
            <customer-check-result-list
                :data="this.companies"
                title="Existierende Kunden"
                data-type="company">
            </customer-check-result-list>
          </b-card>
        </b-col>
        <b-col>
          <b-card header-class="simple-card-header" header-tag="h4">
            <customer-check-result-list
                :data="this.locations"
                title="Existierende Standorte"
                data-type="location">
            </customer-check-result-list>
          </b-card>
        </b-col>
        <b-col>
          <b-card header-class="simple-card-header" header-tag="h4">
            <customer-check-result-list
                :data="this.leads"
                title="Existierende Leads"
                data-type="lead">
            </customer-check-result-list>
          </b-card>
        </b-col>
      </b-row>
      <div v-if="this.showExistingComponent">
        <h2 style="margin-top: 20px; margin-bottom: 20px">Es gibt noch keinen Kunden, Standort oder Lead?</h2>
        <div class="row">
          <div class="col-md-12">
            <b-card header-class="simple-card-header" header-tag="h4">
              <h2>Neuen Kunden anlegen</h2>
              <a href="/companies/create" class="btn btn-white border font-weight-bolder border-secondary">Jetzt
                Kunden eingeben</a>
            </b-card>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    check: {
      type: Object,
      default: () => {
        return {
          searchTerm: "",
          phone: "",
        }
      },
    },
    method: {
      type: String,
      default: "post"
    },
    submitUrl: {
      type: String,
      default: ""
    },
  },
  data() {
    return {
      showExistingComponent: false,
      companies: [],
      leads: [],
      locations: []
    };
  },
  methods: {
    onClickSubmit() {
      this.sendRequest(this.check);
    },
    async sendRequest(data) {
      this.showExistingComponent = false;
      return await axios({
        method: this.method,
        url: this.submitUrl,
        data: data
      }).then(response => {
        this.showExistingComponent = true;
        this.companies = response.data.companies;
        this.leads = response.data.leads;
        this.locations = response.data.locations;
      });
    },
  },
};
</script>
