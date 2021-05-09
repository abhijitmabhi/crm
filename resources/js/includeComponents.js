import Vue from "vue";
import vSelect from "vue-select";

Vue.component("v-select", vSelect);

// Customer
Vue.component("support-contact-form", require("@customer/SupportContactFormComponent").default);

// Business
Vue.component("sales-row", require("@business/saleReview/Row").default);
Vue.component("sales-control", require("@business/saleReview/Control").default);
Vue.component("product-create", require("@business/products/create").default);
Vue.component("product-edit", require("@business/products/edit").default);
Vue.component("product-form", require("@business/products/form").default);
Vue.component("product-index", require("@business/products").default);
Vue.component("payment-option-index", require("@business/paymentOptions/index").default);
Vue.component("payment-option-form", require("@business/paymentOptions/form").default);
Vue.component("payment-option-edit", require("@business/paymentOptions/edit").default);
Vue.component("payment-option-create", require("@business/paymentOptions/create").default);

// Leads
Vue.component("lead-to-customer", require("@callcenter/leads/toCustomer/LeadToCustomerComponent").default);
Vue.component("lead-create-form", require("./components/callcenter/leads/create/CreateLeadFormComponent").default);
Vue.component("lead-invalid-fix", require("@callcenter/leads/invalid/fix").default);
Vue.component("lead-form", require("@callcenter/leads/ShowLeadComponent/LeadComponent").default);
Vue.component("lead-state-change", require("@callcenter/leads/ShowLeadComponent/LeadStateChangeComponent").default);
Vue.component("lead-form-history", require("@callcenter/leads/formAndHistory").default);
Vue.component("lead-show", require("@callcenter/leads/show").default);
Vue.component("lead-edit", require("@callcenter/leads/edit").default);
Vue.component("lead-single", require("@callcenter/leads/lead-single").default);
Vue.component("lead-category", require("@callcenter/leads/CategorySelectComponent").default);
Vue.component("lead-table", require("@callcenter/leads/LeadTable").default);
Vue.component("lead-invalid", require("@callcenter/leads/invalid/index").default);
Vue.component("lead-list-item", require("@callcenter/leads/ListItem").default);
Vue.component("lead-import", require("@callcenter/leads/import/ImportLeadsComponent").default);
Vue.component("lead-create-popup", require("./components/callcenter/leads/create/CreateLeadPopupComponent").default);
// Expert / SAM
Vue.component("expert-confirm-appointments", require("@experts/confirm-appointments").default);
Vue.component("expert-dashboard", require("@experts/dashboard").default);
Vue.component("expert-lead-list", require("@experts/leads/list").default);
Vue.component("expert-show", require("@experts/show").default);
Vue.component(
    "expert-call-center-agent-assignment",
    require("@experts/pipeline/ExpertCallCenterAgentAssignmentComponent").default
);
Vue.component(
    "expert-lead-category-select",
    require("@experts/pipeline/ExpertLeadCategorySelectComponent").default
);
Vue.component("category-stats", require("@experts/pipeline/CategoryStatsComponent").default);
Vue.component("user-management", require("@admin/UserManagementComponent").default);
Vue.component("user-table", require("@admin/UserTableComponent").default);
Vue.component(
    "expert-lead-stats",
    require("@experts/ExpertLeadStatsComponent").default
);
Vue.component(
    "expert-lead-stats-table-row",
    require("@experts/ExpertLeadStatsTableRowComponent").default
);

// Agent
Vue.component("agent-stats", require("@callcenter/agent/stats").default);
Vue.component("agent-phone", require("@callcenter/agent/phone").default);
Vue.component("agent-success-table", require("@callcenter/agent/successTable").default);
Vue.component("agent-dashboard", require("@callcenter/agent/dashboard").default);
Vue.component("calendar", require("@callcenter/CalendarComponent").default); // Check component use and rename to be more specific
// Agent.Recalls
Vue.component("agent-recall-list", require("@callcenter/agent/recalls").default);
Vue.component("agent-recall-item", require("@callcenter/agent/recalls/item").default);
Vue.component("agent-appointment-needed-list", require("@callcenter/agent/appointmentNeeded").default);

// Appointments / Calendar
Vue.component("appointment", require("./components/callcenter/appointment").default);
Vue.component("appointments-past", require("@components/appointments/past").default);
Vue.component("user-calendar", require("@general/UserCalendarComponent").default);
Vue.component("create-private-appointment", require("@components/appointments/CreatePrivateAppointmentComponent").default);
Vue.component("appointment-info", require("@components/appointments/AppointmentInfo").default);
Vue.component("lead-appointment-info-modal", require("./components/appointments/LeadAppointmentInfoModal").default);

