<template>
    <nav
        class="relative z-10 flex flex-wrap items-center justify-between px-6 py-4 bg-white shadow-xl md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-no-wrap md:overflow-hidden md:w-64"
    >
        <div
            class="flex flex-wrap items-center justify-between w-full px-0 mx-auto md:flex-col md:items-stretch md:min-h-full md:flex-no-wrap"
        >
            <!-- Toggler -->
            <button
                class="px-3 py-1 text-xl leading-none text-black bg-transparent border border-transparent border-solid rounded opacity-50 cursor-pointer md:hidden"
                type="button"
                v-on:click="toggleCollapseShow('bg-white m-2 py-3 px-6')"
            >
                <i class="fas fa-bars"></i>
            </button>
            <!-- Brand -->
            <a
                class="inline-block p-4 px-0 mr-0 text-sm font-bold text-left text-gray-700 uppercase whitespace-no-wrap md:block md:sticky md:pb-2"
                href="javascript:void(0)"
            >
                {{ $page.props.app.name }}
            </a>
            <!-- User -->
            <ul class="flex flex-wrap items-center list-none md:hidden">
                <li class="relative inline-block">
                    <notification-dropdown-component></notification-dropdown-component>
                </li>
                <li class="relative inline-block">
                    <user-dropdown-component></user-dropdown-component>
                </li>
            </ul>
            <!-- Collapse -->
            <div
                class="absolute top-0 left-0 right-0 z-40 items-center flex-1 h-auto overflow-x-hidden overflow-y-auto rounded shadow md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none"
                v-bind:class="collapseShow"
            >
                <!-- Collapse header -->
                <div
                    class="block pb-4 mb-4 border-b border-gray-300 border-solid md:min-w-full md:hidden"
                >
                    <div class="flex flex-wrap">
                        <div class="w-6/12">
                            <a
                                class="inline-block p-4 px-0 mr-0 text-sm font-bold text-left text-gray-700 uppercase whitespace-no-wrap md:block md:pb-2"
                                href="javascript:void(0)"
                            >
                                {{ $page.props.app.name }}
                            </a>
                        </div>
                        <div class="flex justify-end w-6/12">
                            <button
                                type="button"
                                class="px-3 py-1 text-xl leading-none text-black bg-transparent border border-transparent border-solid rounded opacity-50 cursor-pointer md:hidden"
                                v-on:click="toggleCollapseShow('hidden')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Form -->
                <form class="mt-6 mb-4 md:hidden">
                    <div class="pt-0 mb-3">
                        <input
                            type="text"
                            placeholder="Search"
                            class="w-full h-12 px-3 py-2 text-base font-normal leading-snug text-gray-700 placeholder-gray-400 bg-white border border-gray-600 border-solid rounded shadow-none outline-none focus:outline-none"
                        />
                    </div>
                </form>
                <!-- Navigation -->
                <ul class="flex flex-col list-none md:flex-col md:min-w-full">
                    <li v-for="(menuItem, idx) of menuItems" :key="idx" class="items-center">
                        <sidebar-link
                            :href="route(menuItem.route)"
                            :active="route().current(menuItem.route)"
                            ><i
                                :class="`mr-2 text-sm text-gray-500 ${menuItem.faIcon}`"
                            ></i>
                            {{menuItem.title}}</sidebar-link
                        >
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-4 md:min-w-full" />
                <!-- Heading -->
                <h6
                    class="block pt-1 pb-4 text-xs font-bold text-gray-600 no-underline uppercase md:min-w-full"
                >
                    Documentation
                </h6>
            </div>
        </div>
    </nav>
</template>
<script>
import NotificationDropdownComponent from "./NotificationDropdown.vue";
import UserDropdownComponent from "./UserDropdown.vue";
import SidebarLink from "@/JigComponents/SidebarLink";
import menu from "./Menu.json"
export default {
    data() {
        return {
            collapseShow: "hidden",
            menuItems: menu
        };
    },
    methods: {
        toggleCollapseShow: function(classes) {
            this.collapseShow = classes;
        }
    },
    components: {
        SidebarLink,
        NotificationDropdownComponent,
        UserDropdownComponent
    }
};
</script>
