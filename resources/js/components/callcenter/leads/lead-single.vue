<template>
    <div>
        <b-card
            :header="header"
            header-tag="h4"
            header-class="card-title simple-card-header"
        >
            <b-alert :show="updateSuccess" variant="success">Lead erfolgreich geupdated</b-alert>
            <b-alert :show="updateFailure" variant="danger">Lead konnte nicht geupdated werden</b-alert>
            <div v-if="lead">
                <lead-edit
                    v-if="isEdit"
                    :lead="lead"
                    :expert="expert"
                    @update_lead="updateLead"
                    @abort="hideEdit"
                    ref="edit"
                ></lead-edit>
                <lead-show v-else :lead="lead" :expert="expert" ref="show"></lead-show>
            </div>
            <p v-else class="mb-0 text-center">
                <b-spinner label="Lade Lead..."></b-spinner>
            </p>
            <hr />
            <back-button class="mt-4 mb-4"></back-button>
            <button
                v-if="lead && !isEdit"
                class="mt-4 mb-4 ml-2 btn btn-white"
                @click="showEdit"
            >Bearbeiten</button>
        </b-card>
    </div>
</template>

<script>
import _ from "lodash";
export default {
    data() {
        return {
            expert: null,
            lead: null,
            isEdit: false,
            updateSuccess: 0,
            updateFailure: 0
        };
    },
    props: {
        leadId: Number | String,
        edit: Boolean | String
    },
    computed: {
        closedUntilReason() {
            if (this.lead.status == 5) {
                return "Termin";
            }
            return "Wieder verfügbar";
        },
        header() {
            if (this.lead) return this.lead.company_name;
            return "Lade Lead...";
        }
    },
    methods: {
        hideEdit(e) {
            if (e) e.preventDefault();
            this.isEdit = false;
        },
        showEdit(e) {
            if (e) e.preventDefault();
            let expert = this.$refs.show.$data.expert;
            this.expert = {
                id: expert.id,
                text: expert.name
            };
            this.isEdit = true;
        },
        loadLead() {
            this.lead = null;
            return axios
                .get("/api/leads/" + this.leadId)
                .then(response => (this.lead = response.data));
        },
        updateLead(promise) {
            this.isEdit = false;
            promise
                .then(response => {
                    if (response.status === 200) {
                        this.updateSuccess = 8;
                    } else {
                        this.updateFailure = 8;
                    }
                })
                .catch(e => (this.updateFailure = 8))
                .then(() => {
                    this.loadLead();
                });
        }
    },
    mounted() {
        this.isEdit = this.edit === true || this.edit === "true";
        this.loadLead();
    }
};
</script>
