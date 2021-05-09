<template>
  <div>
    <b-row>
      <b-col cols="12" xl="6" v-for="(user, index) in users" :key="index">
        <b-row>
          <b-col cols="9">
            <select2-ajax
                v-model="user.id"
                data-url="/api/users"
                :search-callback="userSearchCallback"
                :result-callback="userResultCallback"
            >
              <option v-if="index == 0" :value="userId" selected>
                {{ userName }}
              </option>
              <option v-else selected disabled>Nutzer auswählen</option>
            </select2-ajax>
          </b-col>
          <b-col cols="3">
            <b-button
                class="float-right"
                variant="btn btn-primary"
                @click="$bvModal.show(user.id)"
                :disabled="user.id == 0"
            >Privaten Termin erstellen
            </b-button>
          </b-col>
        </b-row>
        <b-row>
          <b-col v-if="user.id != 0">
            <calendar :user-role="userRole" :expert-id="user.id"/>
          </b-col>
        </b-row>
        <b-modal
            size="xl"
            :id="user.id"
            v-if="user.id != 0"
            title="Privaten Termin erstellen"
        >
          <create-private-appointment :user="user.id" :modal="user.id"/>
          <template v-slot:modal-footer>
            <div></div>
          </template>
        </b-modal>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  provide: function () {
    return {
      userId: this.userId,
      userRole: this.userRole,
    };
  },
  data() {
    return {
      users: {
        0: {id: this.userId},
        1: {id: 0},
      },
    };
  },
  props: {
    userRole: Array,
    userId: Number | String,
    userName: String,
    canSeeAllCalendars: Boolean,
  },
  methods: {
    userSearchCallback(param) {
      return {
        name: param.term,
      };
    },
    updateUsername(index, event) {
      this.users[index].name = event;
    },
    userResultCallback(result) {
      return {
        results: result.data.map((user) => {
          return {
            id: user.id,
            text: user.name,
          };
        }),
      };
    },
  },
};
</script>
