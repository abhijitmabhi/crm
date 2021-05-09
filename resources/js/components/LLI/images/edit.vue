<template>
  <div class="d-flex flex-column justify-content-between border" :class="borderColor">
    <div class="d-flex justify-content-center mb-4 pt-3 position-relative">
      <b-img
          @click="showLargeImage"
          thumbnail
          :src="image.url"
          :class="imageClasses"
          style="margin: auto"
      ></b-img>
      <div
          v-if="deletingImage"
          class="position-absolute d-flex align-items-center justify-content-center w-100 h-100"
      >
        <b-spinner></b-spinner>
      </div>
    </div>
    <div>
      <p class="mb-0">Nutzung:</p>
      <p>
        <select2 :value="image.location_association" @select="selectOption" ref="test">
          <option
              v-for="(assoc, name) in locationAssociations"
              :key="name"
              :value="name"
          >{{ assoc }}
          </option>
        </select2>
      </p>
      <p class="text-right">
        <button class="btn btn-outline-dark" @click="deleteImage">Löschen</button>
      </p>
    </div>
    <show-large-image ref="showLargeImage" :large-image-url="largeImageUrl" :image-id="image.id"></show-large-image>
  </div>
</template>
<script>
import ShowLargeImage from "./ShowLargeImageComponent";

export default {
  components: {ShowLargeImage},
  data() {
    return {
      changed: false,
      deletingImage: false,
      locationAssociations: this.$enums.locationImageType,
      largeImage: ''
    };
  },
  props: {
    image: {
      type: Object,
      required: true
    }
  },
  computed: {
    largeImageUrl() {
      return this.image.original;
    },
    imageClasses() {
      if (this.deletingImage) return "img-fluid blur";
      return "img-fluid";
    },
    borderColor() {
      if (this.changed) {
        return "border-primary";
      }
      return "";
    },
    largeVersionUrl() {
      return this.image.original;
    }
  },
  methods: {
    reset() {
      this.changed = false;
      this.$refs.test.select(this.image.location_association);
      this.$emit("change", null, null, false);
    },
    selectOption(newAssoc) {
      this.changed = this.image.location_association !== newAssoc;
      this.$emit("change", this.image.id, newAssoc, this.changed);
    },
    deleteImage(e) {
      e.preventDefault();
      e.stopPropagation();
      let confirmed = confirm(
          "Durch diese Aktion wird das Bild auch auf Google MyBusiness gelöscht. Möchten Sie fortfahren?"
      );
      if (confirmed) {
        this.deletingImage = true;
        this.$emit("delete-image", this.image);
      }
    },
    showLargeImage() {
      this.$bvModal.show(`largeImageModal${this.image.id}`);
    }
  }
};
</script>
