<template>
    <div>
        <b-row>
            <b-col cols="12">
                <select2-ajax
                    data-url="/api/users"
                    :search-callback="userSearchCallback"
                    :result-callback="userResultCallback"
                    @select="selectExpert"
                >
                    <option>Nutzer auswählen</option>
                </select2-ajax>
            </b-col>
            <b-col class="d-flex my-4 justify-content-end" v-if="firstUser == userId" cols="12">
                <b-button variant="outline-success" v-b-modal.new-event-modal>Neuen Termin Anlegen</b-button>
            </b-col>

            <b-col v-if="firstUser">
                <calendar :expert-id="firstUser" />
            </b-col>
        </b-row>
        <b-modal size="xl" id="new-event-modal">
            <template v-slot:modal-header>
                <div></div>
            </template>
            <new-calendar-event :user="userId" @appointmentMade="selectExpert" />
            <template v-slot:modal-footer>
                <div></div>
            </template>
        </b-modal>

        <b-row>
            <b-col cols="12">
                <select2-ajax
                    data-url="/api/users"
                    :search-callback="userSearchCallback"
                    :result-callback="userResultCallback"
                    @select="selectSecondExpert"
                >
                    <option>Nutzer auswählen</option>
                </select2-ajax>
            </b-col>
            <b-col class="d-flex my-4 justify-content-end">
                <b-button variant="outline-success" v-b-modal.new-event-modal>Neuen Termin Anlegen</b-button>
            </b-col>

            <b-col v-if="secondUser">
                <calendar :expert-id="secondUser" />
            </b-col>
        </b-row>
        <b-modal size="xl" id="new-event-modal">
            <template v-slot:modal-header>
                <div></div>
            </template>
            <new-calendar-event :user="userId" @appointmentMade="selectExpert" />
            <template v-slot:modal-footer>
                <div></div>
            </template>
        </b-modal>
    </div>
</template>


<script>
export default {
    data() {
        return {
            firstUser: this.userId,
            secondUser: null,
        };
    },
    props: {
        userId: Number | String,
        users: Array,
    },
    methods: {
        userSearchCallback(param) {
            return {
                name: param.term,
            };
        },
        userResultCallback(result) {
            return {
                results: result.data.map(user => {
                    return {
                        id: user.id,
                        text: user.name,
                    };
                }),
            };
        },
        selectExpert(userId) {
            this.firstUser = userId;
        },
        selectSecondExpert(userId) {
            this.secondUser = userId;
        },
    },
};
</script>
