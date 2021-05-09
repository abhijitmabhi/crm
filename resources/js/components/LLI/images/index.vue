<template>
  <div class="pb-4">
    <upload-images :store-url="imagesUrl" @image-uploaded="uploadedImages"></upload-images>
    <div class="col mt-3">
      <lli-images-manage
          :get-url="imagesUrl"
          :update-url="imagesUrl"
          @change="updateImageAssociation"
          ref="imageGrid"
      ></lli-images-manage>
    </div>
  </div>
</template>
<script>
import LliImagesManage from "./manage";

export default {
  components: {LliImagesManage},
  data() {
    return {photos: {}};
  },
  props: {
    imagesUrl: {
      type: String,
      required: true,
    },
  },
  methods: {
    updateImageAssociation(imageId, newAssoc, changed) {
      if (changed) {
        if (this.checkImageLocationAssociationDuplicate(newAssoc) === true) {
          this.$refs.imageGrid.resetSelected(imageId);
          this.$alert("Bild mit diesem Typ existiert bereits.");
        } else {
          this.$set(this.photos, imageId, newAssoc);
        }
      } else {
        this.$delete(this.photos, imageId);
      }

      this.$emit("change", changed);
    },
    checkImageLocationAssociationDuplicate(newAssoc) {
      return (newAssoc === "LOGO" && Object.values(this.photos).indexOf("LOGO") > -1)
          || (newAssoc === "COVER" && Object.values(this.photos).indexOf("COVER") > -1);
    },
    uploadedImages() {
      this.$refs.imageGrid.reloadImages();
    },
    reset(e) {
      e.preventDefault();
      this.photos = {};
      this.$refs.imageGrid.reset();
    },
  },
};
</script>
