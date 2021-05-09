<template>
  <form class="pb-4 mb-4">
    <h5 class="mb-2">Suchoptimierung</h5>
    <div class="row mb-2">
      <b-form-group
          class="col-md-6"
          label="Hauptkategorie"
          label-for="main_category"
      >
        <v-select
            v-model="mainCategory"
            id="main_category"
            name="main_category"
            placeholder="Bitte auswählen"
            :options="infoData.allCategories"
            @search="debounceSearch"
        >
          <span slot="no-options" @click="$refs.select.open = false">
            Keine Hauptkategorie ausgewählt.
          </span>
        </v-select>
      </b-form-group>
      <b-form-group
          class="col-md-6"
          label="Nebenkategorien"
          label-for="additional_categories"
      >
        <v-select
            v-model="additionalCategories"
            id="additional_categories"
            name="additional_categories"
            placeholder="Bitte auswählen"
            :options="infoData.allCategories"
            @search="debounceSearch"
            multiple
        >
          <span slot="no-options" @click="$refs.select.open = false">
            Keine Nebenkategorien ausgewählt.
          </span>
        </v-select>
      </b-form-group>
    </div>
    <div class="row mb-2">
      <b-form-group
          class="col-md-12"
          label="Beschreibung"
          label-for="description"
          invalid-feedback="maximal 750 Zeichen"
          :state="isDescriptionValid"
      >
        <b-textarea
            class="form-control"
            name="description"
            id="description"
            v-model="description"
        />
      </b-form-group>
    </div>
    <div class="row">
      <b-form-group
          class="col"
          label="Suchbegriffe"
          label-for="search_terms"
      >
        <v-select
            taggable
            multiple
            push-tags
            v-model="selectedTerms"
            :options="selectedAndDeletedTerms"
            @input="limiter"
            :disabled="hasRole(this.$enums.role.CUSTOMER)"
            id="search_terms"
            name="search_terms"
        >
          <span slot="no-options" @click="$refs.select.open = false">
            Noch keine Suchbegriffe vorhanden!
          </span>
        </v-select>
        <b-form-invalid-feedback :state="searchTermsValidState">
          Bitte nur maximal 5 Suchbegriffe eingeben!
        </b-form-invalid-feedback>
      </b-form-group>
    </div>
    <div class="row">
      <b-form-group
          class="col"
          label="Citation Kategorien"
          label-for="citationCategories"
      >
        <v-select
            multiple
            v-model="selectedCitationCategories"
            :options="infoData.allCitationCategories"
            id="citationCategories"
            name="citationCategories"
        >
        </v-select>
      </b-form-group>
    </div>
  </form>
</template>

<script>

export default {
  name: "location-info",
  props: {
    infoData: {
      type: Object,
      default: () => {
        return {
          mainCategory: "",
          additionalCategories: [],
          description: "",
          keywordsDeleted: [],
          keywordsActive: [],
          allCategories: [],
          allCitationCategories: [],
          selectedCitationCategories: [],
          isUserCustomer: true
        };
      }
    },
  },
  data() {
    return {
      selectedTerms: null,
      deletedTerms: null,
      selectedAndDeletedTerms: null,
      mainCategory: null,
      additionalCategories: null,
      searchTermsValidState: true,
      description: "",
      selectedCitationCategories: null
    };
  },
  beforeMount() {
    this.selectedTerms = this.infoData.keywordsActive;
    this.deletedTerms = this.infoData.keywordsDeleted;
    this.selectedAndDeletedTerms = this.mergeTerms();

    this.mainCategory = this.infoData.mainCategory;
    this.additionalCategories = this.infoData.additionalCategories;

    this.selectedCitationCategories = this.infoData.selectedCitationCategories;

    this.description = this.infoData.description;
  },
  computed: {
    isDescriptionValid() {
      return this.description && this.description.length <= 750;
    }
  },
  methods: {
    mergeTerms: function() {
      return this.selectedTerms.concat(this.deletedTerms);
    },
    getInfoData: function() {
      return {
        mainCategory: this.mainCategory,
        additionalCategories: this.additionalCategories,
        description: this.description,
        rank_queries: this.selectedTerms,
        selectedCitationCategories: this.selectedCitationCategories
      }
    },
    debounceSearch(search, loading) {
      if(search.length) {
        loading(true);
        this.search(loading, search, this);
      }
    },
    search: _.debounce((loading, search, vm) => {
        loading(false);
    }, 350),
    limiter: function(searchTerms) {
      const MAX_SEARCH_TERMS_ALLOWED = 5;
      if(searchTerms.length > MAX_SEARCH_TERMS_ALLOWED) {
        searchTerms.pop()
        this.searchTermsValidState = false;
      } else {
        this.searchTermsValidState = true;
      }
    }

  }
}
</script>
