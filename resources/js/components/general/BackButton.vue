<template>
    <a :href="back" class="btn btn-white">Zurück</a>
</template>

<script>
export default {
    computed: {
        back() {
            return document.referrer;
        }
    }
};
</script>