// LLI
Vue.component("lli-company-import", require("@LLI/company/import").default);
Vue.component("lli-company-form", require("@LLI/company/CompanyFormComponent").default);
Vue.component("google-auth-status", require("@LLI/company/GoogleAuthStatusComponent").default);
Vue.component("lli-location-form", require("@LLI/location/LocationFormComponent").default);
Vue.component("lli-document-table", require("@LLI/company/DocumentTableComponent").default);
Vue.component("lli-location-table", require("@LLI/location/location-table").default);
Vue.component("lli-keyword-scraping", require("@LLI/scraping/KeywordScrapingComponent").default);

Vue.component("lli-customer-dashboard", require("@components/LLI/statistics/CustomerDashboardComponent").default);
Vue.component("statistics-legend-item", require("@components/LLI/statistics/StatisticsLegendItem").default);
Vue.component("location-timeframe-picker", require("@components/LLI/statistics/LocationTimeFramePicker").default);
Vue.component("detailed-useraction-chart", require("@components/LLI/statistics/DetailedUserActionChart").default);
Vue.component("customer-list", require("@components/LLI/customer/CustomerListComponent").default);
Vue.component("customer-check", require("@components/LLI/customer/CustomerCheckComponent").default);
Vue.component("customer-check-result-list", require("@components/LLI/customer/CustomerCheckResultListComponent").default);
Vue.component("customer-check-result-item", require("@components/LLI/customer/CustomerCheckResultItemComponent").default);
Vue.component("detailed-useraction", require("@components/LLI/statistics/DetailedUserAction").default);
Vue.component("check-location-citations", require("@components/LLI/location/citations/LocationCitationsCheckComponent").default);
Vue.component("location-citations-spinner", require("@components/LLI/location/citations/LocationCitationsSpinnerComponent").default);



// Comments
Vue.component("history", require("./components/callcenter/leads/history").default);
Vue.component("comment-card", require("@callcenter/CommentCardComponent").default);

// Notifications
Vue.component("notifications", require("./components/general/Notifications").default);
Vue.component("notification-dropdown", require("@general/NotificationDropdown").default);

// Google Maps
Vue.component("gmap", require("@general/Gmap").default);
Vue.component("map-show-customer", require("./components/map/customerDetails").default);

// Search
Vue.component("search-auto-suggest", require("./components/search/AutoSuggestSearchComponent").default);

// General
Vue.component("back-button", require("@general/BackButton").default);
Vue.component("dropdown-component", require("@general/DropdownComponent").default);
Vue.component("flat-pickr", require("vue-flatpickr-component"));
Vue.component("upload-images", require("@general/dndFileUpload").default);
Vue.component("pagination", require("laravel-vue-pagination"));
Vue.component("select2", require("@general/Select2").default);
Vue.component("select2-ajax", require("@general/Select2_ajax").default);

// TODO: refactor deprecated
Vue.component("tbody-spinner", require("@general/deprecated/table/TableBodySpinner").default); // Use b-table and b-overlay instead
Vue.component("tbody-empty", require("@general/deprecated/table/TableBodyEmpty").default); // Use b-table instead
Vue.component("base-form", require("@general/deprecated/BaseForm").default); // outdated experiment
Vue.component("crud-table", require("@general/deprecated/table").default); // Use b-table instead
Vue.component("modal", require("@general/deprecated/ModalComponent").default); // Use b-modal instead

// Core
Vue.component(
    "import-blacklist-leads-component",
    require("./components/core/import/ImportBlacklistLeadsComponent").default
);
Vue.component("problematic-locations-spinner", require("./components/LLI/location/problem/ProblematicLocationsSpinnerComponent").default);
Vue.component("problematic-locations", require("./components/LLI/location/problem/ProblematicLocationsComponent").default);
Vue.component("problem-locations-table", require("./components/LLI/location/problem/ProblemLocationsTableComponent").default);
Vue.component("user-form", require("./components/admin/UserFormComponent").default);
Vue.component("unfinished-locations-table", require("./components/core/UnfinishedLocations/UnfinishedLocationsTableComponent").default);
Vue.component("unfinished-customers-table", require("./components/core/UnfinishedLocations/UnfinishedCustomersTableComponent").default);
