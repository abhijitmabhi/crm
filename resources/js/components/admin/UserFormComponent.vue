<template>
  <form :action="getRoute" name="userForm" v-on:submit.prevent="submit">
    <b-overlay :show="isLoading">
      <slot>
        <!-- CSRF gets injected into this slot -->
      </slot>
      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Vorname">
            <b-input id="firstName" type="text" class="form-control" name="firstName" placeholder="Vorname"
                     v-model="firstName" required>
            </b-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Nachname">
            <b-input id="lastName" type="text" class="form-control" name="lastName" placeholder="Nachname"
                     v-model="lastName"
                     required>
            </b-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Email Adresse">
            <b-input id="email" type="text" class="form-control" name="email" placeholder="Email" v-model="email"
                     required>
            </b-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Passwort">
            <b-input id="password" type="password" class="form-control" name="password" autocomplete="new-password"
                     :placeholder="createUser ? 'Mind. 8 Zeichen!' : 'Neues Passwort'" v-model="password"
                     :required="createUser">
            </b-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row>
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Passwort bestätigen">
            <b-input id="password_confirmation" type="password" class="form-control" placeholder="Passwort bestätigen"
                     name="password_confirmation" v-model="confirmedPassword" autocomplete="new-password"
                     :required="createUser">
            </b-input>
          </b-form-group>
        </b-col>
      </b-row>

      <b-row v-if="isShowRoles">
        <b-col>
          <b-form-group
              label-class="font-weight-bold"
              label="Rollen">
            <v-select
                id="roles"
                name="roles"
                multiple
                :options="allRoles"
                label="display_name"
                placeholder="Rolle auswählen"
                v-model="selectedRoles"
            ></v-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-alert
          v-model="showAlert"
          :variant="alertType"
          dismissible
          @dismissed="isSuccess = false"
      >{{ alertMessage }}
      </b-alert>

      <input v-if="createUser" type="submit" value="Erstellen" class="btn btn-primary mt-4">
      <input v-else type="submit" value="Aktualisieren" class="btn btn-primary mt-4">

    </b-overlay>
  </form>
</template>

<script>
export default {
  name: "UserForm",
  props: {
    allRoles: {
      type: Array,
      default: () => []
    },

    user: {
      type: Object
    },

    userRoles: {
      type: Array
    },

    isShowRoles: {
      type: Boolean,
      default: true
    },
  },

  data() {
    return {
      errors: {
        type: Array,
        default: []
      },
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      confirmedPassword: "",
      selectedRoles: [],
      routeSuccess: this.route('users.index'),
      getRoute: "",
      createUser: true,
      showAlert: false,
      isSuccess: false,
      alertMessage: "",
      isLoading: false,
    };
  },

  computed: {
    alertType() {
      return this.isSuccess ? 'success' : 'danger';
    }
  },

  mounted() {
    if (this.user) {
      this.createUser = false;
      this.firstName = this.user.first_name;
      this.lastName = this.user.last_name;
      this.email = this.user.email;
      this.selectedRoles = this.userRoles;
    }
    this.createUser ? this.getRoute = this.route('users.store') : this.getRoute = this.route('users.update', this.user.id)
  },

  methods: {
    submit: function () {
      if (this.createUser) {
        this.validateAndMakePostRequest();
      } else {
        this.validateAndUpdateUser();
      }
    },

    validateAndUpdateUser: function () {
      if (this.validateForm()) {
        this.isLoading = true;
        this.showAlert = false;
        this.isSuccess = false;
        this.alertMessage = "";

        axios.put(this.getRoute, {
          "firstName": this.firstName,
          "lastName": this.lastName,
          "email": this.email,
          "password": this.password,
          "password_confirmation": this.confirmedPassword,
          "roles": this.selectedRoles
        }).then(() => {
          this.isSuccess = true;
          this.alertMessage = "Erfolgreich aktualisiert.";
        }).catch((error) => {
          this.isSuccess = false;
          this.alertMessage = "Anfrage konnte nicht verschickt werden. Versuchen sie es später nochmal."
        }).finally(() => {
          this.isLoading = false;
          this.showAlert = true;
        });
      }
    },

    validateAndMakePostRequest: function () {
      if (this.validateForm()) {
        this.isLoading = true;
        this.showAlert = false;
        this.isSuccess = false;
        this.alertMessage = "";

        axios.post(this.getRoute, {
          "firstName": this.firstName,
          "lastName": this.lastName,
          "email": this.email,
          "password": this.password,
          "password_confirmation": this.confirmedPassword,
          "roles": this.selectedRoles
        }).then(() => {
          this.isSuccess = true;
          this.alertMessage = "Erfolgreich aktualisiert.";
          window.location.href = this.routeSuccess;
        }).catch((error) => {
          this.showErrorAlert = true;
          this.errorAlertMessage = error.response.data.message;
        }).finally(() => {
          this.isLoading = false;
          this.showAlert = true;
        });
      }
    },

    validateForm: function () {
      this.errors = [];

      this.validateEmail();
      if (this.createUser || this.password !== "") {
        this.validatePassword();
      }
      this.validateIfRoleSelected();

      if (!this.errors.length) {
        return true;
      } else {
        alert(this.errors[0]);
      }
    },

    validateEmail: function () {
      const emailToCheck = this.email;
      const regExp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      const isEmailValid = regExp.test(emailToCheck);

      if (!isEmailValid) {
        this.errors.push('Bitte eine gültige Email eingeben.');
      }

    },

    validatePassword: function () {
      const passwordToCheck = this.password;
      const passwordConfirmedToCheck = this.confirmedPassword;

      if (passwordToCheck.length < 8)
        this.errors.push('Passwort muss mindestens 8 Zeichen haben!');
      else if (passwordConfirmedToCheck.length === 0)
        this.errors.push('Bitte das Passwort bestätigen!');
      else if (passwordToCheck !== passwordConfirmedToCheck)
        this.errors.push('Passwörter stimmen nicht überein!');

    },

    validateIfRoleSelected: function () {
      if (this.isShowRoles && this.selectedRoles.length === 0)
        this.errors.push('Mindestens eine Rolle erforderlich!');
    }

  }

};
</script>

<style scoped>

</style>
