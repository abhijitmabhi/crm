<template>
  <div>
    <location-timeframe-picker
        :locations="locations"
        @locationIdChanged="changeLocationId"
        @selectedTimeFrameChanged="changeTimeFrame"
    >

    </location-timeframe-picker>

    <detailed-useraction-chart
        title="Webseitenklicks"
        :locationId="locationId"
        :companyId="companyId"
        :pastDays="pastDays"
    >
    </detailed-useraction-chart>

    <detailed-useraction-chart
        title="Wegbeschreibungen"
        :locationId="locationId"
        :companyId="companyId"
        :pastDays="pastDays"
    >
    </detailed-useraction-chart>

    <detailed-useraction-chart
        title="Anrufe"
        :locationId="locationId"
        :companyId="companyId"
        :pastDays="pastDays"
    >
    </detailed-useraction-chart>
  </div>
</template>

<script>
import LocationTimeFramePicker from "./LocationTimeFramePicker";
import DetailedUserActionChart from "./DetailedUserActionChart";

export default {
  components: {LocationTimeFramePicker, DetailedUserActionChart},
  name: "DetailedUserAction",
  props: {
    locations: {
      type: Array,
      required: true
    },
    companyId: {
      type: Number,
      required: true
    },
  },
  data() {
    return {
      locationId: this.locations[0].id,
      timeFrame: "yesterday"
    }
  },
  methods: {
    changeLocationId: function($id) {
      this.locationId = $id;
    },
    changeTimeFrame: function($timeFrame) {
      this.timeFrame = $timeFrame;
    }
  },
  computed: {
    pastDays() {
      switch (this.timeFrame) {
        case "yesterday":
          return 1;
        case "seven-days":
          return 7;
        case "thirty-days":
          return 30;
        case "three-months":
          return 90;
        default:
          return 1;
      }
    }
  }
}
</script>

<style scoped>

</style>
