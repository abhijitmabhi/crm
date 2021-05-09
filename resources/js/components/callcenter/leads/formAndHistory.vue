<template>
    <b-tabs nav-wrapper-class="bg-light-grey" @input="rerenderCalendar">
        <b-tab title="Lead" class="col">
            <p></p>
            <lead-form
                @appointment="appointmentSaved"
                :appointment-id="appointmentId"
                :lead-id="leadId"
                :in-modal="inModal"
                :timer="timer"
                :show-timer="!!timer"
                :showStorno="storno"
                :show-recall="recall"
                @abort="abort"
            ></lead-form>
        </b-tab>
        <b-tab title="Kommentare" class="col">
            <history :lead-id="leadId" />
        </b-tab>
        <b-tab v-if="expertId" title="Kalender" class="col">
            <calendar v-if="showCalendar" ref="calendar" :expert-id="expertId" />
        </b-tab>
    </b-tabs>
</template>
<script>
import history from "./history";
export default {
    components: { history },
    data() {
        return {
            showCalendar: false,
        };
    },
    props: {
        appointmentId: {
            type: String | Number,
            default: -1,
        },
        inModal: {
            type: Boolean,
            default: false,
        },
        expertId: {
            type: String | Number,
            required: false,
        },
        leadId: {
            type: String | Number,
            required: true,
        },
        timer: {
            required: false,
            default: false,
        },
        storno: {
            type: Boolean,
            default: false,
        },
        recall: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        abort() {
            this.$emit("abort");
        },
        appointmentSaved(warningLevel) {
            this.$emit("appointment", warningLevel);
        },
        rerenderCalendar(newIndex) {
            this.showCalendar = !!(2 == newIndex);
        },
    },
};
</script>
