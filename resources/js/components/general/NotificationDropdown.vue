<template>
    <dropdown-component>
        <template v-slot:toggelingElement>
            <i class="far fa-bell" :class="{'text-primary': hasNotifications }"></i>
        </template>
        <span class="dropdown-item dropdown-header">{{notificationCount}} Benachrichtigungen</span>
        <span
            class="dropdown-item"
            v-if="notificationCount != 0"
            @click="readAll"
        >Alle als gelesen markieren</span>
        <div class="dropdown-divider"></div>
        <a
            class="dropdown-item"
            :key="notification.id"
            v-for="notification in notifications.unreadNotifications"
            @click.stop="read(notification)"
            :href="notification.link"
        >
            <i class="fas text-primary" :class="notification.icon"></i>
            {{notification.data.short_message | truncate}}
        </a>
        <a class="dropdown-item" href="/notifications">Alle Benachrichtigungen anzeigen</a>
    </dropdown-component>
</template>


<script>
export default {
    data() {
        return {
            notifications: {
                unreadNotifications: {},
                readNotifications: {}
            }
        };
    },
    methods: {
        getNotifications() {
            axios
                .get(`/api/users/${this.userId}/notifications`)
                .then(response => {
                    this.notifications = response.data;
                    window.setTimeout(this.getNotifications, 60000);
                });
        },

        read(notification) {
            axios.put(
                `/api/users/${this.userId}/notifications/${notification.id}`
            );
            return false;
        },
        toggleAll() {
            this.showAll = !this.showAll;
            this.getNotifications();
        },
        readAll() {
            axios.put(`/api/users/${this.userId}/notifications/`);
        }
    },
    computed: {
        notificationCount() {
            return this.notifications.unreadNotifications.length;
        },
        hasNotifications() {
            return this.notificationCount > 0;
        }
    },
    mounted() {
        this.getNotifications();
    },
    props: {
        userId: Number
    },
    filters: {
        ellipsize: function(value) {
            if (!value) return "";
            value = value.toString();
            return value.charAt(0).toUpperCase() + value.slice(1);
        }
    }
};
</script>

