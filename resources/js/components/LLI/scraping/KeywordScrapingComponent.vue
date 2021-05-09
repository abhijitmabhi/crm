<template>
  <b-overlay :show="loading">
    <b-card>
      <div v-if="!isLocationEmpty">
        <p class="text-bold text-lg text-md">
          Company name:
        </p>
        <div class="mt-n2 pb-1">{{ location.name }}
          <span @click="copyNameToClipboard">
            <i class="fas fa-copy pl-2 fa-lg" id="button-2"></i>
          </span>
        </div>
        <b-tooltip target="button-2" title="Copied!" triggers="focus" :show.sync="showCopiedName"></b-tooltip>

        <p class="text-md text-bold text-lg">
          Company address:
        </p>
        <div class="mt-n2">
          {{ location.address + ', ' + location.postcode + ' ' + location.city }}
          <span @click="copyAddressToClipboard">
          <i class="fas fa-copy pl-2 fa-lg" id="button-1"></i>
        </span>
          <b-tooltip target="button-1" title="Copied!" triggers="focus" :show="showCopiedAddress"></b-tooltip>
        </div>
      </div>

      <b-modal id="modal-1" cancel-variant="success" @ok="reportLocationProblem()">
        <h5>Are you sure that you want to report a problem?</h5>
      </b-modal>

      <div v-if="isLocationEmpty">
        <h3>Well done! There are no more search terms to scrape. You're done for the day
          <i class="fa far fa-smile pl-2 locationEmptyButton"></i>
        </h3>
      </div>

      <div class="form-group mt-3">
        <label class="control-label" for="textarea-searchTerms">Search term textarea</label>
        <b-form-textarea
            id="textarea-searchTerms"
            placeholder="Paste here"
            rows="8"
            v-model="unparsedSearchTerms"
        ></b-form-textarea>
      </div>
      <div class="form-group float-right">
        <b-button type="submit" class="btn btn-danger" @click="hideTooltip" v-b-modal.modal-1>Report problem for the
          current company
        </b-button>
        <b-button type="submit" class="btn btn-success" @click="storeSearchTerms">Save search terms</b-button>
      </div>
      <div class="row">
        <div class="col">
          <b-alert variant="danger" :show="showErrorAlert">{{ errorAlertMessage }}</b-alert>
        </div>
      </div>
    </b-card>
  </b-overlay>
</template>

<script>
export default {
  name: "KeywordScrapingComponent",
  props: {
    location: {
      type: Array
    }
  },
  data() {
    return {
      isLocationEmpty: this.isObjectEmpty(this.location),
      showCopiedAddress: false,
      showCopiedName: false,
      unparsedSearchTerms: "",
      showErrorAlert: false,
      errorAlertMessage: "",
      loading: false
    }
  },
  methods: {
    isObjectEmpty: function (obj) {
      return obj == null;
    },

    copyAddressToClipboard: function () {
      if (this.showCopiedName)
        this.showCopiedName = false;
      this.showCopiedAddress = !this.showCopiedAddress;
      this.copyToClipboard(this.location.address);
    },
    copyNameToClipboard: function () {
      if (this.showCopiedAddress)
        this.showCopiedAddress = false;
      this.showCopiedName = !this.showCopiedName;
      this.copyToClipboard(this.location.name);
    },
    copyToClipboard: function (value) {
      var tempInput = document.createElement("input");
      tempInput.value = value;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand("copy");
      document.body.removeChild(tempInput);
    },
    reportLocationProblem: function () {
      this.loading = true;
      const response = axios.post(
          "/api/location/" + this.location.id + "/reportProblem",
          {
            locationName: this.location.name,
            locationAddress: this.location.address,
            locationZip: this.location.postcode,
            locationId: this.location.id,
          })
          .then(() => {
            window.location.reload();
          })
          .catch((error) => {
            this.showErrorAlert = true;
            this.errorAlertMessage = error.response.data.message;
            this.loading = false;
          });
    },

    hideTooltip: function () {
      this.showCopiedAddress = false;
      this.showCopiedName = false;
    },

    storeSearchTerms: function () {
      if (this.unparsedSearchTerms) {
        this.loading = true;
        const response = axios.post(
            "/api/location/" + this.location.id + "/scraping-results",
            {unparsedSearchTerms: this.unparsedSearchTerms})
            .then(() => {
              window.location.reload();
            })
            .catch((error) => {
              this.showErrorAlert = true;
              this.errorAlertMessage = error.response.data.message;
              this.loading = false;
            });
      } else {
        this.showErrorAlert = true;
        this.errorAlertMessage = "The input field is empty!";
      }
    }
  }
}
</script>

<style scoped>
input {
  background: none;
  border: none;
}

i {
  color: #919aa1;
  cursor: pointer;
}

.locationEmptyButton {
  font-size: 36px;
  font-weight: 400;
}
</style>