<template>
    <b-row>
        <b-col v-if="2 > status">
            <b-overlay :show="1 === status">
                <b-form-group>
                    <b-form-file
                        v-model="spreadsheet"
                        accept=".ods, .xlsx"
                        placeholder="Datei (*.ods oder *.xlsx) hier hochladen"
                        drop-placeholder="Datei hier ablegen"
                        browse-text="Suchen"
                        aria-describedby="fileHelp"
                        ref="file"
                        :state="!!spreadsheet"
                    />
                    <p class="mb-0">
                        <small>
                          Der Upload funktioniert am besten, wenn die Dateien nicht zu groß werden (ca 500 Einträge)
                        </small>
                    </p>
                    <p>
                        <small v-if="!spreadsheet" class="text-danger">
                          Bitte eine Datei hochladen!
                        </small>
                    </p>
                    <div class="mt-4">
                        <div id="fileHelp" class="alert alert-primary d-inline">
                            Unbedingt an
                            <a href="/storage/sheets/vorlageKundenImport.xlsx">Vorlage</a> halten!
                        </div>
                    </div>
                </b-form-group>
                <div class="form-group mt-4">
                    <b-button
                        @click.prevent="importCustomers"
                        variant="white"
                        class="border-secondary"
                        :disabled="!spreadsheet"
                        >Hochladen</b-button
                    >
                </div>
            </b-overlay>
        </b-col>
        <b-col v-else-if="2 == status">
            <success-feedback :errors="errors" :success="successNr" />
            <b-button variant="white" class="border-secondary" @click.prevent="reset"
                >Weitere Kunden importieren</b-button
            >
        </b-col>
        <b-col v-else>
            <b-alert :show="true" variant="danger">
                Ein unbekannter Fehler ist aufgetreten. Bitte informieren Sie
                <a href="email:portal@localhero.de">die Administratoren</a>.
            </b-alert>
            <b-button variant="white" class="border-secondary" @click.prevent="reset"
                >Weitere Kunden importieren</b-button
            >
        </b-col>
    </b-row>
</template>

<script>
import SuccessFeedback from "./ImportSuccessFeedback";
export default {
    components: { SuccessFeedback },
    data() {
        return {
            errors: [],
            spreadsheet: null,
            status: 0, // 0 - Open, 1 - loading | 2 - finished | 3 - 500 error
            successNr: 0,
        };
    },
    methods: {
        async importCustomers() {
            const { spreadsheet } = this;
            const form = new FormData();
            form.append("spreadsheet", spreadsheet);
            this.status = 1;
            try {
                const response = await axios.post("/admin/lead/import/blacklist", form);
                this.errors = response.data.errors;
                this.successNr = response.data.success;
                this.status = 2;
            } catch (e) {
                this.status = 3;
            }
        },
        reset() {
            this.errors = [];
            this.spreadsheet = null;
            this.status = 0;
            this.successNr = 0;
        },
    },
};
</script>

<style></style>
