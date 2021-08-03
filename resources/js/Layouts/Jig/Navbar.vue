<template>
    <!-- Navbar -->
    <nav class="sticky top-0 z-20 w-full bg-primary md:-top-0">
        <!-- Primary Navigation Menu -->
        <div class="pr-4 mx-auto sm:pr-6 lg:pr-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <application-logo class="h-full px-8 bg-primary" />
                    <!-- Navigation Links -->
                    <div
                        class="hidden  sm:-my-px text-gray-50 hover:text-gray-200 sm:ml-10 sm:flex"
                    >
                        <slot> </slot>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="relative ml-3">
                        <!-- Teams Dropdown -->
                        <jet-dropdown
                            align="right"
                            width="60"
                            v-if="$page.props.jetstream.hasTeamFeatures"
                        >
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md  hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50"
                                    >
                                        {{ $page.props.user.current_team.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div
                                        v-if="
                                            $page.props.jetstream
                                                .hasTeamFeatures
                                        "
                                    >
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400 "
                                        >
                                            Manage Team
                                        </div>

                                        <!-- Team Settings -->
                                        <jet-dropdown-link
                                            :href="
                                                route(
                                                    'teams.show',
                                                    $page.props.user
                                                        .current_team
                                                )
                                            "
                                        >
                                            Team Settings
                                        </jet-dropdown-link>

                                        <jet-dropdown-link
                                            :href="route('teams.create')"
                                            v-if="
                                                $page.props.jetstream
                                                    .canCreateTeams
                                            "
                                        >
                                            Create New Team
                                        </jet-dropdown-link>

                                        <div
                                            class="border-t border-gray-100"
                                        ></div>

                                        <!-- Team Switcher -->
                                        <div
                                            class="block px-4 py-2 text-xs text-gray-400 "
                                        >
                                            Switch Teams
                                        </div>

                                        <div
                                            v-for="team in $page.props.user
                                                .all_teams"
                                            :key="team.id"
                                        >
                                            <form
                                                @submit.prevent="
                                                    switchToTeam(team)
                                                "
                                            >
                                                <jet-dropdown-link as="button">
                                                    <div
                                                        class="flex items-center "
                                                    >
                                                        <svg
                                                            v-if="
                                                                team.id ==
                                                                $page.props.user
                                                                    .current_team_id
                                                            "
                                                            class="w-5 h-5 mr-2 text-green-400 "
                                                            fill="none"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                            ></path>
                                                        </svg>
                                                        <div>
                                                            {{ team.name }}
                                                        </div>
                                                    </div>
                                                </jet-dropdown-link>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </jet-dropdown>
                    </div>

                    <!-- Settings Dropdown -->
                    <system-notifications-dropdown
                        :unread-notifications="[]"
                        class="relative ml-3"
                        :open="notificationsShown"
                        @opened="toggleNotificationDropdown"
                    ></system-notifications-dropdown>

                    <div class="relative ml-3">
                        <jet-dropdown align="right" class="w-12" :width="48">
                            <template #trigger>
                                <button
                                    v-if="
                                        $page.props.jetstream
                                            .managesProfilePhotos
                                    "
                                    class="flex text-sm transition duration-150 ease-in-out border-2 border-transparent rounded-full  focus:outline-none focus:border-gray-300"
                                >
                                    <img
                                        class="object-cover w-10 h-10 rounded-full "
                                        :src="
                                            $page.props.user.profile_photo_url
                                        "
                                        :alt="$page.props.user.name"
                                    />
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md  hover:text-gray-700 focus:outline-none"
                                    >
                                        {{ $page.props.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <!-- Account Management -->
                                <div
                                    class="block px-4 py-2 text-xs text-gray-400 "
                                >
                                    {{ $page.props.user.name }}
                                </div>

                                <jet-dropdown-link
                                    :href="route('profile.show')"
                                >
                                    Profile
                                </jet-dropdown-link>

                                <jet-dropdown-link
                                    :href="route('api-tokens.index')"
                                    v-if="$page.props.jetstream.hasApiFeatures"
                                >
                                    API Tokens
                                </jet-dropdown-link>

                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <responsive-nav-link
                                    @click.native.prevent="logout"
                                    target="_blank"
                                    v-if="$page.props.user.is_cas"
                                    :href="route('cas.logout')"
                                    >Logout</responsive-nav-link
                                >
                                <form v-else @submit.prevent="logout">
                                    <jet-dropdown-link as="button">
                                        Logout
                                    </jet-dropdown-link>
                                </form>
                            </template>
                        </jet-dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="flex items-center -mr-2 sm:hidden">
                    <button
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                        class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md  hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500"
                    >
                        <svg
                            class="w-6 h-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="sm:hidden"
        >
            <div class="pt-2 pb-3 space-y-1">
                <jet-responsive-nav-link
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Dashboard
                </jet-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div
                        v-if="$page.props.jetstream.managesProfilePhotos"
                        class="flex-shrink-0 mr-3"
                    >
                        <img
                            class="object-cover w-10 h-10 rounded-full"
                            :src="$page.props.user.profile_photo_url"
                            :alt="$page.props.user.name"
                        />
                    </div>

                    <div>
                        <div class="text-base font-medium text-gray-800">
                            {{ $page.props.user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ $page.props.user.email }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <jet-responsive-nav-link
                        :href="route('profile.show')"
                        :active="route().current('profile.show')"
                    >
                        Profile
                    </jet-responsive-nav-link>

                    <jet-responsive-nav-link
                        :href="route('api-tokens.index')"
                        :active="route().current('api-tokens.index')"
                        v-if="$page.props.jetstream.hasApiFeatures"
                    >
                        API Tokens
                    </jet-responsive-nav-link>

                    <!-- Authentication -->
                    <responsive-nav-link
                        @click.native.prevent="logout"
                        target="_blank"
                        v-if="$page.props.user.is_cas"
                        :href="route('cas.logout')"
                        >Logout</responsive-nav-link
                    >
                    <form v-else method="POST" @submit.prevent="logout">
                        <jet-responsive-nav-link as="button">
                            Logout
                        </jet-responsive-nav-link>
                    </form>

                    <!-- Team Management -->
                    <div v-if="$page.props.jetstream.hasTeamFeatures">
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Manage Team
                        </div>

                        <!-- Team Settings -->
                        <jet-responsive-nav-link
                            :href="
                                route(
                                    'teams.show',
                                    $page.props.user.current_team
                                )
                            "
                            :active="route().current('teams.show')"
                        >
                            Team Settings
                        </jet-responsive-nav-link>

                        <jet-responsive-nav-link
                            :href="route('teams.create')"
                            :active="route().current('teams.create')"
                        >
                            Create New Team
                        </jet-responsive-nav-link>

                        <div class="border-t border-gray-200"></div>

                        <!-- Team Switcher -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            Switch Teams
                        </div>

                        <div
                            v-for="team in $page.props.user.all_teams"
                            :key="team.id"
                        >
                            <form @submit.prevent="switchToTeam(team)">
                                <jet-responsive-nav-link as="button">
                                    <div class="flex items-center">
                                        <svg
                                            v-if="
                                                team.id ==
                                                $page.props.user.current_team_id
                                            "
                                            class="w-5 h-5 mr-2 text-green-400"
                                            fill="none"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        <div>{{ team.name }}</div>
                                    </div>
                                </jet-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
</template>
<script>
import UserDropdownComponent from "./UserDropdown.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import ResponsiveNavLink from "@/JigComponents/ResponsiveNavLink.vue";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink.vue";
import SystemNotificationsDropdown from "@/JigComponents/SystemNotificationsDropdown.vue";
import ApplicationLogo from "@/JigComponents/ApplicationLogo.vue";
import ApplicationMark from "@/JigComponents/ApplicationMark.vue";
import { defineComponent } from "vue";

export default defineComponent({
    components: {
        ApplicationLogo,
        ApplicationMark,
        SystemNotificationsDropdown,
        JetNavLink,
        UserDropdownComponent,
        JetDropdown,
        JetDropdownLink,
        ResponsiveNavLink,
        JetResponsiveNavLink,
    },
    data() {
        return {
            showingNavigationDropdown: false,
            notificationsShown: true,
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
        toggleNotificationDropdown(e) {
            this.notificationsShown = e;
        },
        logout() {
            let vm = this;
            if (this.$page.props.user?.is_cas) {
                console.log("Logging out of cas");
                const win = window.open(this.route("cas.logout"), "_blank");
                setTimeout(function () {
                    win.close();
                    vm.$inertia.reload();
                }, 2000);
            } else {
                this.$inertia.post(route("logout"));
            }
        },
        onLogout() {
            console.log("You have been redirected to logout page");
        },
    },
});
</script>
