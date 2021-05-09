<template>
  <b-overlay
      :show="showLoading"
  >
    <div class="card card-primary">
      <div class="card-body">
        <check-location-citations
            :location = "location"
            @showLoadingSpinner="toggleShow"
        >
        </check-location-citations>
      </div>
    </div>
  </b-overlay>
</template>

<script>
export default {
  name: "location-citations-spinner",
  props: {
    location: {
      type: Object,
      required: true,
      default: null,
    },
  },
  data() {
    return {
      showLoading: false
    };
  },
  methods: {
    toggleShow: function() {
      this.showLoading = !this.showLoading;
    }
  }
}
</script>
