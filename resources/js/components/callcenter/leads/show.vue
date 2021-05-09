<template>
    <table v-if="actualLead" class="table table-sm table-borderless">
        <tbody>
            <tr>
                <td class="w-25">SAM</td>
                <td v-if="expert">{{ expert.name }}</td>
                <td v-else>
                    <b-spinner small />
                </td>
            </tr>
            <tr>
                <td class="w-25">Status</td>
                <td>
                    <p :class="'mb-0' + leadStatusClass">{{ leadStatus }}</p>
                    <p v-if="actualLead.status == '5'" :class="'mb-0' + leadExpertStatusClass">
                        {{ leadExpertStatus }}
                    </p>
                </td>
            </tr>
            <tr v-if="![1, 6].includes(parseInt(actualLead.status))">
                <td>{{ closedUntilReason }}</td>
                <td>{{ actualLead.closed_until }}</td>
            </tr>
            <tr>
                <td>Branche</td>
                <td>
                    <span class="d-block">{{ actualLead.category }}</span>
                    <span v-if="actualLead.sub_category" class="d-block">{{
                        actualLead.sub_category
                    }}</span>
                </td>
            </tr>
            <tr>
                <td>Anschrift:</td>
                <td>
                    <p class="mb-0">{{ actualLead.company_name }}</p>
                    <p class="mb-0">{{ actualLead.street }}</p>
                    <p class="mb-0">{{ actualLead.zip }} {{ actualLead.city }}</p>
                </td>
            </tr>
            <tr>
                <td>Ansprechpartner:</td>
                <td>{{ actualLead.contact_name }}</td>
            </tr>
            <tr v-if="actualLead.additional_contact">
                <td></td>
                <td>{{ actualLead.additional_contact }}</td>
            </tr>
            <tr>
                <td>Telefon:</td>
                <td>
                    <a :href="'tel:' + actualLead.phone1">
                        <i class="fas fa-phone"></i>
                        {{ actualLead.phone1 }}
                    </a>
                </td>
            </tr>
            <tr v-if="actualLead.phone2">
                <td></td>
                <td>
                    <a :href="'tel:' + actualLead.phone2">
                        <i class="fas fa-phone"></i>
                        {{ actualLead.phone2 }}
                    </a>
                </td>
            </tr>
            <tr v-if="actualLead.email">
                <td>Email:</td>
                <td>
                    <a :href="'mailto:' + actualLead.email">
                        <i class="fas fa-envelope"></i>
                        {{ actualLead.email }}
                    </a>
                </td>
            </tr>
            <tr v-if="actualLead.website">
                <td>Impressum:</td>
                <td>
                    <a :href="actualLead.website" target="_blank">
                        <i class="fas fa-link"></i>
                        {{ actualLead.website }}
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    data() {
        return {
            actualLead: null,
            expert: null,
        };
    },
    props: {
        lead: Number | String | Object,
    },
    computed: {
        leadStatusClass() {
            switch (parseInt(this.lead.status)) {
                case 2:
                case 3:
                    return " text-warning";
                case 4:
                case 6:
                    return " text-danger";
                case 5:
                    return " text-success";
                default:
                    return "";
            }
        },
        leadStatus() {
            switch (parseInt(this.lead.status)) {
                case 1:
                    return "Offen";
                case 2:
                    return "Nicht erreicht";
                case 3:
                    return "Wiedervorlage";
                case 4:
                    return "Kein Interesse";
                case 5:
                    return "Termin vereinbart";
                case 6:
                    return "Blacklist";
                case 10:
                  return "Termin notig";
                case 11:
                    return "Konkurrenzschutz";
                default:
                    return null;
            }
        },
        leadExpertStatusClass() {
            switch (parseInt(this.lead.expert_status)) {
                case 0:
                    return " text-warning";
                case 1:
                    return " text-success";
                case 2:
                    return " text-danger";
                default:
                    return "";
            }
        },
        leadExpertStatus() {
            switch (parseInt(this.lead.expert_status)) {
                case 0:
                    return "Der SAM hat den Termin noch nicht bestätigt.";
                case 1:
                    return "Der SAM hat den Termin bestätigt.";
                case 2:
                    return "Der SAM hat den Termin abgelehnt.";
                default:
                    return null;
            }
        },
        closedUntilReason() {
            if (this.lead.status == 5) {
                return "Termin";
            }
            return "Wieder verfügbar";
        },
        header() {
            if (this.lead) return this.lead.company_name;
            return "Lade Lead...";
        },
    },
    methods: {
        updateLead() {
            if (!this.lead) return;
            if (typeof this.lead != "object") {
                return axios
                    .get("/api/leads/" + this.lead)
                    .then(response => (this.actualLead = response.data));
            } else {
                this.actualLead = this.lead;
                return Promise.resolve(null);
            }
        },
        updateExpert() {
            return axios.get("/api/users/" + this.actualLead.expert_id).then(response => {
                this.expert = response.data;
            });
        },
    },
    mounted() {
        this.updateLead().then(this.updateExpert);
    },
};
</script>
