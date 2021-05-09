<template>
    <div class="form-group row">
        <label for="starting" class="offset-sm-2 col-sm-2 mb-0 d-flex align-items-center">
            <strong>Start</strong>
        </label>
        <div class="col-sm-6 mb-2">
            <flat-pickr v-model="startDate" :config="config" altId="starting"></flat-pickr>
        </div>
        <label for="starting" class="offset-sm-2 col-sm-2 mb-0 d-flex align-items-center">
            <strong>Ende</strong>
        </label>
        <div class="col-sm-6">
            <flat-pickr v-model="endDate" :config="config"></flat-pickr>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            config: {
                altInputClass: "form-control",
                altFormat: "M	j, Y",
                altInput: true,
                dateFormat: "Y-m-d"
            },
            startDate: this.start,
            endDate: this.end
        };
    },
    props: {
        start: String,
        end: String
    },
    watch: {
        startDate() {
            let newStart = this.$moment(this.startDate);
            if (newStart.isSameOrAfter(this.endDate)) {
                this.endDate = newStart.add(1, "day").format("YYYY-MM-DD");
            }
            this.$emit("updateDate", this.startDate, this.endDate);
        },
        endDate() {
            let newEnd = this.$moment(this.endDate);
            if (newEnd.isSameOrBefore(this.startDate)) {
                this.startDate = newEnd.subtract(1, "day").format("YYYY-MM-DD");
            }
            this.$emit("updateDate", this.startDate, this.endDate);
        }
    }
};
</script>
<style>
input {
    cursor: pointer;
}
</style>

