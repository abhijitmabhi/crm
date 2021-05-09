<template>
  <form class="pb-4 mb-4">
    <h5 class="mb-2">Kontakt</h5>
    <div class="row">
      <!--      <div class="col-md-6">-->
      <!--        <b-form-group-->
      <!--            label="Fax"-->
      <!--            label-for="fax"-->
      <!--        >-->
      <!--          <b-form-input-->
      <!--              type="tel"-->
      <!--              name="fax"-->
      <!--              id="fax"-->
      <!--              v-model="contactData.fax"-->
      <!--          />-->
      <!--        </b-form-group>-->
      <!--      </div>-->
      <div class="col-md-6">
        <b-form-group
            label="Email"
            label-for="email"
        >
          <b-form-input
              type="email"
              name="email"
              id="email"
              v-model="contactData.email"
          />
        </b-form-group>
      </div>
      <div class="col-md-6">
        <b-form-group
            label="Webseite"
            label-for="website"
        >
          <b-form-input
              type="url"
              name="website"
              id="website"
              v-model="contactData.website"
          />
        </b-form-group>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <b-form-group
            label="Telefonnummer"
            label-for="phone"
        >
          <b-form-input
              type="tel"
              name="phone"
              id="phone"
              v-model="contactData.phone"
          />
        </b-form-group>
      </div>
      <div class="col-md-6">
        <b-form-group
            label="Handynummer"
            label-for="mobilephone"
        >
          <b-form-input
              type="tel"
              name="mobilephone"
              id="mobilephone"
              v-model="contactData.mobilephone"
          />
        </b-form-group>
      </div>
    </div>
  </form>
</template>
<script>

export default {
  props: {
    contactData: {
      type: Object,
      default: () => {
        return {
          phone: "",
          mobilephone: "",
          fax: "",
          email: "",
          website: "",
        };
      }
    },
  }
};
</script>
