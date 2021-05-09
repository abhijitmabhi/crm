<template>
    <div>
        <b-row>
            <b-col md="3">Geschäftsführer</b-col>
            <b-col>
                <p>
                    <b-input
                        id="contact_name"
                        v-model="changes.contact_name"
                        placeholder="Vorname Name Geschäftsführer"
                    />
                </p>
                <p>
                    <b-input
                        v-model="changes.additional_contact"
                        placeholder="Vorname Name Weiterer Ansprechpartner"
                    />
                </p>
            </b-col>
        </b-row>
        <b-row>
            <b-col md="3">Impressum</b-col>
            <b-col>
                <p>
                    <b-input-group>
                        <b-input
                            id="imprint"
                            v-model="changes.website"
                            placeholder="Unternehmenswebsite / Impressum"
                        />
                        <b-input-group-append>
                            <b-button :href="changes.website" variant="primary">Seite Ansehen</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </p>
            </b-col>
        </b-row>
        <b-button v-b-toggle.show_details class="border">Details</b-button>
        <b-collapse id="show_details">
            <b-row class="mt-4">
                <b-col md="3">Unternehmensname</b-col>
                <b-col>
                    <p>
                        <b-input v-model="changes.company_name" placeholder="Unternehmensname" />
                    </p>
                </b-col>
            </b-row>
            <b-row>
                <b-col md="3">Branche</b-col>
                <b-col>
                    <p>
                        <lead-category v-model="changes.category">
                            <option
                                v-if="lead.category"
                                :default="lead.category"
                                v-text="lead.category"
                                selected
                            />
                        </lead-category>
                    </p>
                </b-col>
            </b-row>
            <b-row>
                <b-col md="3">Anschrift</b-col>
                <b-col>
                    <p>
                        <b-input v-model="changes.street" placeholder="Straße + Hausnummer" />
                    </p>
                    <b-row>
                        <b-col sm="4">
                            <p>
                                <b-input v-model="changes.zip" placeholder="PLZ" />
                            </p>
                        </b-col>
                        <b-col>
                            <p>
                                <b-input v-model="changes.city" placeholder="Ortname" />
                            </p>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </b-collapse>
        <p class="d-flex justify-content-end">
            <b-button variant="danger" v-b-modal="modalId">Lead ungültig</b-button>
            <b-button
                variant="success"
                class="ml-3"
                @click="updateLead"
                :disabled="!canSave"
            >Speichern</b-button>
        </p>
        <b-modal
            ref="modal"
            :id="modalId"
            :title="`${lead.company_name} für ungültig erklären`"
            hide-footer
        >
            <b-overlay :show="updatingLead">
                <p>Wollen Sie wirklich {{ lead.company_name }} für ungültig erklären?</p>
                <p>
                    <label
                        for="invalidate-confirm font-weight-normal"
                    >"löschen" zur Bestätigung eingeben:</label>
                    <b-input id="invalidate-confirm" v-model="invalidationConfirmation" />
                </p>
                <p class="d-flex justify-content-end">
                    <b-button @click.prevent="hideModal">Abbrechen</b-button>
                    <b-button
                        style="min-width: 32px"
                        @click.prevent="invalidateLead"
                        :disabled="!invalidationConfirmed"
                        variant="danger"
                    >Ok</b-button>
                </p>
            </b-overlay>
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            updatingLead: false,
            invalidationConfirmation: "",
            changes: {
                contact_name: "",
                additional_contact: "",
                website: "",
                company_name: "",
                street: "",
                zip: "",
                city: "",
                category: "",
            },
        };
    },
    props: {
        lead: {
            type: Object,
            required: true,
        },
    },
    watch: {
        modalVisible(newVal, oldVal) {
            if (!newVal) {
                this.invalidationConfirmation = "";
            }
        },
        lead() {
            this.setupForm();
        },
    },
    computed: {
        modalId() {
            return this._uid + "_delete_" + this.lead.id;
        },
        keysChanged() {
            return Object.keys(this.changes).filter(key => this.changes[key] !== this.lead[key]);
        },
        canSave() {
            return this.keysChanged.includes("contact_name");
        },
        invalidationConfirmed() {
            return "löschen" === this.invalidationConfirmation.toLowerCase();
        },
    },
    methods: {
        showModal() {
            this.$refs.modal.show();
        },
        modalHide() {
            this.$refs.modal.hide();
        },
        setupForm() {
            Object.keys(this.changes).forEach(key => {
                this.changes[key] = this.lead[key];
            });
        },
        update(data) {
            this.updatingLead = true;
            const promise = axios.put(`/api/leads/${this.lead.id}`, data);
            this.$emit("updating", promise, data);
            return promise
                .then(() => {
                    this.$refs.modal.hide();
                    this.$emit("updated", data);
                })
                .finally(() => (this.updatingLead = false));
        },
        updateLead(e) {
            return this.update({
                reason: "CORRECTION",
                ...this.keysChanged.reduce((data, change) => {
                    data[change] = this.changes[change];
                    return data;
                }, {}),
            });
        },
        async invalidateLead(e) {
            await this.update({ reason: "CORRECTION", status: 8 });
            this.invalidationConfirmation = "";
        },
    },
    mounted() {
        this.setupForm();
    },
};
</script>
