<template>
    <b-modal
        :id="dialogId"
        dialog-class="modal-xxl d-flex justify-content-center"
        footer-class="d-none"
        :centered="true"
    >
      <b-img
          id="largeImage"
          ref="largeImage"
          :fluid="true"
          :src="largeImageUrl"
      ></b-img>
    </b-modal>
</template>

<script>
export default {
  name: "showLargeImage",
  props: {
    largeImageUrl: {
      type: String,
      required: true
    },
    imageId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      dialogId: `largeImageModal${this.imageId}`
    }
  },
}
</script>
