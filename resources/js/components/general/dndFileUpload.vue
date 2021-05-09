<template>
    <b-card class="dnd-area cursor-pointer pl-0" style="background: #eeeeee" ref="dndArea" @click="openUploadPanel">
        <div class="success">
            <b-alert
                v-for="(result, index) in results"
                :key="index"
                :show="result.show"
                :variant="result.variant"
                dismissible
            >{{result.body}}</b-alert>
        </div>
        <div class="thumbnails">
            <div
                class="bg-white border p-1 thumbnail-container position-relative"
                v-for="(image,index) in image_data"
                :key="index"
                :data-index="index"
                @click="removeImage"
            >
                <div class="thumbnail w-100 h-100" :style="`background-image: url(${image})`">
                    <div
                        v-if="index == uploadIndex"
                        class="h-100 w-100 d-flex justify-content-center align-items-center"
                    >
                        <b-spinner></b-spinner>
                    </div>
                </div>
                <div class="control position-absolute w-100 h-100 text-center text-dark">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>
        <div class="text-center">
            <div v-if="images.length < 1" class="p-4">
                <p class="mb-4 h5 text-secondary">{{text}}</p>
                <p class="mb-0 h1 text-secondary">
                    <i class="fas" :class="icon"></i>
                </p>
            </div>
            <b-button v-else-if="storeUrl" variant="outline-dark" @click="startUpload">Bilder uploaden</b-button>
        </div>
        <input
            type="file"
            name="images[]"
            accept="image/jpeg, image/png"
            style="display: none"
            ref="fileUpload"
            :multiple="multiple"
            @change="handleInputUpload"
        />
    </b-card>
</template>
<script>
import $ from "jquery";
import { readFileAsync, checkFileMimetype } from "@utils/fileHelpers";
export default {
    data() {
        return {
            fails: [],
            uploadIndex: null,
            images: [],
            image_data: {},
            results: []
        };
    },
    props: {
        storeUrl: {
            type: String,
            required: false
        },
        multiple: {
            type: Boolean,
            default: true
        },
        text: {
            type: String,
            default: "Neue Bilder hier ablegen"
        },
        icon: {
            type: String,
            default: "fa-file-download"
        }
    },
    watch: {
        async images() {
            let image_data = {};
            if (FileReader && this.images && this.images.length) {
                let i;
                for (i = 0; i < this.images.length; i++) {
                    await readFileAsync(this.images[i]).then(result => {
                        this.$set(image_data, i, result);
                    });
                }
            }
            this.image_data = image_data;
        }
    },
    methods: {
        // Event Handlers
        openUploadPanel(e) {
            if (e.target != this.$refs.fileUpload) {
                e.preventDefault();
                this.$refs.fileUpload.click();
            }
        },
        async handleInputUpload(e) {
            await this.addImagesFromFilelist(e.target.files);
            this.$emit("newFile", this.images);
        },
        handleDnDUpload(e) {
            this.addImagesFromFilelist(e.originalEvent.dataTransfer.files);
        },
        removeImage(e) {
            this.preventDefaultAndPropagation(e);
            let el = e.target;
            while (!el.classList.contains("thumbnail-container")) {
                el = el.parentNode;
            }
            const removeIndex = parseInt(el.dataset.index, 10);
            this.images.splice(removeIndex, 1);
        },
        async startUpload(e) {
            let self = this;
            this.preventDefaultAndPropagation(e);
            let i = 0;
            while (i < this.images.length) {
                let image = this.images[i];
                let data = new FormData();
                data.append("image", image);
                this.uploadIndex = i;
                await axios
                    .post(this.storeUrl, data)
                    .then(response => {
                        if (response.status === 201) {
                            this.images = this.images.filter(
                                img => img != image
                            );
                        }
                        this.$emit("image-uploaded");
                    })
                    .catch(error => {
                      if(error.response.data.errors && error.response.data.errors.image) {
                        self.$alert(error.response.data.errors.image)
                      } else if (error.response.data && !error.response.data.includes('<html>')) {
                        self.$alert(error.response.data)
                      } else {
                        self.$alert(error.response.statusText);
                      }
                      i++;
                    });
                this.uploadIndex = null;
            }
        },
        // Helpers
        async addImagesFromFilelist(files) {
            let allowed = [];
            let i;
            for (i = 0; i < files.length; i++) {
                if (
                    await checkFileMimetype(files[i], [
                        "image/jpeg",
                        "image/png"
                    ])
                ) {
                    allowed.push(files[i]);
                }
            }
            this.images = this.images.concat(allowed);
        },
        preventDefaultAndPropagation(e) {
            e.preventDefault();
            e.stopPropagation();
        },
        attachDnD(form) {
            let $form = $(form);
            $form
                .on(
                    "drag dragstart dragend dragover dragenter dragleave drop",
                    this.preventDefaultAndPropagation
                )
                .on("dragover dragenter", () => $form.addClass("is-dragover"))
                .on("dragleave dragend drop", () =>
                    $form.removeClass("is-dragover")
                )
                .on("drop", this.handleDnDUpload);
        }
    },
    async mounted() {
        this.attachDnD(this.$refs.dndArea);
    }
};
</script>

<style>
.swal2-confirm, .swal2-styled{
  background-color: #7b0018 !important
}
</style>
