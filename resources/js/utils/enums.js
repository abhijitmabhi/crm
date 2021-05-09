const enums =
    {
        leadState: {
            OPEN: 1,
            NOT_REACHED: 2,
            RECALL: 3,
            NO_INTEREST: 4,
            APPOINTMENT: 5,
            BLACKLIST: 6,
            CLOSED: 7,
            INVALID: 8,
            TOO_MANY_TRIES: 9,
            APPOINTMENT_NEEDED: 10,
            COMPETITION_PROTECTION: 11
        },
        commentReason: {
            APPOINTMENT: "APPOINTMENT",
            APPOINTMENT_NEEDED: "APPOINTMENT_NEEDED",
            APPOINTMENT_DELETED: "APPOINTMENT_DELETED",
            RECALL: "RECALL",
            OPEN: "OPEN",
            REOPEN_OLD: "Freigabe",
            REOPEN: "REOPEN",
            CORRECTION_OLD: "Korrektur",
            CORRECTION: "CORRECTION",
            NOT_REACHED: "NOT_REACHED",
            NO_INTEREST: "NO_INTEREST",
            COMMENT: "COMMENT",
            CREATED: "CREATED",
            COMPETITION_PROTECTION: "COMPETITION_PROTECTION",
            BLACKLIST: "BLACKLIST",
            CUSTOMER: "CUSTOMER"
        },
        commentDisplayName: {
            APPOINTMENT : "Termin",
            APPOINTMENT_NEEDED : "Termin nötig",
            APPOINTMENT_DELETED: "Termin gelöscht",
            Korrektur : "Korrektur",
            CORRECTION : "Korrektur",
            RECALL : "Wiedervorlage",
            COMMENT : "Kommentar",
            BLACKLIST : "Blacklist",
            Freigabe : "Freigabe",
            REOPEN : "Freigabe",
            NO_INTEREST : "Kein Interesse",
            NOT_REACHED : "Nicht erreicht",
            CREATED : "Erstellt",
            OPEN : "Wiedereröffnet",
            COMPETITION_PROTECTION : "Konkurrenzschutz",
            CUSTOMER : "Kunde"
        },
        commentIcon: {
            APPOINTMENT: "bg-success fa-calendar-alt",
            APPOINTMENT_NEEDED: "bg-success fa-phone-volume",
            APPOINTMENT_DELETED: "bg-danger fa-trash",
            RECALL: "bg-success fa-phone-volume",
            OPEN: "bg-success fa-exclamation-triangle",
            Freigabe: "bg-warning fa-exclamation-triangle",
            Korrektur: "bg-warning fa-exclamation-triangle",
            CORRECTION: "bg-warning fa-exclamation-triangle",
            NOT_REACHED: "bg-warning fa-exclamation-triangle",
            NO_INTEREST: "bg-warning fa-exclamation-triangle",
            COMMENT: "bg-info fa-comment-dots",
            CREATED: "bg-success fa-star",
            COMPETITION_PROTECTION: "bg-danger fa-ban",
            BLACKLIST: "bg-danger fa-exclamation-triangle",
            CUSTOMER: "bg-success far fa-sack-dollar"
        },
        role: {
            ADMIN: 'admin',
            CALL_CENTER_AGENT: 'callcenter-agent',
            CALL_CENTER_SUPERVISOR: 'callcenter-supervisor',
            CUSTOMER: 'customer',
            DESIGNER: 'UI/UX Designer',
            EXPERT: 'Expert',
            LLI_MANAGER: 'lli-manager',
            MANAGER: 'manager',
            FIX_LEADS: 'fix-leads',
            FINANCE: "finance",
            CITY: "city",
            LLI_DATA_SCRAPER: "LLI_DATA_SCRAPER",
            FOLLOW_UP_AGENT: "FOLLOW_UP_AGENT"
        },
        permission: {
            ASSIGN_EXPERTS: 'ASSIGN_EXPERTS',
            EDIT_ROLES: 'EDIT_ROLES',
            EDIT_USERS: 'EDIT_USERS',
            MAKE_CALLS: 'MAKE_CALLS',
            VIEW_APPOINTMENTS: 'VIEW_APPOINTMENTS',
            VIEW_EXPERTS: 'VIEW_EXPERTS',
            SEARCH_LEADS: 'SEARCH_LEADS',
            SCRAPE_MY_BUSINESS: 'SCRAPE_MY_BUSINESS'
        },
        locationState: {
            CITATIONS_DONE: "CITATIONS_DONE",
            ACCESS_DATA_SENT: "ACCESS_DATA_SENT",
            ACTIVATED: "ACTIVATED",
            INFORMATION_EXIST: "INFORMATION_EXIST",
            PICTURES_EXIST: "PICTURES_EXIST",
            HAS_PROBLEM: "HAS_PROBLEM",
            STATISTICS_READY: "STATISTICS_READY"
        },
        locationImageType: {
            CATEGORY_UNSPECIFIED: "Unbekannt",
            COVER: "Titelbild",
            PROFILE: "Profilbild",
            LOGO: "Logo",
            EXTERIOR: "Außenaufnahme",
            INTERIOR: "Innenaufnahme",
            PRODUCT: "Produkt",
            AT_WORK: "Bei der Arbeit",
            FOOD_AND_DRINK: "Essen und Trinken",
            MENU: "Speisekarte",
            COMMON_AREA: "Gemeinschaftsraum",
            ROOMS: "Räume",
            TEAMS: "Team",
            ADDITIONAL: "Zusätzlich"
        },
        LocationCitationState: {
            TODO: "TODO",
            DONE: "DONE"
        }
    }
export default enums;
