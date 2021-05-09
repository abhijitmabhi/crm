<template>
  <div>
    <div class="d-flex">
      <div class="input-group mb-3 mr-3">
        <div class="input-group-prepend">
          <label for="search" class="input-group-text bg-primary">Suche</label>
        </div>
        <input
            v-model="search"
            id="search"
            type="text"
            class="form-control"
            placeholder="Suchbegriff eingeben"
        />
      </div>
      <div class="input-group mb-3 mr-3">
        <div class="input-group-prepend">
          <label class="input-group-text bg-primary" for="selectStatus">Status</label>
        </div>
        <select
            v-model="filter"
            id="selectStatus"
            @change="getLeads"
            class="custom-select custom-select-md"
        >
          <option value>---</option>
          <option v-for="(value, name) in filters" :key="value.id" :value="value">{{
              name
            }}
          </option>
        </select>
      </div>
      <div class="input-group mb-3 ml-3 d-flex">
        <div class="input-group-prepend">
          <label class="input-group-text bg-primary" for="selectExpert">SAM</label>
        </div>
        <div class="flex-grow-1">
          <select2-ajax
              v-model="expert"
              data-url="/api/users"
              :search-callback="expertSearchCallback"
              :result-callback="expertResultCallback"
          >
            <option :value="null">Alle Experten</option>
          </select2-ajax>
        </div>
      </div>
    </div>
    <b-overlay :show="fetchingLeads">
      <b-table
          ref="table"
          :busy.sync="fetchingLeads"
          :items="tableProvider"
          :fields="fields"
          responsive
          striped
          small
          head-variant="dark"
      >
        <template v-slot:cell(actions)="data">
          <!--                    <a :href="`/leads/${data.item.id}`">-->
          <!--                        <i class="fas fa-external-link-alt"></i>-->
          <!--                    </a>-->
          <button
              v-b-modal="'reasignModal' + data.item.id"
              title="neu zuweisen"
              class="btn btn-white btn-sm"
          >
            <i class="fas fa-angle-double-right"></i>
          </button>
          <b-modal
              :id="'reasignModal' + data.item.id"
              hide-footer
              ignore-enforce-focus-selector=".select2-search__field"
          >
            <reassign-modal
                @lead-saved="leadUpdated(data.item.id)"
                :lead="data.item"
                :experts="experts"
            ></reassign-modal>
          </b-modal>
          <button
              @click="unlock(data.item.id)"
              title="entsperren"
              :data-id="data.item.id"
              v-if="data.item.status == leadStates.BLACKLIST"
              class="btn btn-white btn-sm"
          >
            <i class="fas fa-lock-open"></i>
          </button>
        </template>
      </b-table>
    </b-overlay>

    <div class="d-flex align-items-center justify-content-center">
      <pagination
          :data="leadsMeta"
          @pagination-change-page="getLeads"
          :limit="2"
      ></pagination>
    </div>
  </div>
</template>

<script>
import {debounce} from "lodash";
import ReassignModal from "./ReassignModal";

export default {
  components: {ReassignModal},
  data() {
    return {
      leadStates: this.$enums.leadState,
      fetchingLeads: false,
      fields: [
        {
          key: "company_name",
          label: "Unternehmensname",
          sortable: true,
        },
        {
          key: "city",
          label: "Stadt",
          sortable: true,
        },
        {
          key: "expert",
          label: "SAM",
          sortable: true,
        },
        {
          key: "closed_until",
          label: "Datum",
          sortable: true,
        },
        {
          key: "actions",
          label: "Aktionen",
          sortable: false,
        },
      ],
      debouncedSearch: debounce(this.getLeads, 350),
      page: 1,
      loading: true,
      leadsMeta: {},
      orderBy: "",
      filter: "",
      experts: {},
      expert: null,
      search: "",
      filters: {
        Offen: 1,
        "Nicht Erreicht": 2,
        Rückruf: 3,
        "Kein Interesse": 4,
        Termine: 5,
        Blacklist: 6,
        Geschlossen: 7,
        Ungültig: 8,
      },
    };
  },
  watch: {
    expert() {
      this.getLeads();
    },
    search() {
      this.debouncedSearch();
    },
  },
  methods: {
    unlock(leadId) {
      axios
          .put(this.route('api.leads.revertBlacklist', leadId))
          .then(response => {
            this.$emit("leadSaved");
            this.getLeads();
          })
          .catch(error => {
            //TODO: show error message
            console.log(error.response.data);
          });
    },
    updatePage(newPage) {
      this.page = newPage;
    },
    getLeads(page = this.page) {
      this.page = page;
      this.$refs.table.refresh();
    },
    async tableProvider({sortBy, sortDesc}) {
      const sort_by = sortBy;
      const sort_direction = sortDesc ? "desc" : "asc";
      const {search, filter, expert, page} = this;
      const params = {expert, filter, page, search, sort_by, sort_direction};
      return axios
          .get("/api/leads", {params})
          .then(response => {
            const {data, meta, links} = response.data;
            this.leadsMeta = {meta, links};
            return data;
          })
          .catch(error => []);
    },
    leadUpdated(modalId) {
      this.$bvModal.hide(`reasignModal${modalId}`);
      this.getLeads();
    },
    setOrderBy(value) {
      this.orderBy = value;
      this.getLeads();
    },
    async getExperts() {
      let res = await axios.get("/api/users?role=expert");
      this.experts = res.data.data;
    },
    expertSearchCallback(params) {
      return {name: params.term, role: "expert"};
    },
    expertResultCallback(result) {
      return {
        results: result.data.map(expert => {
          return {id: expert.id, text: expert.name};
        }),
      };
    },
  },
  mounted() {
    this.getLeads();
    this.getExperts();
  },
};
</script>
