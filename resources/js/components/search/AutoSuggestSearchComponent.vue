<template>
  <div class="position-relative">
    <b-form-input
        autocomplete="off"
        @focus="showSuggestionList = true"
        v-model="searchTerm"
        :id="id"
        :placeholder="placeholder"
        :name="name"
    />
    <b-list-group
        class="position-absolute  bootom-0 bg-white"
        style="z-index: 1000; min-width: 100%; max-height: 100%; min-height: 200px; overflow: scroll"
        v-if="hasSuggestions && showSuggestionList"
        v-show="showSuggestions"
    >
      <b-list-group-item
          @click="submit(suggestion.name)"
          v-for="suggestion in suggestions"
          :key="suggestion.tel"
      >
        <div>{{ suggestion.name }}</div>
        <small>{{ suggestion.tel }}</small>
      </b-list-group-item>
    </b-list-group>
  </div>
</template>

<script>
import {debounce} from "lodash"

export default {
  props: {
    "id": String,
    "formId": String,
    "searchIndexes": Array,
    "name": String,
    "placeholder": String,
    "showSuggestions": Boolean
  },
  data() {
    return {
      suggestions: {},
      showSuggestionList: false,
      searchTerm: "",
      debounceGetData: debounce(this.getSuggestions, 500,),
    }
  },
  computed: {
    hasSuggestions() {
      return Object.keys(this.suggestions).length > 0;
    }
  },
  watch: {
    searchTerm() {
      this.debounceGetData();
    }
  },
  methods: {
    submit(val) {
      this.searchTerm = val;
      this.showSuggestionList = false;
    },
    getSuggestions() {
      if (!this.showSuggestions) {
        return;
      }
      let instance = this;
      instance.suggestions = [];
      axios.post("/api/search", {
        "searchIndexes": this.searchIndexes,
        "searchTerm": this.searchTerm
      }).then(response => {
        instance.suggestions = response.data;
      })
    }
  }

}
</script>

<style scoped>
.list-group-item:hover {
  background-color: rgba(0, 0, 0, .1);
  cursor: pointer;
}
</style>