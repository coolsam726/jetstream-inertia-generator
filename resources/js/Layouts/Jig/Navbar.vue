<template>
    <!-- Navbar -->
    <nav
        class="absolute top-0 left-0 z-10 flex items-center w-full p-4 bg-transparent md:flex-row md:flex-no-wrap md:justify-start"
    >
        <div
            class="flex flex-wrap items-center justify-between w-full px-4 mx-autp md:flex-no-wrap md:px-10"
        >
            <!-- Brand -->
            <inertia-link
                class="hidden text-sm font-semibold text-white uppercase lg:inline-block"
                :href="route('dashboard')"
                >Home</inertia-link
            >
            <!-- Form -->
            <form
                class="flex-row flex-wrap items-center hidden mr-3 md:flex lg:ml-auto"
            >
                <div class="relative flex flex-wrap items-stretch w-full">
                    <span
                        class="absolute z-10 items-center justify-center w-8 h-full py-3 pl-3 text-base font-normal leading-snug text-center text-gray-400 bg-transparent rounded"
                        ><i class="fas fa-search"></i
                    ></span>
                    <input
                        type="text"
                        placeholder="Search here..."
                        class="relative w-full px-3 py-3 pl-10 text-sm text-gray-700 placeholder-gray-400 bg-white rounded shadow outline-none focus:outline-none focus:shadow-outline"
                    />
                </div>
            </form>
            <!-- User -->
            <ul
                class="flex-col items-center hidden list-none md:flex-row md:flex"
            >
                <user-dropdown-component></user-dropdown-component>
            </ul>
        </div>
    </nav>

    <!-- End Navbar -->
</template>
<script>
import UserDropdownComponent from "./UserDropdown.vue";
import NavLink from "@/Jetstream/NavLink";
export default {
    components: {
        NavLink,
        UserDropdownComponent
    }
};
</script>
