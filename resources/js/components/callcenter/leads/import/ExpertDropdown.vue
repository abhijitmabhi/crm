<template>
    <div>
        <p class="font-weight-bold mt-4">SAMs auswählen</p>
        <select2
            name="select-expert"
            ref="selectExperts"
            class="custom-select custom-select-md mb-4"
            @change="emitChange"
        >
            <option
                v-for="expert in experts.data"
                :key="expert.id"
                :value="expert.id"
                v-text="expert.name"
            />
        </select2>
    </div>
</template>

<script>
export default {
    data() {
        return {
            experts: [],
        };
    },
    methods: {
        getExperts() {
            return axios
                .get("/api/users?role=expert&per_page=1000")
                .then(response => (this.experts = response.data));
        },
        emitChange(...args) {
            this.$emit("expert-selected", ...args);
        },
    },
    mounted() {
        this.getExperts().then(() => {
            this.emitChange(this.$refs.selectExperts.$el.value);
        });
    },
};
</script>

<style></style>
