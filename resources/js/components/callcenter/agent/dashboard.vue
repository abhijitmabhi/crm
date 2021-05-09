<template>
  <div>
    <b-card>
      <b-row>
        <b-col>
          <h3>Offene Wiedervorlagen</h3>
          <agent-recall-list :user-id="callagentId"/>
        </b-col>
      </b-row>
    </b-card>
    <b-card class="mt-4">
      <b-row>
        <b-col>
          <h3>Termin Erforderlich</h3>
          <agent-appointment-needed-list :user-id="callagentId"/>
        </b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  props: {
    callagentId: {
      required: true,
      type: String | Number,
    },
  },
};
</script>