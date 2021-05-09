"use strict";
import $ from "jquery";
import "select2";
import Vue from "vue";
import VueCookies from "vue-cookies";
import BootstrapVue from "bootstrap-vue";
import BusinessHours from "vue-business-hours";
import VueSimpleAlert from "vue-simple-alert";
import { statistics } from "./storage";
import global_functions from './utils/mixin';
import "./includeComponents";

window.jQuery = window.$ = $;

window["API_KEY"] = document.head.querySelector('meta[name="google_api_key"]').content;

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

//add enums as a global variable
Vue.prototype.$enums = enums;

let wssPort = document.head.querySelector('meta[name="wss_port"]');
let pusherKey = document.head.querySelector('meta[name="pusher_app_key"]');

if (wssPort && pusherKey) {
    window.Echo = new Echo({
        broadcaster: "pusher",
        key: pusherKey.content,
        wsHost: window.location.hostname,
        wsPort: wssPort.content,
        wssPort: wssPort.content,
        disableStats: true,
        encrypted: true,
    });
}

Vue.mixin({
    methods: global_functions
});

Vue.mixin({
    methods: {
        route: route,
    },
});

/**
 * Flatpickr config
 */
import {German} from "flatpickr/dist/l10n/de.js";
import ConfirmDatePlugin from "flatpickr/dist/plugins/confirmDate/confirmDate";

flatpickr.localize(German); // default locale is now Russian
flatpickr.setDefaults({
    plugins: [
        new ConfirmDatePlugin({
            confirmIcon: "",
            confirmText: "OK",
            showAlways: false,
        }),
    ],
});

/**
 * Currently only used for user Dropdown
 * TODO: remove this dependency
 */
require("bootstrap");

/**
 * Moment JS Setup and Vue integration
 */
const moment = require("dayjs");
import "dayjs/locale/de";

moment.locale("de");
Vue.use(require("vue-moment"), {
    moment,
});

/**
 * Axios Setup
 */

window.copyStringToClipboard = function (str) {
    // Temporäres Element erzeugen
    var el = document.createElement("textarea");
    // Den zu kopierenden String dem Element zuweisen
    el.value = str;
    // Element nicht editierbar setzen und aus dem Fenster schieben
    el.setAttribute("readonly", "");
    el.style = {
        position: "absolute",
        left: "-9999px",
    };
    document.body.appendChild(el);
    // Text innerhalb des Elements auswählen
    el.select();
    // Ausgewählten Text in die Zwischenablage kopieren
    document.execCommand("copy");
    // Temporäres Element löschen
    document.body.removeChild(el);
};

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}

let api_token = document.head.querySelector('meta[name="api_token"]');
if (api_token) {
    window.axios.defaults.headers.common["Authorization"] = "Bearer " + api_token.content;
} else {
    console.error("Api token not found");
}

/**
 * Packages and plugins used by Vue
 */
Vue.use(BusinessHours)
Vue.use(BootstrapVue);
Vue.use(VueCookies);
Vue.use(VueSimpleAlert);


VueCookies.config("7d");

Vue.filter("truncate", function (text, length, clamp) {
    text = text || "";
    clamp = clamp || "...";
    length = length || 30;

    if (text.length <= length) return text;

    var tcText = text.slice(0, length - clamp.length);
    var last = tcText.length - 1;

    while (last > 0 && tcText[last] !== " " && tcText[last] !== clamp[0]) last -= 1;

    // Fix for case when text dont have any `space`
    last = last || length - clamp.length;

    tcText = tcText.slice(0, last);

    return tcText + clamp;
});

/**
 * Vue Setup
 */
window.vueInstance = new Vue({
    el: "#app",
    data: {
        statistics,
    },
});


import * as Sentry from "@sentry/browser";
import {Vue as VueIntegration} from "@sentry/integrations";
import {Integrations} from "@sentry/tracing";
import enums from "./utils/enums";

Sentry.init({
    dsn: process.env.MIX_SENTRY_VUE_DSN,
    environment: process.env.MIX_SENTRY_VUE_ENV,
    integrations: [
        new Integrations.BrowserTracing(),
        new VueIntegration({
            Vue,
            attachProps: true,
            logErrors: true,
            tracing: true,
            tracingOptions: {
                trackComponents: true,
            },
        }),
    ],
    // We recommend adjusting this value in production, or using tracesSampler for finer control
    tracesSampleRate: 1.0,
});


window.refreshFromJs = function () {
    fetch("")
        .then(response => response.text())
        .then(content => {
            const parser = new DOMParser();
            const html = parser.parseFromString(content, "text/html");

            document.title = html.title;
            document.getElementById("app").innerHTML = html.getElementById("app").innerHTML;
        })
        .then(() => {
            window.vueInstance = new Vue({
                el: "#app",
            });
        });
};

$(".flash-message").each(function () {
    this.classList.add("show");
    window.setTimeout(function () {
        this.classList.remove("show");
    }, 5500);
});

$("#calendarFullscreenToggle").click(e => {
    if (e.target.innerText == "Vollbild") {
        e.target.innerText = "Minimieren";
    } else {
        e.target.innerText = "Vollbild";
    }
    $("#main-sidebar, #main-header, #cal-1").toggleClass("d-none");
    $("#content-wrapper").toggleClass("m-0");
    $("#cal-0").toggleClass("col-xl-6");
});

// Import Chart.js config
import "./chartjs_config";
import mixin from "./utils/mixin";
