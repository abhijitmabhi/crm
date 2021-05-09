<template>
    <div class="d-flex align-items-baseline">
        <div class="pr-2 d-flex align-items-baseline">
            <span class="pr-2">SAM <i class="fas fa-user text-primary"></i></span>
            <select  @change="setExpert" v-model="expert" name="expert" id="expert">
                <option value="" selected>Alle</option>
                <option v-for="expert in experts" :value="expert.id">{{expert.name}}</option>
            </select>

        </div>
        <div class="d-flex align-items-baseline pr-2">
            <span class="pr-2">Zeitraum</span>
            <b-form-datepicker @input="setDate" id="example-datepicker" v-model="date" class="mb-2"></b-form-datepicker>
        </div>
        <button @click="reset" class="btn btn-primary">Filter zurücksetzen</button>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                date: "",
                expert:"",
                url: new URL(location.href)
            }
        },

        methods: {
            setExpert(){
                this.url.searchParams.set('expert', this.expert),
                location.href = this.url.href;
            },
            setDate(){
                this.url.searchParams.set('date', this.date),
                location.href = this.url.href;
            },
            reset () {
                this.url.searchParams.delete('date');
                this.url.searchParams.delete('expert');
                location.href = this.url.href;
            }
        },
        mounted(){
            if (this.url.searchParams.has('expert')){
                this.expert = this.url.searchParams.get('expert');
            }
            if (this.url.searchParams.has('date')){
                this.date = this.url.searchParams.get('date');
            }
        },
        props: {
            experts: {}
        }
    }
</script>