<template>
  <div>
    <b>{{ name }}</b><br>
    <span>{{ city }}, {{ street }}</span><br>
    <span>Tel: {{ phone }}</span><br>
    <a :href="btnLink" class="btn btn-white border font-weight-bolder border-secondary">Weiter</a>
  </div>
</template>
<script>
export default {
  props: {
    name: String,
    phone: String,
    city: String,
    street: String,
    btnLink: String
  },
}
</script>