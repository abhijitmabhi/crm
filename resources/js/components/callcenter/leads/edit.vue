<template>
    <form ref="form" @submit="submit" class="w-100">
        <table v-if="actual_lead" class="table table-sm table-borderless table-responsive">
            <tbody>
                <tr>
                    <td class="w-25">SAM</td>
                    <td>
                        <b-overlay :show="fetchingExpert">
                            <select2-ajax
                                data-url="/api/users"
                                :search-callback="expertSearchCallback"
                                :result-callback="expertResultCallback"
                                @select="updateExpert"
                            >
                                <option v-if="actual_expert" :value="actual_expert.id" selected>{{
                                    actual_expert.name
                                }}</option>
                            </select2-ajax>
                        </b-overlay>
                    </td>
                </tr>
                <tr>
                    <td class="w-25">Status</td>
                    <td>
                        <div :class="leadStatusClasses">
                            <select2 @select="updateLeadStatus" :value="leadStatus">
                                <option
                                    v-for="leadStatus in leadStatusOptions"
                                    :key="leadStatus"
                                    :value="leadStatus"
                                    >{{ leadStatus }}</option
                                >
                            </select2>
                        </div>
                        <div v-if="showExpertStatus">
                            <select2 :options="expertStatusOptions" @select="updateExpertStatus">
                                <option
                                    v-for="expertStatus in expertStatusOptions"
                                    :key="expertStatus"
                                    :value="expertStatus"
                                    >{{ expertStatus }}</option
                                >
                            </select2>
                        </div>
                    </td>
                </tr>
                <tr v-if="![1, 6].includes(parseInt(form.status))">
                    <td>{{ closedUntilReason }}</td>
                    <td>
                        <flat-pickr
                            v-if="actual_lead"
                            class="form-control"
                            v-model="form.closed_until"
                            :config="flatpickr_cfg"
                        ></flat-pickr>
                        <p v-if="missing.includes('closed_until')" class="mb-0">
                            <small class="text-danger">Bitte ein Datum angeben!</small>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>Branche</td>
                    <td>
                        <div>
                            <lead-category @select="updateCategory" :default="actual_lead.category">
                                <option :value="actual_lead.category">{{
                                    actual_lead.category
                                }}</option>
                            </lead-category>
                        </div>
                        <div>
                            <b-input
                                v-model="form.sub_category"
                                placeholder="Fachrichtung eingeben"
                            />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Anschrift:</td>
                    <td>
                        <p class="mb-1">
                            <b-input-group>
                                <p slot="prepend" class="input-group-text" style="width: 82px">
                                    Name
                                </p>
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="form.company_name"
                                    placeholder="Unternehmen"
                                />
                            </b-input-group>
                        </p>
                        <p v-if="missing.includes('company_name')" class="mb-1">
                            <small class="text-danger">Bitte Unternehmensnamen angeben!</small>
                        </p>
                        <p class="mb-1">
                            <b-input-group>
                                <p slot="prepend" class="input-group-text" style="width: 82px">
                                    Straße
                                </p>
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="form.street"
                                    placeholder="Straße"
                                    style="width: 82px"
                                />
                            </b-input-group>
                        </p>
                        <p v-if="missing.includes('street')" class="mb-1">
                            <small class="text-danger">Bitte Straße angeben!</small>
                        </p>
                        <p class="mb-1">
                            <b-input-group>
                                <p slot="prepend" class="input-group-text" style="width: 82px">
                                    PLZ
                                </p>
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="form.zip"
                                    placeholder="PLZ"
                                    style="width: 82px"
                                />
                            </b-input-group>
                        </p>
                        <p v-if="missing.includes('zip')" class="mb-1">
                            <small class="text-danger">Bitte PLZ angeben!</small>
                        </p>
                        <p class="mb-0">
                            <b-input-group>
                                <p slot="prepend" class="input-group-text" style="width: 82px">
                                    Stadt
                                </p>
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="form.city"
                                    placeholder="Stadt"
                                    style="width: 82px"
                                />
                            </b-input-group>
                        </p>
                        <p v-if="missing.includes('city')" class="mb-1">
                            <small class="text-danger">Bitte Ort angeben!</small>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>Ansprechpartner:</td>
                    <td>
                        <input
                            type="text"
                            v-model="form.contact_name"
                            placeholder="Name &amp; Vorname Geschäftsführer"
                            class="form-control"
                        />
                    </td>
                    <p v-if="missing.includes('contact_name')" class="mb-1">
                        <small class="text-danger">Bitte einen Ansprechpartner angeben!</small>
                    </p>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input
                            type="text"
                            v-model="form.additional_contact"
                            placeholder="Weitere Ansprechpartner"
                            class="form-control"
                        />
                    </td>
                </tr>
                <tr>
                    <td>Telefon:</td>
                    <td>
                        <input type="tel" v-model="form.phone1" class="form-control" />
                        <p v-if="missing.includes('phone1')" class="mb-1">
                            <small class="text-danger">Bitte eine Telefonnummer angeben!</small>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input
                            type="tel"
                            v-model="form.phone2"
                            placeholder="Alternative Nummer"
                            class="form-control"
                        />
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>
                        <input
                            type="email"
                            v-model="form.email"
                            placeholder="Email"
                            class="form-control"
                        />
                    </td>
                </tr>
                <tr>
                    <td>Impressum:</td>
                    <td>
                        <input
                            type="url"
                            v-model="form.website"
                            placeholder="Impressum URL"
                            class="form-control"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mb-4">
            <button v-if="!hideAbort" class="btn btn-white" @click="emitAbort">Abbrechen</button>
            <button
                v-if="showInvalidate"
                class="btn btn-danger"
                @click.prevent="$refs.confirmInvalid.show()"
            >
                Lead ungültig
            </button>
            <!--<input
                type="reset"
                value="Zurücksetzen"
                :isHidden="showResetButton"
                class="btn btn-white"
                :disabled="!changed"
            />-->
            <input
                type="submit"
                :value="saveButtonText"
                class="btn btn-success"
                :disabled="!changed"
            />
        </div>
        <b-modal
            ref="confirmInvalid"
            :title="`${form.company_name} für ungültig erklären`"
            hide-footer
        >
            <p>Wollen Sie wirklich {{ form.company_name }} für ungültig erklären?</p>
            <p>
                <label for="invalidate-confirm font-weight-normal">
                    "löschen" zur Bestätigung eingeben:
                </label>
                <b-input
                    id="invalidate-confirm"
                    v-model="invalidateConfirmation"
                    placeholder="hier löschen eingeben"
                />
            </p>
            <p class="d-flex justify-content-end">
                <b-button @click.prevent="$refs.confirmInvalid.hide()">Abbrechen</b-button>
                <b-button
                    style="min-width: 32px"
                    @click.prevent="invalidateLead"
                    :disabled="!invalidConfirmed"
                    variant="danger"
                    >Ok</b-button
                >
            </p>
        </b-modal>
    </form>
