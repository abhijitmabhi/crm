<template>
  <div>
    <div class="form-group">
      <input type="hidden" name="user"/>
      <slot>
        <!-- CSRF gets injected into this slot -->
      </slot>
      <div class="form-group">
        <label for="selectedUsers">Nutzer suchen</label>
        <input v-model="search" type="text" class="form-control" id="selectedUsers"
               placeholder="Email oder Name eingeben">
      </div>
    </div>

    <user-table
        :selected-users="filteredUsers"
    >

    </user-table>
  </div>
</template>

<script>
export default {
  name: "UserManagementComponent",
  props: {
    allUsers: {
      type: Array,
      default: [],
    },
  },
  data() {
    return {
      selectedUser: [],
      search: '',
    };
  },
  computed: {
    filteredUsers: function () {
      let searchTerm = this.search;
      return this.allUsers.filter(function (user) {
        return user.name.toLowerCase().indexOf(searchTerm.toLowerCase()) >= 0 ||
            user.email.toLowerCase().indexOf(searchTerm.toLowerCase()) >= 0;
      });
    }
  }
};
</script>

<style scoped>
</style>