<template>
    <div>
        <b-alert variant="success" :show="showSuccess" dismissible>
            <p>{{ success }} Leads wurden importiert.</p>
        </b-alert>
        <b-alert variant="danger" :show="errors.length > 0" dismissible>
            <p v-if="errors.length > 1" class="mb-1">
                {{ errors.length }} Leads wurden nicht importiert.
            </p>
            <p v-else class="mb-1">1 Lead wurde nicht importiert.</p>
            <b-button variant="secondary" v-b-toggle.error-details>Details</b-button>
            <b-button variant="secondary" class="text-dark" :href="errorFile"
                >Herunterladen</b-button
            >
            <b-collapse id="error-details" class="mt-2 mb-2">
                <p
                    v-for="(error, i) in errors"
                    :key="`error_import_${i}`"
                    class="mb-2"
                    v-html="error"
                />
            </b-collapse>
        </b-alert>
    </div>
</template>

<script>
export default {
    props: {
        errors: Array,
        errorFile: String,
        success: Number | String,
    },
    computed: {
        showSuccess() {
            return this.success && this.success > 0;
        },
    },
};
</script>

<style></style>
