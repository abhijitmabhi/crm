<template>
    <li class="nav-item dropdown" ref="dropdownMenu">
        <a @click="openDropdown = !openDropdown" class="nav-link dropdown-toggle" href="#">
            <slot name="toggelingElement"></slot>
        </a>
        <div ref="dropdownMenu">
            <div
                class="menu show dropdown-menu dropdown-menu-lg"
                :class="position"
                v-if="openDropdown"
            >
                <slot></slot>
            </div>
        </div>
    </li>
</template>


<script>
export default {
    data() {
        return {
            notifications: {},
            openDropdown: false
        };
    },
    props: {
        position: {
            type: String,
            default: "dropdown-menu-right"
        }
    },
    methods: {
        documentClick(e) {
            let el = this.$refs.dropdownMenu;
            let target = e.target;
            if (el !== target && !el.contains(target)) {
                this.openDropdown = false;
            }
        }
    },
    created() {
        document.addEventListener("click", this.documentClick);
    },
    destroyed() {
        document.removeEventListener("click", this.documentClick);
    }
};
</script>

