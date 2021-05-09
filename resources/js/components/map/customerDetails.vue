<template>
    <div>
        <b-tabs content-class="mt-3">
            <b-tab title="Info">
                <b-container>
                    <b-row v-if="locationData">
                        <b-col class="border-right border-red">
                            <h3>Standortdaten</h3>
                            <p>Name: {{locationData.name}}</p>
                            <p>Adresse: {{locationData.address}} {{locationData.postcode}} {{locationData.city}}</p>
                            <p>Telefon: {{locationData.phone}}</p>
                            <b-row>
                                <b-col cols="12">
                                    <strong>Standort Bilder</strong>
                                </b-col>
                                <b-col
                                    cols="4"
                                    v-for="image in locationData.images"
                                    :key="image.id"
                                >
                                    <b-img thumbnail :src="image.filename"></b-img>
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col>
                            <h3>Kundendaten</h3>
                            <p>Name: {{locationData.company.name}}</p>
                            <p>Telefon: {{locationData.company.phone}}</p>
                            <a :href="locationData.company.url">Website</a>
                        </b-col>
                    </b-row>
                    <b-row v-else>
                        <b-col class="text-center">
                            <b-spinner />
                        </b-col>
                    </b-row>
                </b-container>
            </b-tab>
            <b-tab title="History">
                <b-container>
                    <p>History Feature folgt...</p>
                </b-container>
            </b-tab>
        </b-tabs>
    </div>
</template>
<script>
export default {
    data() {
        return {
            locationData: false
        };
    },
    props: {
        locationId: {
            type: String | Number,
            required: true
        }
    },
    methods: {
        getLocation(locationId) {
            return axios.get(`/api/locations/${locationId}`).then(response => {
                let data = response.data;
                this.locationData = data;
            });
        }
    },
    mounted() {
        this.getLocation(this.locationId);
    }
};
</script>