</template>

<script>
import { clone, isEqual } from "lodash";
export default {
    data() {
        return {
            invalidateConfirmation: "",
            fetchingExpert: false,
            actual_expert: null,
            flatpickr_cfg: {
                enableTime: true,
            },
            actual_lead: null,
            form: {},
            original_form: {},
            leadStatusOptions: [
                "Offen",
                "Nicht erreicht",
                "Wiedervorlage",
                "Kein Interesse",
                "Termin vereinbart",
                "Blacklist",
                "Konkurrenzschutz",
            ],
            expertStatusOptions: [
                "SAM hat den Termin nicht bestätigt.",
                "SAM hat den Termin bestätigt.",
                "SAM hat den Termin abgelehnt.",
            ],
            missing: [],
        };
    },
    watch: {
        lead() {
            this.updateLead();
        },
    },
    props: {
        lead: Object,
        expert: Object,
        edit: Boolean | String,
        saveButtonText: { type: String, default: "Speichern" },
        hideAbort: {
            type: Boolean,
            default: false,
        },
        showInvalidate: {
            type: Boolean,
            default: true,
        },
    },
    computed: {
        invalidConfirmed() {
            return "löschen" === this.invalidateConfirmation.toLowerCase();
        },
        changed() {
            return !isEqual(this.form, this.original_form);
        },
        leadStatus() {
            var status = parseInt(this.lead.status) - 1;
            if (status < this.leadStatusOptions.length) {
                return this.leadStatusOptions[status];
            }
            return null;
        },
        leadExpertStatus() {
            var status = parseInt(this.lead.expert_status);
            if (status < this.expertStatusOptions.length) {
                return this.expertStatusOptions[status];
            }
            return null;
        },
        leadStatusClasses() {
            return this.form.status === 5 ? "mb-1" : "";
        },
        closedUntilReason() {
            if (this.form.status == 5) {
                return "Termin";
            }
            return "Wieder verfügbar";
        },
        showExpertStatus() {
            return this.form.status == 5;
        },
    },
    methods: {
        setupForm() {
            this.original_form.reason = "CORRECTION";
            this.original_form.company_name = this.actual_lead.company_name;
            this.original_form.category = this.actual_lead.category;
            this.original_form.closed_until = this.actual_lead.closed_until;
            this.original_form.status = this.actual_lead.status;
            this.original_form.expert_status = this.actual_lead.expert_status;
            this.original_form.street = this.actual_lead.street;
            this.original_form.zip = this.actual_lead.zip;
            this.original_form.city = this.actual_lead.city;
            this.original_form.contact_name = this.actual_lead.contact_name;
            this.original_form.additional_contact = this.actual_lead.additional_contact;
            this.original_form.phone1 = this.actual_lead.phone1;
            this.original_form.phone2 = this.actual_lead.phone2;
            this.original_form.email = this.actual_lead.email;
            this.original_form.website = this.actual_lead.website;
            this.form = clone(this.original_form, true);
        },
        updateLead() {
            if (!this.lead) return;
            if (typeof this.lead != "object") {
                return axios
                    .get("/api/leads/" + this.lead)
                    .then(response => (this.actual_lead = response.data))
                    .then(() => this.setupForm);
            } else {
                this.actual_lead = this.lead;
                this.setupForm();
                return Promise.resolve();
            }
        },
        updateLeadStatus(status) {
            this.form.status = this.leadStatusOptions.indexOf(status) + 1;
        },
        updateExpert(expertId) {
            this.$set(this.form, "expert_id", expertId);
        },
        updateExpertStatus(status) {
            this.$set(this.form, "expert_status", this.expertStatusOptions.indexOf(status));
        },
        updateCategory(select) {
            this.$set(this.form, "category", select);
        },
        verifyData() {
            const needed = ["company_name", "contact_name", "phone1"];
            const wrong = [];
            if (this.form.status != 1) {
                if (!this.form.closed_until) {
                    wrong.push("closed_until");
                }
            }
            needed.forEach(need => {
                if (!this.form[need]) {
                    wrong.push(need);
                }
            });
            if (this.form.status === 5 && !this.form.expert_status) {
                this.form.expert_status = 0;
            }
            this.missing = wrong;
            return wrong.length === 0;
        },
        submit(e) {
            e.preventDefault();
            if (this.verifyData()) {
                this.$emit(
                    "update_lead",
                    axios
                        .put(`/api/leads/${this.actual_lead.id}`, this.form)
                        .then(() => this.$emit("lead_updated"))
                );
            }
        },
        emitAbort(e) {
            e.preventDefault();
            this.form = clone(this.original_form);
            this.$emit("abort");
        },
        expertSearchCallback(param) {
            return {
                role: "Expert",
                name: param.term,
                callagent: this.user_id,
            };
        },
        expertResultCallback(result) {
            return {
                results: result.data.map(user => {
                    return {
                        id: user.id,
                        text: user.name,
                    };
                }),
            };
        },
        fetchExpert() {
            this.fetchingExpert = true;
            if (this.expert && this.expert.id == this.lead.expert_id) {
                this.actual_expert = this.expert;
                this.fetchingExpert = false;
                return Promise.resolve();
            }
            return axios
                .get("/api/users/" + this.lead.expert_id)
                .then(response => (this.actual_expert = response.data))
                .finally(() => (this.fetchingExpert = false));
        },
        invalidateLead(e) {
            this.invalidateConfirmation = "";
            this.$refs.confirmInvalid.hide();
            this.$emit(
                "update_lead",
                axios
                    .put(`/api/leads/${this.actual_lead.id}`, {
                        reason: "CORRECTION",
                        status: 8,
                    })
                    .then(() => this.$emit("lead_updated"))
            );
        },
    },
    mounted() {
        this.updateLead().then(this.fetchExpert);
    },
};
</script>
