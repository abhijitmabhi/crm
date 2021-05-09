<template>
  <form class="pb-4 mb-4">
    <h5 class="mb-2">Anschrift</h5>
    <div class="row">
      <div class="col-md-12">
        <b-form-group
            label="Name"
            label-for="name"
        >
          <b-form-input
              type="text"
              name="name"
              id="name"
              v-model="addressData.name"
          />
        </b-form-group>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <b-form-group
            label="Straße & Hausnummer"
            label-for="street"
        >
          <b-form-input
              type="text"
              name="street"
              id="street"
              v-model="addressData.address"
          />
        </b-form-group>
      </div>
      <div class="col-md-6">
        <b-form-group
            label="Addresszusatz"
            label-for="address_addition"
        >
          <b-form-input
              type="text"
              name="address_addition"
              id="address_addition"
              v-model="addressData.address_addition"
          />
        </b-form-group>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <b-form-group
            label="Postleitzahl"
            label-for="postcode"
        >
          <b-form-input
              type="text"
              name="postcode"
              id="postcode"
              v-model="addressData.postcode"
          />
        </b-form-group>
      </div>
      <div class="col-md-6">
        <b-form-group
            label="Stadt"
            label-for="city"
        >
          <b-form-input
              type="text"
              name="city"
              id="city"
              v-model="addressData.city"
          />
        </b-form-group>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 mb-0">
        <label for="country">Land</label>
        <v-select
            name="country"
            id="country"
            placeholder="Land auswählen"
            v-model="selectedCountry"
            :options="countries"
            label="label"
            :clearable="false"
        ></v-select>
      </div>
      <div class="col-md-6">
        <label>Bundesland</label>
        <v-select
            name="state"
            id="state"
            placeholder="Bundesland auswählen"
            v-model="selectedState"
            :options="states"
            :clearable="false"
        ></v-select>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <b-form-group
            label="Latitude"
            label-for="latitude"
        >
          <b-form-input
              type="text"
              name="latitude"
              id="latitude"
              v-model="addressData.coordinates.lat"
          />
        </b-form-group>
      </div>
      <div class="col-md-6">
        <b-form-group
            label="Longitude"
            label-for="longitude"
        >
          <b-form-input
              type="text"
              name="longitude"
              id="longitude"
              v-model="addressData.coordinates.lng"
          />
        </b-form-group>
      </div>
    </div>
  </form>
</template>
<script>

export default {
  props: {
    addressData: {
      type: Object,
      default: () => {
        return {
          name: "",
          address: "",
          address_addition: "",
          postcode: "",
          city: "",
          state: "",
          country: "",
          coordinates: {
            lat: "",
            lng: ""
          }
        };
      }
    },
  },
  data() {
    return {
      countries: [
        {label: 'Deutschland', code: 'DE'},
        {label: 'Schweiz', code: 'CH'},
        {label: 'Österreich', code: 'AT'}
      ],
      selectedCountry: null,
      selectedState: null,
      countryStates: {
        'DE': ["Baden-Württemberg", "Bayern", "Berlin", "Brandenburg", "Bremen", "Hamburg", "Hessen", "Mecklenburg-Vorpommern", "Niedersachsen", "Nordrhein-Westfalen", "Rheinland-Pfalz", "Saarland", "Sachsen", "Sachsen-Anhalt", "Schleswig-Holstein", "Thüringen"],
        'CH': ['Aargau', 'Bern', 'Fribourg', 'Genève', 'Glarus', 'Graubünden', 'Jura', 'Luzern', 'Neuchâtel', 'St.Gallen', 'Schaffhausen', 'Schwyz', 'Solothurn', 'Thurgau', 'Ticino', 'Uri', 'Valais', 'Vaud', 'Zug', 'Zürich'],
        'AT': ['Voralberg', 'Tirol', 'Salzburg', 'Kärnten', 'Steiermark', "Oberösterreich", 'Niederösterreich', 'Burgenland', 'Wien'],
        '': []
      },
      states: []
    }
  },
  watch: {
    selectedCountry(newSelected, oldSelected) {
      if (this.selectedCountry) {
        this.addressData.country = this.selectedCountry.code;
        this.states = this.countryStates[this.selectedCountry.code];
        if (oldSelected && newSelected !== oldSelected) {
          this.selectedState = null;
        }
      }
    },
    selectedState() {
      this.addressData.state = this.selectedState;
    },
  },
  mounted() {
    this.selectedCountry = this.countries.find(country => country.code === this.addressData.country);
      this.selectedState = this.addressData.state;
  }
};
</script>