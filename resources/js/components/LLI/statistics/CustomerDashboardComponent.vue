<template>
  <div>
    <b-row>
      <b-col class="mb-4">
        <local-search-index
            class="h-100"
            :locationId="selectedLocationId"/>
      </b-col>
    </b-row>
    <b-row>
      <b-col class="mb-4">
        <location-timeframe-picker
            :locations="locations"
            @locationIdChanged="changeLocationId"
            @selectedTimeFrameChanged="changeTimeFrame"
        >
        </location-timeframe-picker>
      </b-col>
    </b-row>
    <b-row>
      <b-col lg="6" class="mb-4">
        <!--                <keywords-->
        <!--                    class="h-100"-->
        <!--                    :locationId="selectedLocationId"-->
        <!--                />-->
        <source-queries
            class="h-100"
            :locationId="selectedLocationId"
            :companyId="companyId"
            :past-days="pastDays"
        />
      </b-col>
      <b-col lg="6" class="mb-4">
        <competition
            ref="competition"
            class="h-100"
            :locationId="selectedLocationId"
            :companyId="companyId"
            :past-days="pastDays"
        />
      </b-col>
    </b-row>
    <!--        <b-row>-->
    <!--             <b-col lg="6" class="mb-4">-->
    <!--                <visitor-streams class="h-100" />-->
    <!--            </b-col>-->
    <!--            <b-col lg="6" class="mb-4">-->
    <!--                <source-queries-->
    <!--                    class="h-100"-->
    <!--                    :locationId="selectedLocationId"-->
    <!--                    :companyId="companyId"-->
    <!--                    :past-days="pastDays"-->
    <!--                />-->
    <!--            </b-col>-->
    <!--        </b-row>-->
    <b-row>
      <b-col class="mb-4">
        <user-actions
            :locationId="selectedLocationId"
            :companyId="companyId"
            :past-days="pastDays"
        />
      </b-col>
    </b-row>
    <b-row>
      <b-col lg="6" class="mb-4">
        <reputation-management class="h-100"/>
      </b-col>
      <b-col lg="6" class="mb-4">
        <div id="testid"></div>
        <citations class="h-100"/>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Chart from "@general/chart";
import Citations from "./citations";
import Competition from "./keywordsCompetition";
import Keywords from "./keywords";
import LocalSearchIndex from "./localSearchIndexChart";
import ReputationManagement from "./reputation";
import SourceQueries from "./sourceQuerys";
import UserActions from "./userActions";
import VisitorStreams from "./visitorStreams";
import LocationTimeFramePicker from "./LocationTimeFramePicker";

export default {
  components: {
    Chart,
    Citations,
    Competition,
    Keywords,
    LocalSearchIndex,
    ReputationManagement,
    SourceQueries,
    UserActions,
    VisitorStreams,
  },
  data() {
    return {
      selectedLocationId: this.locations[0].id,
      selectedTimeframe: "yesterday"
    };
  },
  props: {
    companyId: {type: String | Number, required: true},
    locations: {type: Array, required: true},
    canEditKeywords: {type: Boolean, default: false},
  },
  methods: {
    changeLocationId: function($locationId) {
      this.selectedLocationId = $locationId;
    },
    changeTimeFrame: function($timeFrame) {
      this.selectedTimeframe = $timeFrame;
    }
  },
  computed: {
    endDate() {
      return this.$moment()
          .subtract(1, "day")
          .format("YYYY-MM-DD");
    },
    startDate() {
      const today = this.$moment();
      switch (this.selectedTimeframe) {
        case "yesterday":
          return today.subtract(1, "day").format("YYYY-MM-DD");
        case "seven-days":
          return today.subtract(1, "week").format("YYYY-MM-DD");
        case "thirty-days":
          return today.subtract(1, "month").format("YYYY-MM-DD");
        case "three-months":
          return today.subtract(3, "month").format("YYYY-MM-DD");
        default:
          return today.subtract(2, "day").format("YYYY-MM-DD");
      }
    },
    pastDays() {
      switch (this.selectedTimeframe) {
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
  },
};

</script>

<style>

</style>