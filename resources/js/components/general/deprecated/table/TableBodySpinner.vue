<template>
    <b-tbody>
        <tr>
            <td :colspan="colspan" class="text-center">
                <b-spinner></b-spinner>
            </td>
        </tr>
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