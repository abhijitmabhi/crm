<template>
  <div class="position-relative">
    <div
        v-if="recalls && isLoading"
        class="overlay position-absolute w-100 h-100 d-flex justify-content-center align-items-center"
    >
      <b-spinner/>
    </div>
    <b-table
        v-if="recalls"
        head-variant="dark"
        no-local-sorting
        :fields="fields"
        :items="recalls"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
    >
      <template v-slot:cell(lead)="lead">
        <a href="#" @click.prevent="openLead" :data-id="lead.value.id">
          <i class="fas fa-eye"></i>
          <span class="pl-2">{{ lead.value.name }}</span>
        </a>
      </template>
    </b-table>
    <div v-else-if="isLoading" class="text-center">
      <b-spinner/>
    </div>
    <div v-if="leads" class="d-flex align-items-center justify-content-center">
      <pagination :data="leads" @pagination-change-page="getLeads" :limit="2"></pagination>
    </div>
    <b-modal ref="modal" size="xl">
      <lead-form-history inModal :lead-id="activeLead"/>
    </b-modal>
  </div>
</template>

<script>
export default {
  provide: function () {
    return {
      userId: this.userId,
    };
  },
  props: {
    userId: {
      type: Number | String,
      required: true,
    },
  },
  data() {
    return {
      isLoading: true,
      leads: null,
      fields: [
        {key: "lead", sortable: true},
        {key: "experte", sortable: true, label: "SAM"},
        {key: "termin", sortable: true, label: "Fällig"},
        {key: "updated_at", sortable: true, label: "Gesetzt"},
      ],
      sortBy: "termin",
      sortDesc: false,
      activeLead: null,
    };
  },
  computed: {
    sort_by() {
      switch (this.sortBy) {
        case "lead":
          return "company_name";
        case "experte":
          return "expert_id";
        case "termin":
          return "closed_until";
        default:
          return this.sortBy;
      }
    },
    sort_direction() {
      return this.sortDesc ? "desc" : "asc";
    },
    recalls() {
      if (this.leads) {
        return this.leads.data.map(recall => {
          return {
            lead: {
              id: recall.id,
              name: recall.company_name,
            },
            agent: recall.agent,
            experte: recall.expert,
            termin: recall.closed_until + " Uhr",
            updated_at: recall.updated_at,
          };
        });
      }
      return null;
    },
  },
  watch: {
    sortDesc() {
      this.getLeads();
    },
  },
  methods: {
    getLeads(page = 1) {
      const params = {
        page,
        filter: 3,
        agent: this.userId,
      };
      if (this.sort_by) {
        params.sort_by = this.sort_by;
        params.sort_direction = this.sort_direction;
      }
      this.isLoading = true;
      return axios.get("/api/leads", {params}).then(response => {
        this.leads = response.data;
        this.isLoading = false;
      });
    },
    openLead(e) {
      e.preventDefault();
      e.stopPropagation();
      if (e.target.tagName === "a") {
        this.activeLead = e.target.dataset.id;
      } else {
        this.activeLead = e.target.parentNode.dataset.id;
      }
      this.$refs.modal.show();
    },
  },
  mounted() {
    this.getLeads();
  },
};
</script>

<style scoped>
.overlay {
  background-color: rgba(255, 255, 255, 0.25);
}
</style>
