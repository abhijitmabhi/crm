<template>
    <b-form @submit.prevent="onSubmit" ref="form" novalidate>
        <b-row>
            <b-col>
                <h6 style="color: red"><i class="fas fa-exclamation-circle"></i> Bitte wirklich NUR private Termine erstellen! (z.B. Urlaub, Arzttermin, Feiertag)</h6>
                <p>
                    Termine für Leads müssen <a href="/leads/schedule">hier</a> erstellt werden.
                </p>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <b-select v-model="event.type" :options="eventTypes" />
            </b-col>
        </b-row>
        <b-row class="mt-3">
            <b-col cols="6">
                <flat-pickr
                    v-model="event.event_begin"
                    :config="config"
                    name="event-begin"
                    id="event-begin"
                    placeholder="Beginn des Termins"
                    class="form-control"
                    ref="datepicker"
                />
                <div class="text-danger error-msg">{{ errors.get("event_begin") }}</div>
            </b-col>
            <b-col cols="6">
                <flat-pickr
                    v-model="event.event_end"
                    :config="config"
                    name="event-end"
                    id="event-end"
                    placeholder="Ende des Termins"
                    class="form-control"
                    ref="datepicker"
                />
                <div class="text-danger error-msg">{{ errors.get("event_end") }}</div>
            </b-col>
            <b-col class="mt-3">
                <b-form-textarea
                    maxlength="200"
                    minlength="15"
                    id="textarea"
                    v-model="event.body"
                    placeholder="Termin Beschreibung"
                    rows="2"
                    required
                    max-rows="6"
                ></b-form-textarea>
                <div class="text-danger error-msg">{{ errors.get("body") }}</div>
            </b-col>
            <b-col class="mt-3">
                <select2-ajax
                    :multiple="true"
                    data-url="/api/users"
                    :search-callback="userSearchCallback"
                    :result-callback="userResultCallback"
                    @select="inviteUser"
                    placeholder="Nutzer einladen"
                ></select2-ajax>
            </b-col>
        </b-row>
        <b-button class="float-right my-3" variant="btn btn-primary" type="submit">Termin erstellen</b-button>
    </b-form>
</template>

<style>
.select2-container--default .select2-search--inline .select2-search__field {
    border: unset;
}
.error-msg {
    padding: 5px;
}
</style>

<script>
class Errors {
    constructor() {
        this.errors = {};
    }
    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }
    record(errors) {
        this.errors = errors;
    }
}

export default {
    data() {
        return {
            eventTypes: [
                { value: "general", text: "Allgemeiner Termin" },
                { value: "seminar", text: "Schulung" },
                { value: "networking", text: "Netzwerkveranstaltung" },
                { value: "private", text: "Privater Termin" },
            ],
            selected: [],
            event: {
                type: "general",
                event_begin: null,
                event_end: null,
                body: null,
                invitees: [],
            },
            cachedTime: false,
            errors: new Errors(),
            config: {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            },
        };
    },
    watch: {
        event: {
            deep: true,
            handler(val) {
                if (val.event_begin != this.cachedTime) {
                    let time = this.$moment(val.event_begin);
                    this.cachedTime = val.event_begin;
                    this.event.event_end = time.add(90, "m").format("YYYY-MM-DD HH:mm");
                }
            },
        },
    },
    methods: {
        userSearchCallback(param) {
            return {
                name: param.term,
            };
        },
        userResultCallback(result) {
            let results = result.data
                .filter(user => user.id != this.user)
                .map(user => {
                    return {
                        id: user.id,
                        text: user.name,
                    };
                });
            return { results };
        },
        inviteUser(selected) {
            this.event.invitees = selected;
        },
        onSubmit() {
            axios
                .post(`/api/users/${this.user}/appointments`, this.event)
                .then(response => {
                    this.$emit("appoinmentMade");
                    this.hideModal();
                    window.refreshFromJs();
                })
                .catch(error => {
                    this.errors.record(error.response.data.errors);
                    this.$refs.form.classList.add("was-validated");
                });
        },
        hideModal() {
            this.$root.$emit("bv::hide::modal", this.modal);
        },
    },
    props: {
        user: Number | String,
        modal: String,
    },
};
</script>
