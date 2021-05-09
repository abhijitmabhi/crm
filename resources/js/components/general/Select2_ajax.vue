<template>
    <select2 ref="select" :value="val" @input="handleInput" :config="finishedConfig">
        <option id="-1" />
        <slot />
    </select2>
</template>

<script>
import $ from "jquery";
import { merge } from "lodash";

export default {
    data() {
        return {
            val: this.value,
            jqEl: null,
        };
    },
    props: {
        config: {
            type: Object,
            default() {
                return {};
            },
        },
        placeholder: {
            type: String | Object,
            default() {
                return {
                    id: "-1",
                    value: null,
                    text: "Bitte auswählen",
                };
            },
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        tags: {
            type: Boolean,
            default: false,
        },
        minimumResultsForSearch: {
            type: Number,
            default: 10,
        },
        dataUrl: {
            type: String,
            required: true,
        },
        searchCallback: {
            type: Function,
            default() {
                return params => {
                    return {
                        searchterm: param.term,
                    };
                };
            },
        },
        resultCallback: {
            type: Function,
            default() {
                return result => {
                    return {
                        results: result.data.map(({ id, name }) => {
                            return { id, text: name };
                        }),
                    };
                };
            },
        },
    },
    computed: {
        finishedConfig() {
            const token = document.head.querySelector('meta[name="csrf-token"]');
            const api_token = document.head.querySelector('meta[name="api_token"]');
            const { language, minimumResultsForSearch, multiple, placeholder, tags } = this;

            return merge(
                {
                    minimumResultsForSearch,
                    multiple,
                    placeholder,
                    tags,
                    ajax: {
                        url: this.dataUrl,
                        dataType: "json",
                        delay: 250,
                        data: this.searchCallback,
                        processResults: this.resultCallback,
                        beforeSend(xhr) {
                            xhr.setRequestHeader("X-CSRF-TOKEN", token.content);
                            xhr.setRequestHeader("Authorization", "Bearer " + api_token.content);
                        },
                    },
                },
                this.config
            );
        },
    },
    methods: {
        handleInput(...args) {
            this.val = this.$refs.select.val;
            this.$emit("input", ...args);
        },
        resetSelect() {
            this.$refs.select.resetSelect();
        },
    },
};
</script>

<style></style>
