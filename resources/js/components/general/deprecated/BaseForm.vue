<template>
    <!-- Outdated Experiment -->
    <form @submit="submit" @reset="reset">
        <slot></slot>
        <p class="mb-0 mt-4 pt-2">
            <slot name="buttons-before"></slot>
            <a
                v-if="backUrl"
                :href="backUrl"
                class="btn btn-white border font-weight-bolder border-secondary"
            >Zurück</a>
            <input
                type="reset"
                value="Zurücksetzen"
                class="btn btn-white border font-weight-bolder border-secondary"
            />
            <input
                type="submit"
                value="Speichern"
                class="btn btn-white border font-weight-bolder border-secondary"
            />
            <slot name="buttons-after"></slot>
        </p>
    </form>
</template>

<script>
export default {
    props: {
        backUrl: {
            type: String,
            required: false,
            default: null,
        },
    },
    methods: {
        submit(e) {
            e.preventDefault();
            e.stopPropagation();
            this.$emit("submit", e);
        },
        reset(e) {
            e.preventDefault();
            e.stopPropagation();
            this.$emit("reset", e);
        },
    },
};
</script>