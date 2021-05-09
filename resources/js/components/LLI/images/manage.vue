<template>
  <div class="row position-relative">
    <div class="mb-4 col">
      <div :class="containerClasses">
        <lli-images-edit
            ref="images"
            v-for="image in images.data"
            :key="image.id"
            :image="image"
            @delete-image="deleteImage"
            @change="updateAssociation"
            class="col col-sm-6 col-md-4 col-xl-3"
        ></lli-images-edit>
      </div>
    </div>
    <div class="col-12 d-flex justify-content-center">
      <pagination :data="images" @pagination-change-page="getData" :limit="2"></pagination>
    </div>
    <div
        v-if="loading"
        class="position-absolute h-100 w-100 d-flex justify-content-center align-items-center"
    >
      <b-spinner></b-spinner>
    </div>
  </div>
</template>
<script>
import {debounce} from "lodash";
import LliImagesEdit from "./edit";

export default {
  components: {LliImagesEdit},
  data() {
    return {
      images: {},
      debounceGetData: debounce(this.getData, 500),
      loading: false,
      cover_image_validations: {
        width: 2120,
        height: 1192,
        ratio: 1.777778
      }
    };
  },
  computed: {
    containerClasses() {
      if (this.loading) {
        return "row blur";
      }
      return "row";
    },
  },
  props: {
    getUrl: {
      type: String,
      required: true,
    },
    updateUrl: {
      type: String,
      required: true,
    },
  },
  methods: {
    reset() {
      this.$refs.images.forEach(image => image.reset());
    },
    resetSelected(imgId) {
      this.$refs.images.filter(element => element.image.id === imgId)[0].reset();
    },
    getUpdateUrl(img) {
      return this.updateUrl + "/" + img.id;
    },
    storeImages(response) {
      this.images = response.data;
    },
    getData(page = 1) {
      return axios
          .get(this.getUrl, {
            params: {
              page,
              per_page: 12,
              sort_by: "created_at",
            },
          })
          .then(this.storeImages)
          .then(() => (this.loading = false));
    },
    reloadImages(page = 1) {
      this.loading = true;
      this.debounceGetData();
    },
    deleteImage(image) {
      axios.delete(this.getUpdateUrl(image)).then(this.getData);
    },
    updateAssociation(imgId, newAssoc, changed) {
      if (newAssoc === 'COVER') {
        if (this.validateCover(newAssoc, imgId) === true) {
          this.$emit("change", imgId, newAssoc, changed);
        } else {
          let image = this.getImageComponentById(imgId).image;
          this.$alert(
              "Titelbild muss maximal 2120x1192 groß sein und ein Seitenverhältnis von 1.777778 haben. " +
              "Ausgewähltes ist " + image.width + "x" + image.height + " groß und hat ein Seitenverhältnis von "
              + image.ratio
          )
          this.getImageComponentById(imgId).reset();
        }
      } else {
        this.$emit("change", imgId, newAssoc, changed);
      }
    },
    validateCover(newAssoc, imgId) {
      let image = this.getImageComponentById(imgId).image;
      return image.width <= this.cover_image_validations.width
          && image.height <= this.cover_image_validations.height
          && Math.abs(image.ratio - this.cover_image_validations.ratio) < 0.01;
    },
    getImageComponentById(imageId) {
      return this.$refs.images.filter(element => element.image.id === imageId)[0];
    }
  },
  mounted() {
    this.getData();
  },
};
</script>
