<template>
    <nav
        class="relative z-40 flex no-scrollbar flex-wrap items-center justify-between px-0 bg-gray-700 shadow-xl md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-no-wrap md:overflow-hidden"
        :class="{'md:w-64': !minimized, 'w-16 hover:w-64': minimized}"
    >
        <div
            class="flex flex-wrap items-center justify-between w-full px-0 mx-auto md:flex-col md:items-stretch md:min-h-full md:flex-no-wrap"
        >
            <!-- Toggler -->
            <button
                class="px-3 py-1 text-xl leading-none text-gray-100 border border-transparent border-solid rounded opacity-50 cursor-pointer md:hidden"
                type="button"
                v-on:click="toggleCollapseShow('bg-white m-2 py-3 px-6')"
            >
                <svg viewBox="0 0 100 80" width="40" height="40">
                    <rect fill="white" width="100" height="5"></rect>
                    <rect fill="white" y="20" width="80" height="5"></rect>
                    <rect fill="white" y="40" width="70" height="5"></rect>
                    <rect fill="white" y="60" width="90" height="5"></rect>
                </svg>
            </button>
            <!-- Brand -->
            <a
                class="inline-block p-2 my-0 mr-0 text-sm border-b border-secondary-600 font-bold text-left text-gray-100 uppercase whitespace-no-wrap"
                href="javascript:void(0)"
            >
                <application-mark class="h-full text-4xl p-1 flex items-center justify-center"/>
            </a>
            <!-- User -->
<!--            <ul class="flex flex-wrap items-center list-none md:hidden">
                <li class="relative inline-block">
                    <notification-dropdown-component></notification-dropdown-component>
                </li>
                <li class="relative inline-block">
                    <user-dropdown-component></user-dropdown-component>
                </li>
            </ul>-->
            <!-- Collapse -->
            <div
                class="absolute top-0 left-0 right-0 z-40 items-center bg-white flex-1 h-auto overflow-x-hidden overflow-y-auto
                 shadow md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:shadow-none w-full"
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
                            v-if="!menuItem.isParent && $page.props.menu_permissions[idx]"
                            :href="route(menuItem.route)"
                            :active="route().current(menuItem.route)"
                            ><i
                                :class="`mr-2 pl-4 text-sm text-gray-500 ${menuItem.faIcon}`"
                            ></i>
                            <span class="hover:visible">{{menuItem.title}}</span></sidebar-link
                        >
                        <jig-sidebar-link
                            v-else-if="menuItem.isParent && $page.props.menu_permissions[idx]"
                            class="flex flex-wrap items-center justify-between"
                            @click.native.prevent="toggleExpanded(idx)"
                            :active="false"
                        >
                            <div class="text-md">
                                <i :class="`mr-2 pl-4 text-gray-500 ${menuItem.faIcon}`"></i>
                                <span class="hover:visible" :class="{'invisible': minimized}">{{ menuItem.title }}</span>
                            </div>
                            <i :class="`mr-2 pl-4 text-md ml-auto text-gray-500 fas ${expanded === idx ? 'fa-angle-down' : 'fa-angle-right'}`"></i>
                        </jig-sidebar-link>
                        <ul v-if="menuItem.isParent && $page.props.menu_permissions[idx] && expanded === idx" class="flex-col pl-2 list-none md:flex-col md:min-w-full">
                            <li v-for="(child, childIdx) of menuItem.children" :key="`${idx}-${childIdx}`" class="items-center">
                                <jig-sidebar-link
                                    v-if="$page.props.menu_permissions[childIdx]"
                                    class="flex flex-wrap items-center justify-between"
                                    :href="route(child.route)"
                                    :active="route().current(child.routePattern)"
                                >
                                    <div class="text-md">
                                        <i :class="`mr-2 pl-4 text-gray-500 ${child.faIcon}`"></i>
                                        {{ child.title }}
                                    </div>
                                </jig-sidebar-link>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-4 md:min-w-full" />
                <!-- Heading -->
<!--                <h6
                    class="block pt-1 pb-4 text-xs font-bold text-gray-600 no-underline uppercase md:min-w-full"
                >
                    Client Menu
                </h6>-->
            </div>
        </div>
    </nav>
</template>
<script>
import NotificationDropdownComponent from "./NotificationDropdown.vue";
import UserDropdownComponent from "./UserDropdown.vue";
import SidebarLink from "@/JigComponents/SidebarLink";
import menu from "./Menu.json"
import JigSidebarLink from "@/Components/JigSidebarLink";
import ApplicationMark from "@/Jetstream/ApplicationMark";
import ApplicationLogo from "@/Jetstream/ApplicationLogo";
import InertiaButton from "@/JigComponents/InertiaButton";
export default {
    props: {
        minimized: {
            default: false,
        }
    },
    data() {
        return {
            collapseShow: "hidden",
            menuItems: menu,
            expanded: null,
            bakMinimized: null,
        };
    },
    mounted() {
        const vm = this;
        for (const [key, menuItem] of Object.entries(vm.menuItems)) {
            if (menuItem.isParent && vm.isParentActive(menuItem)) {
                vm.expanded = key;
            }
        }
    },
    methods: {
        toggleCollapseShow: function(classes) {
            this.collapseShow = classes;
        },
        toggleExpanded(target) {
            if (target ===this.expanded) {
                this.expanded = null;
            } else {
                this.expanded = target;
            }
        },
        isParentActive(parent) {
            let active = false;
            const vm = this;
            const children = parent.children;
            for (const [idx,child] of Object.entries(children)) {
                if (vm.route().current(child.routePattern)) {
                    return true;
                }
            }
            return active;
        }
    },
    computed: {
    },
    components: {
        InertiaButton,
        ApplicationLogo,
        ApplicationMark,
        JigSidebarLink,
        SidebarLink,
        NotificationDropdownComponent,
        UserDropdownComponent
    }
};
</script>
<style scoped>
    /* Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
