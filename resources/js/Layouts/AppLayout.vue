<template>
    <jig-layout>
        <template #navbar-menu>
            <div></div>
        </template>
        <!-- Page Heading -->
        <template v-slot:header>
            <div class="flex w-full justify-center">
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
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import JigLayout from "./JigLayout";
    import Sidebar from "./Jig/Sidebar";
    import menu from "./FrontendSidebarMenu.json"

    export default {
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
            }
        },

        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                let vm = this;
                if (this.$page.props.user.is_cas) {
                    console.log("Logging out of cas");
                    const win = window.open(this.route('cas.logout'), "_blank");
                    setTimeout(function() {
                        win.close();
                        vm.$inertia.reload();
                    },1000)
                } else {
                    this.$inertia.post(route('logout'));
                }
            },
        }
    }
</script>
