<template>
  <div>
    <h4>{{ this.title }}</h4>
    <div v-for="item in this.data">
      <customer-check-result-item
          class="mt-3"
          :name="item.name || item.company_name"
          :phone="item.phone || item.phone1"
          :city="item.city || ''"
          :street="item.street || item.address || ''"
          :btn-link="buttonUrl(item.id)"
      ></customer-check-result-item>
    </div>
    <p v-if="!this.data.length" class="mt-4">Keine Treffer</p>
  </div>
</template>
<script>
export default {
  props: {
    data: {
      type: Array,
      default: [],
    },
    title: {
      type: String,
      default: "",
    },
    dataType: {
      type: String,
      default: ""
    },
  },
  methods: {
    buttonUrl(id) {
      if (this.dataType === 'company')
        return '/companies/' + id;
      if (this.dataType === 'location')
        return '/companies/' + id + '/locations/' + id;
      if (this.dataType === 'lead')
        return '/callcenter/' + id;
    }
  }
};
</script>
