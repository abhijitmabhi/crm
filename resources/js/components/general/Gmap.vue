<template>
  <div>
    <b-form-input id="autocomplete" :class="searchBoxClasses" placeholder="Adresse suchen"></b-form-input>
    <div style="height:1000px" ref="map"></div>
    <b-modal id="lead-modal" hide-footer size="xl">
      <b-spinner v-if="loading"/>
      <appointment v-else-if="leadIsAppointment" :lead-id="openLead" :user-id="userId"/>
      <lead-form-history v-else-if="!accessDenied" :lead-id="openLead" in-modal/>
      <h1 v-if="accessDenied">Zugriff verweigert. Dieser Lead ist jemand anderem zugewiesen.</h1>
    </b-modal>
    <b-modal id="create-modal" hide-footer size="xl" no-enforce-focus>
      <lead-create-popup
          :is-expert="isExpert"
          :user-id="userId"
          :api-place-data="formData"
      ></lead-create-popup>
    </b-modal>
  </div>
</template>

<style scoped>
.searchbox {
  margin-top: 10px;
  max-width: 480px;
  max-height: 40px;
  border-radius: 0;
  background-color: white;
}

.searchbox.hidden {
  display: none;
}
</style>

<script>
import gmapsInit from "@utils/gmaps";
import MarkerClusterer from "@google/markerclustererplus";
import {isObject} from "util";
import {debounce} from "lodash";

