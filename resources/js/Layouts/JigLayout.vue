<template>
    <div className="bg-gray-200">
        <sidebar-component></sidebar-component>
        <div className="relative flex flex-col min-h-screen bg-gray-200 md:ml-64">
            <navbar-component className="fixed flex-initial"></navbar-component>
            <!-- Header -->
            <div className="relative pt-12 pb-32 bg-indigo-400 md:pt-20">
                <div className="w-full px-1 mx-auto md:px-10">
                    <div>
                        <!-- Card stats -->
                        <div className="flex flex-wrap">
                            <slot name="header">
                                <div className="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        className="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0"
                                    >
                                        <div className="flex-auto p-4">
                                            <div className="flex flex-wrap">
                                                <div
                                                    className="relative flex-1 flex-grow w-full max-w-full pr-4"
                                                >
                                                    <h5
                                                        className="text-xs font-bold text-gray-500 uppercase"
                                                    >
                                                        Traffic
                                                    </h5>
                                                    <span
                                                        className="text-xl font-semibold text-gray-800"
                                                    >
                                                        350,897
                                                    </span>
                                                </div>
                                                <div
                                                    className="relative flex-initial w-auto pl-4"
                                                >
                                                    <div
                                                        className="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-red-500 rounded-full shadow-lg"
                                                    >
                                                        <i
                                                            className="far fa-chart-bar"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p
                                                className="mt-4 text-sm text-gray-500"
                                            >
                                                <span
                                                    className="mr-2 text-green-500"
                                                >
                                                    <i
                                                        className="fas fa-arrow-up"
                                                    ></i>
                                                    3.48%
                                                </span>
                                                <span
                                                    className="whitespace-no-wrap"
                                                >
                                                    Since last month
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div className="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        className="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0"
                                    >
                                        <div className="flex-auto p-4">
                                            <div className="flex flex-wrap">
                                                <div
                                                    className="relative flex-1 flex-grow w-full max-w-full pr-4"
                                                >
                                                    <h5
                                                        className="text-xs font-bold text-gray-500 uppercase"
                                                    >
                                                        New users
                                                    </h5>
                                                    <span
                                                        className="text-xl font-semibold text-gray-800"
                                                    >
                                                        2,356
                                                    </span>
                                                </div>
                                                <div
                                                    className="relative flex-initial w-auto pl-4"
                                                >
                                                    <div
                                                        className="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-orange-500 rounded-full shadow-lg"
                                                    >
                                                        <i
                                                            className="fas fa-chart-pie"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p
                                                className="mt-4 text-sm text-gray-500"
                                            >
                                                <span className="mr-2 text-red-500">
                                                    <i
                                                        className="fas fa-arrow-down"
                                                    ></i>
                                                    3.48%
                                                </span>
                                                <span
                                                    className="whitespace-no-wrap"
                                                >
                                                    Since last week
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div className="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        className="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0"
                                    >
                                        <div className="flex-auto p-4">
                                            <div className="flex flex-wrap">
                                                <div
                                                    className="relative flex-1 flex-grow w-full max-w-full pr-4"
                                                >
                                                    <h5
                                                        className="text-xs font-bold text-gray-500 uppercase"
                                                    >
                                                        Sales
                                                    </h5>
                                                    <span
                                                        className="text-xl font-semibold text-gray-800"
                                                    >
                                                        924
                                                    </span>
                                                </div>
                                                <div
                                                    className="relative flex-initial w-auto pl-4"
                                                >
                                                    <div
                                                        className="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-pink-500 rounded-full shadow-lg"
                                                    >
                                                        <i
                                                            className="fas fa-users"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p
                                                className="mt-4 text-sm text-gray-500"
                                            >
                                                <span
                                                    className="mr-2 text-orange-500"
                                                >
                                                    <i
                                                        className="fas fa-arrow-down"
                                                    ></i>
                                                    1.10%
                                                </span>
                                                <span
                                                    className="whitespace-no-wrap"
                                                >
                                                    Since yesterday
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div className="w-full px-4 lg:w-6/12 xl:w-3/12">
                                    <div
                                        className="relative flex flex-col min-w-0 mb-6 break-words bg-white rounded shadow-lg xl:mb-0"
                                    >
                                        <div className="flex-auto p-4">
                                            <div className="flex flex-wrap">
                                                <div
                                                    className="relative flex-1 flex-grow w-full max-w-full pr-4"
                                                >
                                                    <h5
                                                        className="text-xs font-bold text-gray-500 uppercase"
                                                    >
                                                        Performance
                                                    </h5>
                                                    <span
                                                        className="text-xl font-semibold text-gray-800"
                                                    >
                                                        49,65%
                                                    </span>
                                                </div>
                                                <div
                                                    className="relative flex-initial w-auto pl-4"
                                                >
                                                    <div
                                                        className="inline-flex items-center justify-center w-12 h-12 p-3 text-center text-white bg-blue-500 rounded-full shadow-lg"
                                                    >
                                                        <i
                                                            className="fas fa-percent"
                                                        ></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <p
                                                className="mt-4 text-sm text-gray-500"
                                            >
                                                <span
                                                    className="mr-2 text-green-500"
                                                >
                                                    <i
                                                        className="fas fa-arrow-up"
                                                    ></i>
                                                    12%
                                                </span>
                                                <span
                                                    className="whitespace-no-wrap"
                                                >
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
            <div className="flex flex-col justify-between flex-1 w-full px-1 mx-auto -mt-24 bg-gray-200 md:px-10">
                <div className="flex-auto">
                    <slot/>
                </div>
                <footer className="flex-initial py-4">
                    <div className="container px-1 mx-auto md:px-4">
                        <hr className="mb-4 border-gray-300 border-b-1"/>
                        <div
                            className="flex flex-wrap items-center justify-center md:justify-between"
                        >
                            <div className="w-full px-1 md:w-4/12 md:px-4">
                                <div
                                    className="py-1 text-sm font-semibold text-gray-600"
                                >
                                    Copyright © {{ date }}
                                    <a
                                        href="https://github.com/savannabits"
                                        className="py-1 text-sm font-semibold text-gray-600 hover:text-gray-800"
                                    >
                                        Savannabits
                                    </a>
                                </div>
                            </div>
                            <div className="w-full px-4 md:w-8/12">
                                <ul
                                    className="flex flex-wrap justify-center list-none md:justify-end"
                                >
                                    <li>
                                        <a
                                            href="https://www.savannabits.com"
                                            className="block px-3 py-1 text-sm font-semibold text-gray-700 hover:text-gray-900"
                                        >
                                            Savannabits
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="https://github.com/creativetimofficial/tailwind-starter-kit/blob/master/LICENSE.md"
                                            className="block px-3 py-1 text-sm font-semibold text-gray-700 hover:text-gray-900"
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
        <portal-target name="modal" multiple></portal-target>
    </div>
</template>
<script>
import NavbarComponent from "@/Layouts/Jig/Navbar.vue";
import SidebarComponent from "@/Layouts/Jig/Sidebar.vue";

export default {
    name: "JigLayout",
    components: {
        NavbarComponent,
        SidebarComponent
    },
    data() {
        return {
            date: new Date().getFullYear()
        };
    }
};
</script>
