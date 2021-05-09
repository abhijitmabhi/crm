<template>
  <div class="table-responsive">
    <table class="table-striped table table-sm table-hover">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Adresse</th>
        <th scope="col">PLZ</th>
        <th scope="col">Stadt</th>
        <th scope="col">Telefon</th>
        <th scope="col">Email</th>
        <th scope="col" class="centerVertical">Problem gelöst?</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="selectedLocation in selectedLocations">
        <td>
          <a :href="route('companies.locations.show', [selectedLocation.company_id, selectedLocation.id])">
            {{ selectedLocation.name }}
          </a>
        </td>
        <td>
          {{ selectedLocation.address }}
        </td>
        <td>
          {{ selectedLocation.postcode }}
        </td>
        <td>
          {{ selectedLocation.city }}
        </td>
        <td>
          {{ selectedLocation.phone }}
        </td>
        <td>
          {{ selectedLocation.email }}
        </td>
        <td class="center">
          <button
              class="btn btn-link"
              v-on:click="solveProblem(selectedLocation.id)"
          >
            <span aria-hidden="true">
                <i class="fas fa-check fa-2x"></i>
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
  name: "ProblemLocationsTable",
  props: {
    selectedLocations: {
      type: Array,
      default: [],
    },
  },
  methods: {
    solveProblem: function(id) {
      this.$emit('informParentToSetOverlay');
      if (confirm("Sind Sie sich sicher?")) {
        axios
            .post("/api/location/" + id + "/solveProblem",
        {
              locationId: id
            })
        .then(() => {
          window.location.reload();
        });
      } else {
        this.$emit('informParentToHideOverlay');
      }
    }
  }
}
</script>

<style scoped>
.tr-dark td {
  color: #fff;
  background-color: #343a40;
  border-color: #454d55;
}

.center {
  text-align: center;
}

.centerVertical {
  text-align: center;
}

thead th {
  vertical-align: middle;
}
</style>