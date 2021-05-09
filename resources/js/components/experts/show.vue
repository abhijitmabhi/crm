<template>
    <b-row>
        <b-col v-if="!isExpert" cols="12">
            <select2-ajax
                v-model="expert_id"
                data-url="/api/users"
                :search-callback="expertSearchCallback"
                :result-callback="expertResultCallback"
                @select="selectExpert"
            >
                <option>SAM auswählen</option>
            </select2-ajax>
        </b-col>

        <b-col v-if="expert_id">
            <calendar v-if="leadId" :expert-id="expert_id" :lead-to-open="leadId" />
            <calendar v-else :expert-id="expert_id" />
        </b-col>
    </b-row>
</template>

<script>
export default {
    data() {
        return {
            expert_id: this.expertId,
        };
    },
    props: {
        isExpert: Boolean,
        expertId: Number | String,
        leadId: Number | String,
    },
    methods: {
        expertSearchCallback(param) {
            return {
                role: "Expert",
                name: param.term,
            };
        },
        expertResultCallback(result) {
            return {
                results: result.data.map(user => {
                    return {
                        id: user.id,
                        text: user.name,
                    };
                }),
            };
        },
        selectExpert(expertId) {
            this.expert_id = expertId;
        },
    },
};
</script>
