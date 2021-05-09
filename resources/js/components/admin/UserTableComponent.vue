<template>
  <div class="table-responsive">
    <table class="table-striped table table-sm table-hover">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Name</th>
        <th scope="col">E-Mail</th>
        <th scope="col">Rollen</th>
        <th scope="col">Bearbeiten</th>
        <th scope="col">Status</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="selectedUser in selectedUsers">
        <td>
          {{ selectedUser.name }}
        </td>
        <td>
          {{ selectedUser.email }}
        </td>
        <td>
          <div v-for="(role, index) in selectedUser.roles" :key="role.name">
            {{ role.display_name }}<span v-if="index+1 < selectedUser.roles.length">,</span>
          </div>
        </td>
        <td v-if="selectedUser.id">
          <a
              :href="'/users/' + selectedUser.id"
              role="button"
              class="text-info p-2"
              aria-label="Left Align"
          >
            <span class="fas fa-edit" aria-hidden="true"></span>
          </a>
        </td>
        <td v-if="selectedUser.id">
          <button
              class="btn btn-link"
              v-on:click="onClickToggleUserActivation(selectedUser.id)"
          >
            <span aria-hidden="true">
                <div v-if="selectedUser.is_active">Account deaktivieren</div>
                <div v-else>Account aktivieren</div>
            </span>
          </button>
          <br/>
          <button
              class="btn btn-link mt-n3"
              v-on:click="onClickToggleUserLoginActivation(selectedUser.id)"
          >
            <span aria-hidden="true">
                <div v-if="selectedUser.block_login">Login aktivieren</div>
                <div v-else>Login deaktivieren</div>
            </span>
          </button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "UserTableComponent",
  props: {
    selectedUsers: {
      type: Array,
      default: [],
    },
  },
  methods: {
    onClickToggleUserLoginActivation(id) {
      if (confirm("Sind Sie sich sicher?")) {
        axios
            .post(this.route('api.toggleBlockLogin', id))
            .then(() => {
              window.location.reload();
            });
      }
      return false;
    },

    onClickToggleUserActivation(id) {
      if (confirm("Sind Sie sich sicher?")) {
        axios
            .post(this.route('api.toggleActive', id))
            .then(() => {
              window.location.reload();
            });
      }
      return false;
    },

  },
  computed: {},
};
</script>

<style scoped>
.tr-dark td {
  color: #fff;
  background-color: #343a40;
  border-color: #454d55;
}
</style>
