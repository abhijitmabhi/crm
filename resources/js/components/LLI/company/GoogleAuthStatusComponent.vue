<template>
  <div>
    <i v-if="hasAuth" class="fas fa-check text-success h4 mb-0"></i>
    <a
        v-else-if="!hasAuth && !showLoading"
        class="btn btn-white border font-weight-bolder border-secondary"
        :href="this.route('company.google.auth', companyId)"
        target="_blank"
        @click="onClickConnect"
    >Verbinden</a>
    <b-spinner v-if="showLoading" small></b-spinner>
  </div>
</template>

<script>
export default {
  props: {
    hasAuth: {
      type: Boolean,
      default: null
    },
    companyId: {
      type: Number | String,
      required: true
    }
  },
  data() {
    return {
      showLoading: false
    }
  },
  methods: {
    onClickConnect() {
        this.showLoading = true;
    }
  }
};
</script>