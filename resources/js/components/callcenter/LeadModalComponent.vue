<template>
    <div>
        <button @click.stop="toggle">Toggle Modal</button>
        <div
            @click.stop="toggle"
            v-bind:class="{show: showModal, 'd-block': showModal}"
            class="modal"
            tabindex="-1"
            role="dialog"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button
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
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-white"
                            @click.stop="toggle"
                        >Save changes</button>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                            @click.stop="toggle"
                        >Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            showModal: false
        };
    },
    methods: {
        toggle() {
            this.showModal = !this.showModal;
        }
    }
};
</script>
