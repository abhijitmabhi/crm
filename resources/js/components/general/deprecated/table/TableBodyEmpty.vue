<template>
    <b-tbody>
        <b-tr>
            <b-td :colspan="colspan" class="text-center text-muted font-italic">Keine Einträge</b-td>
        </b-tr>
        <slot></slot>
    </b-tbody>
</template>

<script>
export default {
    props: {
        colspan: {
            type: Number | String,
            default: 1,
        },
    },
};
</script>