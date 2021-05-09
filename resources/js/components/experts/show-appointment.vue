<template>
    <div class="card-body">
        <div class="card text-white">
            <div class="card-header simple-card-header bg-primary">
                <slot name="header">
                    <h5 class="card-title">Termin</h5>
                </slot>
            </div>
            <table class="table table-borderless table-sm bg-white">
                <tr>
                    <th class="h5" colspan="2">{{appointment.company_name}}</th>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-user"></i>
                        Geschäftsführer / Inhaber
                    </td>
                    <td>{{appointment.contact_name}}</td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-calendar-alt"></i>
                        Datum / Uhrzeit
                    </td>
                    <td>{{date}}</td>
                </tr>
                <tr v-if="appointment.email">
                    <td>
                        <i class="fas fa-envelope"></i>
                        Email
                    </td>
                    <td>
                        <a :href="'mailto:' + appointment.email">{{appointment.email}}</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-phone"></i>
                        Telefon
                    </td>
                    <td>
                        <p class="mb-0">
                            <a :href="'tel:' + appointment.phone1">
                                <i class="fas fa-phone"></i>
                                {{appointment.phone1}}
                            </a>
                        </p>
                        <p class="mb-0" v-if="appointment.phone2">
                            <a :href="'tel:' + appointment.phone2">
                                <i class="fas fa-phone"></i>
                                {{appointment.phone2}}
                            </a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fas fa-map-marker-alt"></i>
                        Ort
                    </td>
                    <td>
                        <p class="mb-0">{{appointment.company_name}}</p>
                        <p class="mb-0">{{appointment.street}}</p>
                        <p class="mb-0">{{appointment.zip}} {{appointment.city}}</p>
                    </td>
                </tr>
                <tr v-if="appointment.website">
                    <td>
                        <i class="fas fa-link"></i>
                        Website
                    </td>
                    <td>
                        <a :href="website" target="_blank">{{appointment.website}}</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        appointment: Object
    },
    computed: {
        website() {
            return this.appointment.website;
        },
        date() {
            const termin = this.$moment(this.appointment.closed_until);
            return (
                termin.format("DD.MM.YYYY") +
                " um " +
                termin.format("HH:MM") +
                " Uhr"
            );
        }
    }
};
</script>

<style>
</style>
