<template>
    <b-overlay :show="isUpdating">
        <h3 class="text-primary">
            <p>
                <i class="fas fa-heart-broken"></i>
                Nicht verkauft
            </p>
        </h3>
        <b-row>
            <b-col>
                <a
                    href="#"
                    class="d-flex align-items-center border p-4"
                    :class="isAppointment ? ' border-primary text-primary' : 'text-dark'"
                    @click.prevent="isAppointment = true"
                >
                    <i
                        class="fas fa-calendar-alt h3 mb-0 mr-3"
                        :class="isAppointment ? 'text-primary' : ''"
                    ></i>
                    Neuen Termin ausmachen
                </a>
            </b-col>
            <b-col>
                <a
                    href="#"
                    class="d-flex align-items-center border p-4"
                    :class="isAppointment ? 'text-dark' : 'border-primary text-primary'"
                    @click.prevent="isAppointment = false"
                >
                    <i
                        class="fas fa-calendar-alt h3 mb-0 mr-3"
                        :class="isAppointment ? '' : 'text-primary'"
                    ></i>
                    Wiedervorlage erstellen
                </a>
            </b-col>
        </b-row>
        <p></p>
        <b-row>
            <b-col>
                <p>
                    <label for="sale_date">Datum auswählen</label>
                    <b-datepicker
                        v-model="date"
                        :state="dateOk"
                        placeholder="Datum auswählen"
                        locale="de"
                    />
                </p>
            </b-col>
            <b-col>
                <p>
                    <label for="sale_time">Uhrzeit auswählen</label>
                    <b-timepicker
                        v-model="time"
                        :state="timeOk"
                        minutes-step="15"
                        placeholder="Uhrzeit auswählen"
                        locale="de"
                    />
                </p>
            </b-col>
        </b-row>
        <p>
            <label for="sale_comment">Terminausfall</label>
            <b-textarea
                id="sale_comment"
                v-model="comment"
                :placeholder="`Freitext, max. ${actualConfig.max} Zeichen`"
                :state="commentOk"
            />
        </p>
        <div class="d-flex justify-content-end">
            <b-button
                variant="primary"
                v-text="buttonText"
                :disabled="!canSave"
                @click.prevent="updateLead"
            />
        </div>
    </b-overlay>
</template>

<script>

export default {
    data() {
        return {
            isUpdating: false,
            isAppointment: true,
            defaultConfig: {
                min: 0,
                max: 0,
            },
            comment: "",
            date: null,
            time: null,
        };
    },
    props: {
        leadId: {
            type: String | Number,
            required: true,
        },
        config: {
            type: Object,
            required: false,
        },
    },
    computed: {
        buttonText() {
            return this.isAppointment ? "Termin speichern" : "Wiedervorlage speichern";
        },
        canSave() {
            return this.commentOk;
        },
        dateTime() {
            const [hour, minute, second] = this.time.split(":");
            return this.$moment(this.date)
                  .hours(hour)
                  .minutes(minute)
                  .seconds(second);
        },
        dateTimeOk() {
            const now = this.$moment();
            return this.daytime.isAfter(now);
        },
        dateOk() {
            if (!this.date) {
                return null;
            } else {
                return this.$moment(this.date).isAfter(new Date());
            }
        },
        timeOk() {
            if (!this.time) {
                return null;
            } else {
                return true;
            }
        },
        commentOk() {
            let ok = null;
            const commentLength = this.comment.length;
            if (0 < commentLength) {
                const [min, max] = [this.actualConfig.min, this.actualConfig.max];
                if (0 < max) {
                    ok = max >= commentLength;
                }
                if (0 < min) {
                    ok = ok !== false && min >= commentLength;
                }
            }
            return ok;
        },
        actualConfig() {
            if (this.config) {
                return Object.keys(this.defaultConfig).reduce((cfg, item) => {
                    cfg[item] = this.config[item] || this.defaultConfig[item];
                    return cfg;
                }, {});
            }
            return this.defaultConfig;
        },
    },
    methods: {
        updateLead() {
            if (this.isAppointment) {
                this.postUpdate("APPOINTMENT", 5);
            } else {
                this.postUpdate("RECALL", 3);
            }
        },
        postUpdate(reason, status) {
            const endDate = this.dateTime.add("minute", 90);
            this.isUpdating = true;
            Promise.all([
                axios.post(`/api/leads/${this.leadId}/comments`, {
                    lead_id: this.leadId,
                    reason,
                    body: this.comment,
                    date: this.dateTime.toString(),
                    endDate,
                }),
                axios.put(`/api/leads/${this.leadId}`, {
                    status,
                    closed_until: this.dateTime,
                }),
            ])
                .then(() => this.$emit("updated"))
                .finally(() => (this.isUpdating = false));
        },
    },
};
</script>

<style></style>
