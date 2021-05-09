<template>
    <div>
        <table class="table-striped table table-sm table-responsive" v-if="showStats">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Branche</th>
                    <th scope="col">Unbearbeitet</th>
                    <th scope="col">GF fehlt</th>
                    <th scope="col">Nicht erreicht</th>
                    <th scope="col">Wiedervorlage</th>
                    <th scope="col">Kein Interesse</th>
                    <th scope="col">Zu viele Versuche</th>
                    <th scope="col">Termine</th>
                    <th scope="col">Kunden</th>
                    <th scope="col">Blacklist</th>
                    <th scope="col">Insgesamt</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, index) in filteredCategoryStats" :key="`category-${index}`">
                    <th scope="row">{{ row.category }}</th>
                    <td>{{ row.openCount }}</td>
                    <td>{{ row.invalidCount }}</td>
                    <td>{{ row.notReachedCount }}</td>
                    <td>{{ row.recallCount }}</td>
                    <td>{{ row.noInterestCount }}</td>
                    <td>{{ row.tooManyTriesCount }}</td>
                    <td>{{ row.appointmentCount }}</td>
                    <td>{{ row.closedCount }}</td>
                    <td>{{ row.blacklistCount }}</td>
                    <td>{{ row.totalCount }}</td>
                </tr>
                <tr class="tr-dark">
                    <td></td>
                    <td>{{ openCountSum }}</td>
                    <td>{{ invalidCountSum }}</td>
                    <td>{{ notReachedCountSum }}</td>
                    <td>{{ recallCountSum }}</td>
                    <td>{{ noInterestCountSum }}</td>
                    <td>{{ tooManyTriesCountSum }}</td>
                    <td>{{ appointmentCountSum }}</td>
                    <td>{{ closedCountSum }}</td>
                    <td>{{ blacklistCountSum }}</td>
                    <td>{{ totalCountSum }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "CategoryStatsComponent",
    props: {
        categoryStats: {
            type: Array,
            required: true,
        },
        shownCategories: {
            type: Array,
            default: [],
        },
    },
    methods: {
        getSum(array) {
            return array.reduce((sum, count) => sum + count);
        },
    },
    computed: {
        filteredCategoryStats: function() {
            return this.categoryStats.filter(stats =>
                this.shownCategories.includes(stats.category)
            );
        },
        showStats: function() {
            return this.shownCategories.length > 0;
        },
        openCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.openCount));
        },
        invalidCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.invalidCount));
        },
        notReachedCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.notReachedCount));
        },
        recallCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.recallCount));
        },
        noInterestCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.noInterestCount));
        },
        tooManyTriesCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.tooManyTriesCount));
        },
        appointmentCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.appointmentCount));
        },
        closedCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.closedCount));
        },
        blacklistCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.blacklistCount));
        },
        totalCountSum: function() {
            return this.getSum(this.filteredCategoryStats.map(stats => stats.totalCount));
        },
    },
};
</script>

<style scoped>
.tr-dark td {
    color: #fff;
    background-color: #343a40;
    border-color: #454d55;
}
</style>
