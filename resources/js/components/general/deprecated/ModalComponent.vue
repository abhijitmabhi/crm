<template>
    <!-- Use <b-modal> instead -->
    <div>
        <template v-if="this.$scopedSlots.toggelingElement">
            <a @click.stop="toggle">
                <slot name="toggelingElement"></slot>
            </a>
        </template>
        <div
            v-if="showModal"
            v-bind:class="{ 'd-block': showModal }"
            class="modal"
            tabindex="-1"
            role="dialog"
        >
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <slot name="modalTitle"></slot>
                        </h5>
                        <button
                            v-if="!hideClose"
                            type="button"
                            class="close"
                            @click.stop="toggle"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <slot></slot>
                        </p>
                    </div>
                    <div v-if="!hideFooter" class="modal-footer">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
            <div @click.stop="toggle" class="modal-backdrop"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        hideClose: Boolean,
        type: String,
        showModal: Boolean,
        hideFooter: Boolean,
    },
    methods: {
        toggle() {
            this.$emit("update:showModal", !this.showModal);
        },
    },
};
</script>
