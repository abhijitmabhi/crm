<template>
    <div>
        <b-overlay :show="fetchingOptions">
            <b-row>
                <b-col v-if="!onlyExpert" md="6">
                    <select2 @change="selectedExpertId = $event">
                        <option disabled selected>-- SAM auswählen --</option>
                        <option
                            v-for="expert in experts"
                            :key="expert.value"
                            :value="expert.value"
                        >{{ expert.text }}</option>
                    </select2>
                </b-col>
                <b-col md="6">
                    <select2 @change="city = $event">
                        <option value="no-city-selected">Alle Städte</option>
                        <option
                            v-for="city in cities"
                            :key="city.value"
                            :value="city.value"
                        >{{ city.text }}</option>
                    </select2>
                </b-col>
            </b-row>
        </b-overlay>
        <b-overlay :show="fetchingLead">
            <b-row v-if="lead">
                <b-col>
                    <p v-html="remainingLeadsMessage" class="mt-4 h4" />
                </b-col>
            </b-row>
            <b-row v-if="lead" class="mt-4">
                <b-col md="6">
                    <h4>{{ lead.company_name }}</h4>
                    <lead-invalid-edit :lead="lead" @updated="onUpdate" />
                </b-col>
                <b-col md="6">
                    <p>
                        <b-button
                            v-if="leadValidatedUrl"
                            variant="primary"
                            :href="leadValidatedUrl"
                            target="_blank"
                            class="mr-3"
                        >Seite in neuem Tab aufrufen</b-button>
                        <b-button
                            variant="primary"
                            :href="SearchUrl"
                            target="_blank"
                        >Suche in neuem Tab aufrufen</b-button>
                    </p>
                    <iframe :src="iFrameUrl" width="100%" height="900" />
                </b-col>
            </b-row>
            <p v-else class="mt-4 text-center" v-html="emptyLeadsMessage" />
        </b-overlay>
    </div>
</template>

<script>
import LeadInvalidEdit from "./edit";
import { validURL } from "@utils/functions";
export default {
    components: { LeadInvalidEdit },
    data() {
        return {
            cities: [],
            city: "",
            experts: [],
            selectedExpertId: null,
            fetchingCities: false,
            fetchingExperts: false,
            fetchingLead: false,
            lead: null,
            relevantStatus: [1],
            remaining: null,
        };
    },
    props: {
        onlyExpert: {
            type: String | Number,
            default: false,
        },
    },
    watch: {
        selectedExpertId() {
            this.city = "";
            this.fetchLead();
            this.fetchCities();
        },
        city() {
            this.fetchLead();
        },
    },
    computed: {
        fetchingOptions() {
            return this.fetchingCities || this.fetchingExperts;
        },
        leadValidatedUrl() {
            const website = this.lead.website;
            if (this.lead && validURL(website)) {
                if (/https?:\/\//.test(website)) {
                    return website;
                }
                return "https://" + website;
            }
            return false;
        },
        SearchUrl() {
            const query = ["company_name", "street", "zip", "city"]
                .map(key => this.lead[key])
                .join("+")
                .replace(" ", "+");
            return `https://google.com/search?q=${query}`;
        },
        iFrameUrl() {
            if (this.leadValidatedUrl) {
                return this.leadValidatedUrl.replace("http://", "https://");
            }
            return this.SearchUrl + "&igu=1";
        },
        remainingLeadsMessage() {
            if (0 < this.remaining) {
                let text = `noch <span class="text-danger">${this.remaining}</span> Leads`;
                if (this.city && "no-city-selected" !== this.city) {
                    text += ` in ${this.city}`;
                }
                return text;
            }
            return "";
        },
        emptyLeadsMessage() {
            if (this.selectedExpertId) {
                const fittingExperts = this.experts.filter(
                    expert => this.selectedExpertId == expert.value
                );
                if (fittingExperts.length) {
                    const expertName = fittingExperts[0].text;
                    if (this.city && "no-city-selected" !== this.city) {
                        return `<i class="fas fa-check text-success"></i> keine ungültigen Einträge für ${expertName} in ${this.city} vorhanden`;
                    }
                    return `<i class="fas fa-check text-success"></i> keine ungültigen Einträge für ${expertName} vorhanden`;
                }
            } else {
                return '<i class="fas fa-arrow-up text-primary"></i> Bitte einen SAM auswählen <i class="fas fa-arrow-up text-primary"></i>';
            }
        },
    },
    methods: {
        fetchLead() {
            if (!this.selectedExpertId) {
                this.lead = null;
                return;
            }
            this.fetchingLead = true;
            const params = {
                expert: this.selectedExpertId,
                contact_name: "",
                status: this.relevantStatus,
                per_page: 1,
                sort_by: "website",
                sort_direction: "desc",
            };
            if (this.city && "no-city-selected" !== this.city) {
                params.city = this.city;
            }
            return axios
                .get("/api/leads", { params })
                .then(response => {
                    const leads = response.data.data;
                    this.lead = null;
                    if (leads.length) {
                        this.lead = leads[0];
                        this.remaining = response.data.meta.total;
                    } else {
                        this.lead = null;
                    }
                })
                .catch(response => {})
                .finally(() => (this.fetchingLead = false));
        },
        fetchCities() {
            this.fetchingCities = true;
            const params = {
                contact_name: "",
                status: this.relevantStatus,
            };
            if (this.selectedExpertId) {
                params.expert = this.selectedExpertId;
            }
            return axios
                .get("/api/leads/cities", { params })
                .then(response => {
                    const options = response.data.map(city => {
                        return {
                            value: city.city,
                            text: `${city.city} (${city.invalid_leads})`,
                        };
                    });
                    this.cities = options;
                })
                .finally(() => (this.fetchingCities = false));
        },
        fetchExperts() {
            this.fetchingExperts = true;
            return axios
                .get("/api/users", {
                    params: {
                        role: "expert",
                        per_page: 1000,
                    },
                })
                .then(response => {
                    const filteredExperts = response.data.data.map(expert => {
                        return {
                            value: expert.id,
                            text: expert.name,
                        };
                    });
                    this.experts = filteredExperts;
                })
                .catch(response => {})
                .finally(() => {
                    this.fetchingExperts = false;
                });
        },
        onUpdate(promise) {
            this.fetchLead();
            this.fetchCities();
        },
    },
    mounted() {
        if (this.onlyExpert) {
            this.selectedExpertId = this.onlyExpert;
        } else {
            this.fetchExperts();
        }
    },
};
</script>
