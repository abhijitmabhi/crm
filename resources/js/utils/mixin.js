/**
 *    Global functions available in all Vue components.
 */

let mixin = {
    getUserOptions() {
        return JSON.parse(document.head.querySelector('meta[name="user_options"]').content);
    },
    getPhoneLink(lead) {
        if (lead && lead.phone1) {
            let cleanedNumber = lead.phone1.toString().replace(/\s/g, "");
            let userOptions = this.getUserOptions();
            if (userOptions === null || ("dialer_active" in userOptions && userOptions.dialer_active === false)) {
                return "tel:" + cleanedNumber;
            }
            return "callto:" + cleanedNumber;
        }
        return "#";
    },
    
    getGoogleSearchUrl(lead) {
        let queryParameters = lead.company_name + ' ' + lead.zip + ' ' + lead.city + ' ' + lead.street;
        queryParameters = encodeURIComponent(queryParameters);
        return "https://google.de/search?q=" + queryParameters;
    },
    copyStringToClipboard(str) {
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
    },
    getRequestErrors(request) {
        const errors = request.response.data.errors;
        let requestErrors = [];
        for (let key in errors) {
            if (Array.isArray(errors[key])) {
                requestErrors.push(errors[key][0]);
            } else {
                requestErrors.push(errors[key]);
            }
        }
        return requestErrors;
    },
    getWebsiteValidState(website) {
        if (website === "" || website == null) {
            return null;
        }
        const regExp = /[-a-zA-ZäöüÄÖÜ0-9@:%._\+~#=]{1,256}\.[a-zA-ZäöüÄÖÜ0-9()]{1,6}\b([-a-zA-ZäöüÄÖÜ0-9()@:%_\+.~#?&//=]*)/;
        return regExp.test(website);
    },
    hasRole(roleName) {
        let userRoles = this.getAllRoles();
        return userRoles.indexOf(roleName) !== -1;
    },
    hasPermission(permissionName) {
        let permissions = this.getAllPermissions();
        return permissions.indexOf(permissionName) !== -1;
    },
    getAllPermissions() {
        return localStorage.getItem('Permissions').split(",");
    },
    getAllRoles() {
        return localStorage.getItem('Roles').split(",");
    }
}
export default mixin;




