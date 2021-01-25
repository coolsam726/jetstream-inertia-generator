<template>
    <div class="bg-gray-200">
        <sidebar-component></sidebar-component>
        <div class="relative flex flex-col min-h-screen bg-gray-200 md:ml-64">
            <navbar-component class="fixed flex-initial"></navbar-component>
            <!-- Header -->
            <div class="relative pt-12 pb-32 bg-primary-400 md:pt-32">
                <div class="w-full px-1 mx-auto md:px-10">
                    <div>
                        <!-- Card stats -->
                        <div class="flex flex-wrap">
                            <slot name="header">
                                <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                                    <h5 class="text-xs font-bold text-gray-500 uppercase">
                                                        Traffic
                                                    </h5>
                                                    <span class="text-xl font-semibold text-gray-800">
                          350,897
                        </span>
                                                </div>
                                                <div class="relative flex-initial w-auto pl-4">
                                                    <div
                                                        class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-red-500 rounded-full shadow-lg">
                                                        <i class="far fa-chart-bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-4 text-sm text-gray-500">
                                          <span class="mr-2 text-green-500">
                                            <i class="fas fa-arrow-up"></i> 3.48%
                                          </span>
                                                <span class="whitespace-no-wrap">
                                                Since last month
                                              </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                                    <h5 class="text-xs font-bold text-gray-500 uppercase">
                                                        New users
                                                    </h5>
                                                    <span class="text-xl font-semibold text-gray-800">
                          2,356
                        </span>
                                                </div>
                                                <div class="relative flex-initial w-auto pl-4">
                                                    <div
                                                        class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-orange-500 rounded-full shadow-lg">
                                                        <i class="fas fa-chart-pie"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-4 text-sm text-gray-500">
                      <span class="mr-2 text-red-500">
                        <i class="fas fa-arrow-down"></i> 3.48%
                      </span>
                                                <span class="whitespace-no-wrap">
                        Since last week
                      </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                                    <h5 class="text-xs font-bold text-gray-500 uppercase">
                                                        Sales
                                                    </h5>
                                                    <span class="text-xl font-semibold text-gray-800">
                          924
                        </span>
                                                </div>
                                                <div class="relative flex-initial w-auto pl-4">
                                                    <div
                                                        class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-pink-500 rounded-full shadow-lg">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-4 text-sm text-gray-500">
                      <span class="mr-2 text-orange-500">
                        <i class="fas fa-arrow-down"></i> 1.10%
                      </span>
                                                <span class="whitespace-no-wrap">
                        Since yesterday
                      </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0">
                                        <div class="flex-auto p-4">
                                            <div class="flex flex-wrap">
                                                <div class="relative flex-1 flex-grow w-full max-w-full pr-4">
                                                    <h5 class="text-xs font-bold text-gray-500 uppercase">
                                                        Performance
                                                    </h5>
                                                    <span class="text-xl font-semibold text-gray-800">
                          49,65%
                        </span>
                                                </div>
                                                <div class="relative flex-initial w-auto pl-4">
                                                    <div
                                                        class="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-blue-500 rounded-full shadow-lg">
                                                        <i class="fas fa-percent"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-4 text-sm text-gray-500">
                      <span class="mr-2 text-green-500">
                        <i class="fas fa-arrow-up"></i> 12%
                      </span>
                                                <span class="whitespace-no-wrap">
                        Since last month
                      </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-between flex-1 w-full px-1 mx-auto -m-24 bg-gray-200 md:px-10">
                <div class="flex-auto">
                    <slot/>
                </div>
                <footer class="flex-initial py-4">
                    <div class="container px-1 mx-auto md:px-4">
                        <hr class="mb-4 border-gray-300 border-b-1"/>
                        <div class="flex flex-wrap items-center justify-center md:justify-between">
                            <div class="w-full px-1 md:w-4/12 md:px-4">
                                <div class="py-1 text-sm font-semibold text-gray-600">
                                    Copyright © {{ date }}
                                    <a
                                        href="https://www.creative-tim.com"
                                        class="py-1 text-sm font-semibold text-gray-600 hover:text-gray-800"
                                    >
                                        Creative Tim
                                    </a>
                                </div>
                            </div>
                            <div class="w-full px-4 md:w-8/12">
                                <ul class="flex flex-wrap justify-center list-none md:justify-end">
                                    <li>
                                        <a
                                            href="https://www.creative-tim.com"
                                            class="block px-3 py-1 text-sm font-semibold text-gray-700 hover:text-gray-900"
                                        >
                                            Savannabits
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="https://www.creative-tim.com/presentation"
                                            class="block px-3 py-1 text-sm font-semibold text-gray-700 hover:text-gray-900"
                                        >
                                            About Us
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="http://blog.creative-tim.com"
                                            class="block px-3 py-1 text-sm font-semibold text-gray-700 hover:text-gray-900"
                                        >
                                            Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="https://github.com/creativetimofficial/tailwind-starter-kit/blob/master/LICENSE.md"
                                            class="block px-3 py-1 text-sm font-semibold text-gray-700 hover:text-gray-900"
                                        >
                                            MIT License
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <portal-target name="modal" multiple>
        </portal-target>
    </div>
</template>
<script>
import NavbarComponent from "@/Layouts/Jig/Navbar.vue";
import SidebarComponent from "@/Layouts/Jig/Sidebar.vue";

export default {
    name: "JigLayout",
    components: {
        NavbarComponent,
        SidebarComponent,
    },
    data() {
        return {
            date: new Date().getFullYear()
        }
    }
};
</script>
