<template>
  <b-card class="mb-0">
    <div class="mb-4" v-if="1 < locationOptions.length">
      <v-select
          id="locations"
          name="locations"
          :options="locations"
          label="name"
          placeholder="Location auswählen"
          v-model="selectedLocationId"
          :reduce="location => location.id"
          @input="locationIdChanged"
      ></v-select>
     <!-- <select2
          v-model="selectedLocationId"
          @input="locationIdChanged"
      >
        <option
            v-for="location in locations"
            :value="location.id"
            v-text="location.name"
            :key="`${_uid}_location_option_${location.id}`"
        />
      </select2>-->
    </div>
    <div class="mb-4" v-else>
      <h5>{{ selectedLocationObj.name }}</h5>
    </div>
    <b-button-group>
      <b-button
          v-for="(timeframe, i) in timeframes"
          @click.prevent="selectedTimeFrameChanged(timeframe)"
          :pressed="selectedTimeframe === timeframe.value"
          :key="`timeframe_${i}`"
          v-text="timeframe.label"
      />
    </b-button-group>
  </b-card>
</template>

<script>
export default {
  name: "locationTimeFramePicker",

  props: {
    locations: {type: Array, required: true},
  },

  data() {
    return {
      selectedLocationId: this.locations[0].id,
      selectedTimeframe: "yesterday",
      timeframes: [
        {value: "yesterday", label: "Gestern"},
        {value: "seven-days", label: "Letzte Woche"},
        {value: "thirty-days", label: "Letzter Monat"},
        {value: "three-months", label: "Letztes Quartal"},
      ],
    }
  },

  methods: {
    locationIdChanged: function() {
      this.$emit('locationIdChanged', this.selectedLocationId);
    },
    selectedTimeFrameChanged: function(timeframe) {
      this.selectedTimeframe = timeframe.value;
      this.$emit('selectedTimeFrameChanged', this.selectedTimeframe);
    }
  },

  computed: {
    locationOptions() {
      return this.locations.map(({id, name}) => {
        return {
          value: id,
          text: name,
        };
      });
    },
  },
  selectedLocationObj() {
    return this.locations.find(location => this.selectedLocationId == location.id);
  },

}
</script>

<style scoped>

</style>