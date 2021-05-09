<template>
  <b-card title="Spreadsheet hochladen">
    <b-card-body v-if="showUpload">
      <expert-dropdown v-if="expertSelect" @expert-selected="updateExpertId"></expert-dropdown>
      <br>
      <div class="form-group">
        <input
            type="file"
            accept=".ods"
            class="form-controll-file"
            aria-describedby="fileHelp"
            ref="file"
            @change="handleFileUpload()"
        />
        <b-form-checkbox
            id="private-lead"
            name="private-lead"
            v-model="privateLead"
            value="1"
            unchecked-value="0"
        >Als privaten Lead importieren</b-form-checkbox>
        <p>
          <small
              v-if="mistakes"
              class="text-danger"
          >Bitte eine Datei anhängen!</small>
        </p>
        <div class="mt-4">
          <div id="fileHelp" class="alert alert-primary d-inline">
            Unbedingt an
            <a href="/storage/sheets/vorlage.ods">Vorlage</a> halten!
          </div>
        </div>
      </div>
      <div class="form-group mt-4">
        <button
            @click="importLeads"
            class="btn btn-white border-secondary"
        >Hochladen</button>
      </div>
    </b-card-body>
    <b-card-body v-else-if="showSpinner">
      <p class="w-100 text-center">
        <b-spinner></b-spinner>
      </p>
    </b-card-body>
    <b-card-body v-else>
      <success-feedback :success="successNr" :errors="errors" :error-file="errorFile"></success-feedback>
      <button class="btn btn-white" @click="activateUpload">Weitere Leads uploaden</button>
    </b-card-body>
  </b-card>
</template>

<script>
import SuccessFeedback from "./SuccessFeedback";
import ExpertDropdown from "./ExpertDropdown";
export default {
    components: { SuccessFeedback, ExpertDropdown },
    data() {
        return {
            experts: [],
            status: 0, // 0 - open | 1 -loading | 2 - finished
            successNr: 0,
            errorFile: null,
            errors: [],
            mistakes: false,
            file: null,
            expertSelect: false,
            selectedExpertId: false,
            privateLead: false,
        };
    },
    props: {
        expertId: Number | String,
    },
    computed: {
        showUpload() {
            return this.status == 0;
        },
        showSpinner() {
            return this.status == 1;
        },
        referer() {
            return document.referrer;
        },
    },
    methods: {
        updateExpertId(id) {
            this.selectedExpertId = id;
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        activateUpload() {
            this.status = 0;
        },
        importLeads() {
            if (!this.file) {
                this.mistakes = true;
                return;
            }
            this.status = 1;
            return axios
                .post("/admin/lead/import", this.collectData())
                .then(response => {
                    this.errorFile = response.data.errorFile;
                    this.errors = response.data.errors;
                    this.successNr = response.data.success;
                })
                .catch(error => {
                    console.error(error);
                    this.errors.push(error.response.data.message);
                    this.status = 0;
                })
                .then(() => (this.status = 2));
        },
        collectData() {
            let formData = new FormData();
            formData.append("spreadsheet", this.file);
            formData.append("expert", this.selectedExpertId);
            formData.append("private-lead", this.privateLead);
            return formData;
        },
    },
    mounted() {
        if (!this.expertId) {
            this.expertSelect = true;
        } else {
            this.selectedExpertId = this.expertId;
        }
    },
};
</script>

<style>
</style>
