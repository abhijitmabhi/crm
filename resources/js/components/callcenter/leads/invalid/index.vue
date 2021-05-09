<template>
    <b-tabs>
        <b-tab
            :disabled="!noContactNr"
            :active="noContactActive"
            :title="`Kein Kontakt (${noContactNr})`"
        >
            <no-contact />
        </b-tab>
        <b-tab
            :disabled="!noExpertNr"
            :active="noExpertActive"
            :title="`Ungültiger SAM (${noExpertNr})`"
        >
            <no-expert />
        </b-tab>
    </b-tabs>
</template>

<script>
import noContact from "./no-contact";
import noExpert from "./no-expert";
export default {
    components: { noContact, noExpert },
    data() {
        return {
            noContactNr: 0,
            noExpertNr: 0,
            noContactActive: false,
            noExpertActive: false,
        };
    },
    methods: {
        getNoContact() {
            return axios
                .get("/api/leads", {
                    params: { contact_name: "" },
                })
                .then(response => {
                    this.noContactNr = response.data.meta.total;
                    return response;
                });
        },
        getNoExpert() {
            return axios
                .get("/api/leads", {
                    params: { expert: "invalid", per_page: 1 },
                })
                .then(response => {
                    this.noExpertNr = response.data.meta.total;
                    return response;
                });
        },
    },
    mounted() {
        this.getNoContact()
            .then(() => (this.noContactActive = !!this.noContactNr))
            .then(this.getNoExpert)
            .then(() => {
                if (this.noContactActive) {
                    this.noExpertActive = false;
                } else {
                    this.noExpertActive = !!noExpertNr;
                }
            });
    },
};
</script>
