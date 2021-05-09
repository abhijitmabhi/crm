<template>
    <crud-table :rowData="data" :col-config="{name: 'Name'}" :read-url="readUrl"></crud-table>
</template>

<script>
export default {
    data() {
        return {
            data: null
        };
    },
    props: {
        dataUrl: {
            type: String,
            required: true
        },
        readUrl: {
            type: String
        },
        companyId: {
            type: String
        }
    },
    mounted() {
        axios.get(this.dataUrl).then(response => {
            if (response.data.length == 0) {
                this.data = null;
                return;
            }
            this.data = response.data;
        });
        window.Echo.private(`companies.${this.companyId}`).listen(
            ".locationImported",
            e => {
                if (!this.data) {
                    this.data = [];
                }
                this.data.push(e.location);
            }
        );
    }
};
</script>