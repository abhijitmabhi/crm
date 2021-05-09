<template>
  <div>
    <b-overlay :show="busy">
      <FullCalendar class="mb-2"
                    :options="calendarOptions"
      />
    </b-overlay>
    <div class="d-flex justify-content-center">
      <div class="d-flex flex-column flex-md-row justify-content-between">
        <div
            v-for="{ name, color } in eventTypes"
            :key="name"
            class="cc-pill text-white text-center pt-1 pr-2 pb-1 pl-2 rounded"
            :style="`background-color: ${color};`"
        >{{ name }}
        </div>
      </div>
    </div>
    <b-modal v-model="showInfo" hide-footer size="xl">
      <appointment-info :event="displayAppointment" :owner="displayOwner"/>
    </b-modal>
    <lead-appointment-info-modal
        v-if="showLeadAppointment"
        :lead-id="leadId"
        :appointment="displayAppointment"
    ></lead-appointment-info-modal>
    <b-modal v-model="showLeadFormHistory" id="showLeadFormHistory" size="xl" hide-footer>
      <lead-form-history
          v-if="leadId"
          :appointment-id="displayAppointment.id"
          :storno="true"
          :timer="false"
          :leadId="leadId"
          in-modal
      />
    </b-modal>
  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import deLocale from "@fullcalendar/core/locales/de";

export default {
  components: {
    FullCalendar,
  },
  props: {
    expertId: Number | String,
    leadToOpen: Number | String,
    config: Object,
    defaultView: {
      type: String,
      default: "timeGridWeek",
    },
    header: {
      type: Object,
      default() {
        return {
          left: "title",
          center: "timeGridWeek dayGridMonth dayGrid",
          right: "today prev,next",
        };
      },
    },
    goTo: {
      type: Date,
      default: null,
    }
  },
  watch: {
    expertId(newId, oldId) {
      if (history.pushState) {
        var newurl =
            window.location.protocol +
            "//" +
            window.location.host +
            window.location.pathname +
            `?user=${newId}`;
        window.history.pushState({path: newurl}, "", newurl);
      }
      this.fetchData();
    },
    startDate() {
      this.fetchData();
    }
  },
  data() {
    return {
      events: {
        type: Array,
        default() {
          return {
            id: 'a',
            title: 'my event',
            start: '2020-10-10'
          }
        }
      },
      startDate: null,
      endDate: null,
      busy: false,
      displayAppointment: null,
      displayOwner: null,
      eventTypes: [
        {name: "Termin bei Lead", color: "#db0630"},
        {name: "Privater Termin", color: "#32bb60"},
        {name: "Abgesagter Termin", color: "#cdcdcd"},
      ],
      showEdit: false,
      showInfo: false,
      showLeadAppointment: false,
      showLeadFormHistory: false,
      leadId: null,
      locale: "de",
      locales: [deLocale],
      calendarOptions: {
        headerToolbar: this.header,
        plugins: [dayGridPlugin, timeGridPlugin],
        initialView: "timeGridWeek",
        eventClick: this.eventClickHandler,
        events: null,
        slotMinTime: "07:00:00",
        slotMaxTime: "21:00:00",
        locale: "de",
        locales: [deLocale],
        ref: "calendar",
        eventDisplay: "block",
        displayEventTime: false,
        height: "auto",
        allDaySlot: false,
        buttonText: {
          dayGrid: "Tag",
        },
        lazyFetching: true,
        datesSet: (dateInfo) => {
          this.startDate = dateInfo.startStr
          this.endDate = dateInfo.endStr
        }
      },

    };
  },
  methods: {
    eventClickHandler(event) {
      const {id, start, end, title} = event.event;
      const {invitees, attendees, isDeleted, body} = event.event._def.extendedProps;
      this.displayAppointment = {id, start, end, body, invitees, attendees, isDeleted};
      this.displayOwner = event.event._def.extendedProps.owner;
      if (event.event._def.extendedProps.leadId) {
        this.leadId = `${event.event._def.extendedProps.leadId}`;
        this.showLeadAppointment = true;
        this.$bvModal.show('leadAppointmentInfoModal');
      } else if (event.event._def.extendedProps.invitation) {
        this.attend(event.event._def.extendedProps.invitation);
      } else {
        this.showInfo = true;
      }
    },
    async attend(id) {
      const ok = await this.$bvModal.msgBoxConfirm("Termin bestätigen?", {
        okVariant: "success",
        okTitle: "Ja",
        cancelTitle: "Nein",
        cancelVariant: "danger",
      });
      if (ok) {
        await axios.post("/api/users/" + this.expertId + "/appointments/" + id + "/attend");
        try {
          await this.fetchData();
        } catch (e) {
          this.$bvToast.toast("Nur der eingelandene Nutzer kann Termine bestätigen", {
            title: "Fehler",
            variant: "danger",
            autoHideDelay: 7000,
            solid: true,
          });
        }
      }
    },
    async fetchData() {
      this.busy = true;
      const response = await axios.get("/api/users/" + this.expertId + "/appointments", {
        params: {
          startTime: this.startDate,
          endTime: this.endDate
        }
      });
      this.events = response.data;
      this.calendarOptions.events = response.data;
      this.busy = false;
    },
  },
  mounted() {
    this.fetchData();
    if (this.$props["leadToOpen"]) {
      this.leadId = this.leadToOpen;
    }
    if (this.goTo) {
      this.$refs.calendar.getApi().gotoDate(this.goTo);
    }
  }
};
</script>

<style lang="scss" scoped>
.cc-pill {
  margin-bottom: 5px;

  &:last-child {
    margin-bottom: 0;
  }
}

@media (min-width: 768px) {
  .cc-pill {
    margin-left: 5px;
    margin-right: 5px;
    margin-bottom: 0;

    &:first-child {
      margin-left: 0;
    }

    &:last-child {
      margin-right: 0;
    }
  }
}
</style>
