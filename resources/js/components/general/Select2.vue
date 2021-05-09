<template>
    <select
        ref="select"
        class="select-2-component"
        v-model="val"
        :multiple="multiple"
        :value="val"
        @input="$emit('input', val)"
    >
        <slot></slot>
    </select>
</template>

<script>
import $ from "jquery";
if (!window.jQuery) {
    window.jQuery = $;
}
const de = require("select2/dist/js/i18n/de");
import { merge } from "lodash";

export default {
    data() {
        return {
            val: null,
            jqEl: null,
        };
    },
    props: {
        language: {
            type: String | Object,
            default() {
                return de;
            },
        },
        value: String | Array,
        multiple: {
            type: Boolean,
            default: false,
        },
        tags: {
            type: Boolean,
            default: true,
        },
        minimumResultsForSearch: {
            type: Number,
            default: 10,
        },
        config: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    computed: {
        select2Cfg() {
            const { language, tags, multiple, minimumResultsForSearch } = this;
            return merge(
                {
                    tags,
                    multiple,
                    minimumResultsForSearch,
                    language,
                },
                this.config
            );
        },
    },
    methods: {
        dispatchInput() {
            const evt = document.createEvent("HTMLEvents");
            evt.initEvent("input", true, true);
            this.$refs.select.dispatchEvent(evt);
        },
        resetSelect() {
            this.dispatchInput();
        },
        select(value, emit = true) {
            if (this.multiple) {
                const val = this.jqEl.val();
                this.jqEl
                    .val(val.push(value))
                    .trigger("change")
                    .trigger("input");
                this.dispatchInput();
            } else {
                this.jqEl
                    .val(value)
                    .trigger("change")
                    .trigger("input");
            }
            if (emit) {
                this.$emit("selected", this.$refs.select.value);
                this.$emit("change", this.$refs.select.value);
            }
        },
    },
    mounted() {
        if (this.value) {
            if (this.select2Cfg.multiple) {
                this.val = Array.isArray(this.value) ? this.value : [this.value];
            } else {
                this.$el.value = this.value;
                this.val = this.value;
            }
        } else {
            this.val = this.select2Cfg.multiple ? [] : "";
        }

        let select = $(this.$el);
        this.jqEl = select;
        select
            .select2(this.select2Cfg)
            .on("select2:select", () => {
                this.val = select.val();
                this.dispatchInput();
                this.$emit("selection", this.val);
                this.$emit("select", this.val);
                this.$emit("change", this.val);
            })
            .on("select2:unselect", e => {
                this.val = select.val();
                this.dispatchInput();
                this.$emit("unselect", e);
                this.$emit("change", this.val);
            })
            .on("select2:change", () => {
                this.val = select.val();
                this.dispatchInput();
                this.$emit("change", this.val);
            });
        if (this.value && this.multiple) {
            select
                .val(this.value)
                .trigger("change")
                .trigger("input");
        }
    },
};
</script>

<style></style>
