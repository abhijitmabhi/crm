<template>
    <div>
        <b-row>
            <b-col>
                <h2>Termin Nachbearbeitung</h2>
            </b-col>
        </b-row>
        <b-row>
            <b-col>
                <a
                    href="#"
                    class="border d-flex flex-column justify-content-center align-items-center p-4"
                    :class="successClasses.button"
                    @click.prevent="isSale = true"
                >
                    <p>
                        <i class="fas fa-file-signature h2" :class="successClasses.icon"></i>
                    </p>
                    <p class="mb-0">Vertragsabschluss</p>
                </a>
                <p></p>
            </b-col>
            <b-col>
                <a
                    href="#"
                    class="border d-flex flex-column justify-content-center align-items-center p-4"
                    :class="failureClasses.button"
                    @click.prevent="isSale = false"
                >
                    <p>
                        <i class="fas fa-heart-broken h2" :class="failureClasses.icon"></i>
                    </p>
                    <p class="mb-0">Nicht Verkauft</p>
                </a>
                <p></p>
            </b-col>
        </b-row>
        <b-row v-if="null !== isSale">
            <b-col>
                <sale-success
                    v-if="isSale"
                    :comment-config="config.comment"
                    :lead-id="leadId"
                    @updated="$emit('updated')"
                />
                <sale-failure
                    v-else
                    :config="config.comment"
                    :lead-id="leadId"
                    @updated="$emit('updated')"
                />
            </b-col>
        </b-row>
    </div>
</template>

<script>
import saleFailure from "./failure";
import saleSuccess from "./success";

function getClasses(isActive) {
    let [button, icon] = ["text-dark", ""];
    if (isActive) {
        [button, icon] = ["border-primary text-primary", "text-primary"];
    }
    return { button, icon };
}

export default {
    components: { saleFailure, saleSuccess },
    data() {
        return {
            config: {
                comment: {
                    max: 500,
                    min: 0,
                },
            },
            isSale: null,
        };
    },
    props: {
        leadId: {
            type: String | Number,
            required: true,
        },
    },
    computed: {
        successClasses() {
            return getClasses(true === this.isSale);
        },
        failureClasses() {
            return getClasses(false === this.isSale);
        },
    },
};
</script>

<style></style>