export default {
  data() {
    return {
      mapLoaded: false,
      map: null,
      pins: [],
      pin_data: [],
      filter: {
        status: "",
        expert: "",
        type: "",
        bounds: ""
      },
      formData: {},
      markerCluster: {},
      openLead: 0,
      icons: {
        1: "/img/mapMarkers/mapMarkerOpen",
        2: "/img/mapMarkers/mapMarkerNotReached",
        3: "/img/mapMarkers/mapMarkerRecall",
        4: "/img/mapMarkers/mapMarkerNoInterest",
        5: "/img/mapMarkers/mapMarkerAppointment",
        6: "/img/mapMarkers/mapMarkerBlacklist",
        7: "/img/mapMarkers/mapMarkerCustomer",
        10: "/img/mapMarkers/mapMarkerCompetitionProtection"
      },
      loading: false,
      lead: null,
      accessDenied: false,
    };
  },
  props: {
    pinData: Object | String,
    userId: Number,
    isExpert: Boolean
  },
  watch: {
    pin_data() {
      this.updatePins();
    },
    filter: {
      handler: function (oldVal, newVal) {
        this.getPinData(this.apiUrl);
        this.updatePins();
      },
      deep: true
    },
    openLead() {
      this.loading = true;
      this.accessDenied = false;
      axios
          .get(`/api/leads/${this.openLead}`)
          .then(response => {
            this.lead = response.data;
          })
          .catch(e => {
            if (e.response.status === 403) {
              // this.$bvModal.hide("lead-modal");
              // console.error(e.response.data.errors);
              this.loading = false;
              this.accessDenied = true;
              console.log("Forbidden");
            } else {
              this.$bvModal.hide("lead-modal");
              console.error(e.response.data);
            }
          })
          .finally(() => (this.loading = false));
    }
  },
  methods: {
    openLeadModal(leadId) {
      this.openLead = leadId;
      this.$bvModal.hide("create-modal");
      this.$bvModal.show("lead-modal");
      this.getPinData("/api/map");
    },
    getPinData: debounce(function () {
      axios.get(this.apiUrl)
          .then(response => (this.pin_data = response.data));
    }, 1000, {"leading": false, "trailing": true}),
    updatePins() {
      // Delete old pins
      let pin;
      while ((pin = this.pins.pop())) {
        pin.setMap(null);
        pin = null;
      }
      if (Object.keys(this.markerCluster).length !== 0) {
        this.markerCluster.clearMarkers();
      }

      // Create new pins
      this.pin_data.forEach(data => {
        if (data.coordiantes == null) {
          return;
        }
        let vue = this;
        let pin = this.makeMarker(data);
        pin.id = data.id;

        if (data.type == "Lead" && !data.blocked) {
          pin.addListener("click", function () {
            vue.$bvModal.show("lead-modal");
            vue.openLead = pin.id;
          });
        } else if (data.type == "Location") {
          pin.addListener("click", function () {
            vue.$bvModal.msgBoxOk(
                vue.$createElement("map-show-customer", {
                  props: {
                    locationId: pin.id,
                    submitUrl: "asd"
                  }
                }),
                {size: "xl"}
            );
          });
        } else {
          pin.addListener("click", function () {
            const msg = vue.$createElement("p", {class: "m-0"}, [
              "Dieser Lead wird von ",
              vue.$createElement("strong", {}, [data.expert]),
              " verwaltet."
            ]);
            vue.$bvModal.msgBoxOk(msg, {
              title: data.company_name,
              headerClass: "p-2 border-bottom-0",
              footerClass: "p-2 border-top-0"
            });
          });
        }
        this.pins.push(pin);
        pin.setMap(this.map);
      });

      // Recreate cluster
      this.markerCluster = new MarkerClusterer(this.map, this.pins, {
        imagePath:
            "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
        gridSize: 130,
        maxZoom: 14
      });
    },
    makeMarker(pin) {
      if (pin.type == "Location") {
        return new google.maps.Marker({
          position: pin.coordiantes,
          icon: "/img/mapMarkers/white.png"
        });
      } else if (pin.blocked) {
        return new google.maps.Marker({
          position: pin.coordiantes,
          icon: this.icons[pin.status] + "_blocked.png"
        });
      } else {
        return new google.maps.Marker({
          position: pin.coordiantes,
          icon: this.icons[pin.status] + ".png"
        });
      }
    },
    addProperty(object, place, newKey, oldKey) {
      if (place.hasOwnProperty(oldKey)) {
        object[newKey] = place[oldKey];
      }
    },
    addAdressLines(object, place) {
      if (place.hasOwnProperty("address_components")) {
        object.city = this.getAdressProperty(place, "locality");
        object.street =
            this.getAdressProperty(place, "route") +
            " " +
            this.getAdressProperty(place, "street_number");
        object.zip = this.getAdressProperty(place, "postal_code");
      }
    },
    getAdressProperty(place, name) {
      var arr = place.address_components.map(component => {
        if (component.types.includes(name)) {
          return component.long_name;
        }
      });
      var filtered = arr.filter(function (el) {
        return el != null;
      });
      if (filtered[0] != null) {
        return filtered[0];
      }
      return '';
    },
  },
  computed: {
    apiUrl() {
      const url = new URL(window.location.origin + "/api/map");
      if (this.filter.status.length > 0) {
        url.searchParams.append("status", this.filter.status);
      }
      if (this.filter.expert.length > 0) {
        url.searchParams.append("expert", this.filter.expert);
      }
      if (this.filter.type.length > 0) {
        url.searchParams.append("type", this.filter.type);
      }
      url.searchParams.append("bounds", this.filter.bounds);
      return url.toString();
    },
    leadIsAppointment() {
      return !!this.lead && this.lead.status == 5;
    },
    searchBoxClasses() {
      if (this.mapLoaded) {
        return "searchbox elevation-1";
      }
      return "searchbox elevation-1 hidden";
    }
  },
  async mounted() {
    try {
      const google = await gmapsInit();
      const vueMapComponent = this;
      const h = this.$createElement;
      const geocoder = new google.maps.Geocoder();
      window.Echo.channel("map-events").listen(
          ".coordinatesFetched",
          e => {
            this.getPinData(
                `/api/map?status=${this.filter.status}&expert=${this.filter.expert}&type=${this.filter.type}`
            );
          }
      );
      let conf = {
        center: new google.maps.LatLng(50.88244, 11.1385562),
        zoom: 14,
        maxZoom: 20,
        minZoom: 12
      };
      if (
          localStorage.getItem("map-center-lat") !== null &&
          localStorage.getItem("map-center-lng") !== null
      ) {
        conf.center = new google.maps.LatLng(
            localStorage.getItem("map-center-lat"),
            localStorage.getItem("map-center-lng")
        );
      }
      if (localStorage.getItem("map-zoom") !== null) {
        conf.zoom = parseInt(localStorage.getItem("map-zoom"));
      }
      if (localStorage.getItem('map-bounds')) {
        this.filter.bounds = localStorage.getItem('map-bounds');
      }

      this.map = new google.maps.Map(this.$refs.map, conf);
      this.filter.bounds = this.map.getBounds();
      this.map.addListener("center_changed", function () {
        localStorage.setItem("map-center-lat", this.getCenter().lat());
        localStorage.setItem("map-center-lng", this.getCenter().lng());
      });

      this.map.addListener("bounds_changed", debounce(() => {
        let bounds = this.map.getBounds();
        localStorage.setItem("map-bounds", bounds);
        this.filter.bounds = bounds;
      }), 2000, {'leading': false, 'trailing': true});

      this.map.addListener("zoom_changed", function () {
        localStorage.setItem("map-zoom", this.getZoom());
      });

      class ClickEventHandler {
        constructor(map) {
          this.map = map;
          this.map.addListener("click", this.handleClick.bind(this));
          this.placesService = new google.maps.places.PlacesService(
              map
          );
        }
      }

      //TODO: refactoring to a seperate component needed
      ClickEventHandler.prototype.handleClick = function (event) {
        var me = this;
        if (event.placeId) {
          event.stop();
          me.placesService.getDetails(
              {placeId: event.placeId},
              function (place, status) {
                const children = [
                  h("h4", {class: ["text-center"]}, [
                    place.name
                  ]),
                  h("p", {class: ["text-center"]}, [
                    "Adresse: " + place.formatted_address
                  ]),
                  h("p", {class: ["text-center"]}, [
                    "Website: ",
                    h(
                        "a",
                        {
                          attrs: {
                            href: place.website,
                            target: "_blank"
                          }
                        },
                        place.website
                    )
                  ]),
                  h("p", {class: ["text-center"]}, [
                    "Telefonnummer: " +
                    place.formatted_phone_number
                  ])
                ];
                if (place.hasOwnProperty("photos")) {
                  children.push(
                      h("b-img", {
                        props: {
                          src: place.photos[0].getUrl(),
                          thumbnail: true,
                          center: true,
                          fluid: true
                        }
                      })
                  );
                }
                const messageVNode = h(
                    "div",
                    {class: ["foobar"]},
                    children
                );
                vueMapComponent.$bvModal
                    .msgBoxConfirm([messageVNode], {
                      okTitle: "Lead anlegen",
                      cancelTitle: "Abbrechen"
                    })
                    .then(value => {
                      if (value) {
                        const form = (vueMapComponent.formData = {});
                        vueMapComponent.$bvModal.show(
                            "create-modal"
                        );
                        vueMapComponent.addProperty(
                            form,
                            place,
                            "company_name",
                            "name"
                        );
                        vueMapComponent.addAdressLines(
                            form,
                            place
                        );
                        vueMapComponent.addProperty(
                            form,
                            place,
                            "phone1",
                            "formatted_phone_number"
                        );
                        vueMapComponent.addAdressLines(
                            form,
                            place
                        );
                        vueMapComponent.addProperty(
                            form,
                            place,
                            "website",
                            "website"
                        );
                      }
                    });
              }
          );
        }
      };
      var clickHandler = new ClickEventHandler(this.map);

      var input = document.getElementById("autocomplete");
      var options = {
        componentRestrictions: {country: "de"}
      };
      var searchBox = new google.maps.places.SearchBox(input, options);

      this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

      // Bias the SearchBox results towards current map's viewport.
      this.map.addListener("bounds_changed", () => {
        searchBox.setBounds(this.map.getBounds());
      });

      this.map.addListener("tilesloaded", () => {
        this.mapLoaded = true;
      });

      var places = new google.maps.places.PlacesService(this.map);

      searchBox.addListener("places_changed", () => {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
          return;
        }
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {
          if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
          }

          if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        this.map.fitBounds(bounds);
      });

      this.getPinData(this.apiUrl);
    } catch (error) {
      console.error(error);
    }
  }
};
</script>