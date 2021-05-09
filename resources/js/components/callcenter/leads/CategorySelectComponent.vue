<template>
    <select2-ajax
        v-model="value"
        data-url="/api/categories/leads/object"
        :search-callback="searchCallback"
        :resultCallback="resultCallback"
        tags
    >
        <option v-if="!value" value>Bitte Branche wählen</option>
        <slot></slot>
    </select2-ajax>
</template>

<script>
export default {
    data() {
        return {
            value: this.default,
        };
    },
    props: {
        default: {
            type: String,
            default: null,
        },
    },
    computed: {
        getDefault() {
            if (this.default) return this.default;
            return null;
        },
    },
    watch: {
        value(newVal) {
            this.emitSelection(newVal);
        },
    },
    methods: {
        searchCallback(params) {
            return { search: params.term };
        },
        resultCallback(result) {
            return {
                results: result.map((category) => {
                    return {
                        id: category.category,
                        text: category.category,
                    };
                }),
            };
        },
        emitSelection(selection) {
            this.$emit("select", selection);
        },
        resetSelect() {
            this.$refs.select.resetSelect();
        },
    },
};
</script>

<style></style>
