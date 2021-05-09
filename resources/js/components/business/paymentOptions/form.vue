<template>
    <base-form @submit="emitSubmit" @reset="emitReset" :back-url="backUrl">
        <div class="row mb-4">
            <div class="col">
                <label for="name">Name</label>
                <input v-model="form.name" type="text" name="name" id="name" class="form-control" />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <label for="description">Beschreibung</label>
                <b-textarea v-model="form.description" rows="4" id="description"></b-textarea>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <label>Zahlungsbedingungen</label>
                <b-form-group>
                    <b-form-radio-group
                            v-model="form.type"
                            :options="rates"
                            stacked
                    ></b-form-radio-group>
                </b-form-group>
            </div>
            <div class="col">
                <p v-if="showMonthsConfig">
                    <label for="months">Maximale Anzahl monatliche Raten</label>
                    <b-input-group
                            :prepend="form.rates + ''"
                            append="Monatsraten"
                    >
                        <b-form-input
                                type="range"
                                class="h-auto"
                                v-model="form.rates"
                                min="0"
                                max="60"
                        ></b-form-input>
                    </b-input-group>
                </p>
                <p v-if="showYearsConfig">
                    <label for="months">Maximale Anzahl jährliche Raten</label>
                    <b-input-group
                            :prepend="form.rates + ''"
                            append="Jahresraten"
                    >
                        <b-form-input
                                type="range"
                                class="h-auto"
                                v-model="form.rates"
                                min="0"
                                max="5"
                        ></b-form-input>
                    </b-input-group>
                </p>
            </div>
        </div>
    </base-form>
</template>
<script>
import { clone } from "lodash";
export default {
    data() {
        return {
            form: clone(this.formData),
            orginal: clone(this.formData),
            rates: [
                { text: "Einmalig", value: "once" },
                { text: "Monatliche Raten", value: "monthly" },
                { text: "Jährliche Raten", value: "yearly" }
            ]
        };
    },
    props: {
        formData: {
            type: Object,
            required: true
        },
        backUrl: {
            type: String,
            required: false
        }
    },
    computed: {
        showMonthsConfig() {
            return this.form.type === 'monthly';
        },
        showYearsConfig() {
            return this.form.type === 'yearly';
        }
    },
    methods: {
        getData() {
            return  clone(this.form);
        },
        emitSubmit() {
            this.$emit("submit", this.getData());
        },
        emitReset() {
            this.$emit("reset", this.getData());
        },
        reset() {
            this.form = clone(this.orginal);
        },
        setOrginal(update) {
            this.orginal = update;
        },
    }
};
</script>