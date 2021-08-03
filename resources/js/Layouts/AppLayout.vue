<template>
    <jig-layout>
        <template #navbar-menu>
            <div></div>
        </template>
        <!-- Page Heading -->
        <template v-slot:header>
            <div class="flex justify-center w-full">
                <div class="w-full text-secondary">
                    <slot name="header"></slot>
                </div>
            </div>
        </template>
        <template #sidebar>
            <sidebar :menu="frontendSidebarMenu" class="z-40"></sidebar>
        </template>

        <!-- Page Content -->
        <main>
            <slot></slot>
        </main>
    </jig-layout>
</template>

<script>
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";
import JetBanner from "@/Jetstream/Banner.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";
import JigLayout from "./JigLayout.vue";
import Sidebar from "./Jig/Sidebar.vue";
import menu from "./FrontendSidebarMenu.json";

import { defineComponent } from "vue";
export default defineComponent({
    components: {
        Sidebar,
        JigLayout,
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
    },

    data() {
        return {
            showingNavigationDropdown: false,
            frontendSidebarMenu: menu,
        };
    },

    methods: {
        switchToTeam(team) {
            this.$inertia.put(
                route("current-team.update"),
                {
                    team_id: team.id,
                },
                {
                    preserveState: false,
                }
            );
        },

        logout() {
            let vm = this;
            if (this.$page.props.user.is_cas) {
                console.log("Logging out of cas");
                const win = window.open(this.route("cas.logout"), "_blank");
                setTimeout(function () {
                    win.close();
                    vm.$inertia.reload();
                }, 1000);
            } else {
                this.$inertia.post(route("logout"));
            }
        },
    },
});
</script>
