<template>
    <b-card class="keywords" no-body>
        <div class="header">
            <h3 class="lli-card-title">Besucherströme</h3>
        </div>
        <b-card-body class="p-0" style="margin-right:70px!important;">
            <table class="w-100 border-top" style="margin-left:35px!important;height:100%!important">
                <tbody>
                    <tr v-for="(keyword, i) in keywords" :key="`${keyword}_${i}`">
                        <td class="border-bottom border-right text-center px-2 py-4 h4">
                            {{ i + 1 }}.
                        </td>
                        <td class="border-bottom border-right px-2 py-4 h4">
                            {{ keyword.keyword }}
                        </td>
                        <td class="border-bottom text-center py-4 h4">
                            {{ keyword.value }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </b-card-body>
    </b-card>
</template>

<script>
export default {
    data() {
        return {
            keywords: [
                { keyword: "Hörgeräte", value: 435 },
                { keyword: "Hörgeräte Karlsruhe", value: 285 },
                { keyword: "Hörgeräteakustiker", value: 215 },
                { keyword: "Hörgeräteakustiker Karlsruhe", value: 150 },
                { keyword: "Sigma Karlsruhe", value: 75 },
            ],
        };
    },
};
</script>

<style>
.keywords {
    padding: 40px 0;
}
.header {
    padding: 0 40px;
}
</style>
