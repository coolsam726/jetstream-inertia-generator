<template>

    <!-- Section 1 -->
    <section class="w-full px-6 pb-12 antialiased bg-white">
        <div class="mx-auto max-w-7xl">

            <nav class="relative z-50 h-24 select-none">
                <div class="container relative flex flex-wrap items-center justify-between h-24 mx-auto overflow-hidden font-medium border-b border-gray-200 md:overflow-visible lg:justify-center sm:px-4 md:px-2">
                    <div class="flex items-center justify-start w-1/4 h-full pr-4">
                        <a href="#_" class="inline-block py-4 md:py-0">
                            <application-logo></application-logo>
                        </a>
                    </div>
                    <div class="top-0 left-0 items-start w-full h-full p-4 text-sm bg-gray-900 bg-opacity-50 md:items-center md:w-3/4 md:absolute lg:text-base md:bg-transparent md:p-0 md:relative md:flex" :class="{'flex fixed': showMenu, 'hidden': !showMenu }">
                        <div class="flex-col w-full h-auto overflow-hidden bg-white rounded-lg md:bg-transparent md:overflow-visible md:rounded-none md:relative md:flex md:flex-row">
                            <a href="#_" class="inline-flex items-center block w-auto h-16 px-6 text-xl font-black leading-none text-gray-900 md:hidden">tails<span class="text-indigo-600">.</span></a>
                            <div class="flex flex-col items-start justify-center w-full space-x-6 text-center lg:space-x-8 md:w-2/3 md:mt-0 md:flex-row md:items-center">
                                <inertia-link :href="$page.props.app.url" class="inline-block w-full py-2 mx-0 ml-6 font-medium text-left text-indigo-600 md:ml-0 md:w-auto md:px-0 md:mx-2 lg:mx-3 md:text-center">Home</inertia-link>
                                <a href="#features" class="inline-block w-full py-2 mx-0 font-medium text-left text-gray-700 md:w-auto md:px-0 md:mx-2 hover:text-indigo-600 lg:mx-3 md:text-center">Features</a>
                                <a href="#docs" class="inline-block w-full py-2 mx-0 font-medium text-left text-gray-700 md:w-auto md:px-0 md:mx-2 hover:text-indigo-600 lg:mx-3 md:text-center">Blog</a>
                            </div>
                            <div class="flex flex-col items-start justify-end w-full pt-4 md:items-center md:w-1/3 md:flex-row md:py-0">
                                <inertia-link v-if="!$page.props.user && canLogin" :href="route('login')" class="w-full px-6 py-2 mr-0 text-gray-700 md:px-0 lg:pl-2 md:mr-4 lg:mr-5 md:w-auto">Sign In</inertia-link>
                                <inertia-link v-if="!$page.props.user && canRegister" :href="route('register')" class="inline-flex items-center w-full px-6 py-3 text-sm font-medium leading-4 text-white bg-indigo-600 md:px-3 md:w-auto md:rounded-full lg:px-5 hover:bg-indigo-500 focus:outline-none md:focus:ring-2 focus:ring-0 focus:ring-offset-2 focus:ring-indigo-600">Sign Up</inertia-link>
                                <inertia-link v-if="$page.props.user" :href="route('profile.show')" class="inline-flex items-center w-full px-6 py-3 text-sm font-medium leading-4 text-white bg-indigo-600 md:px-3 md:w-auto md:rounded-full lg:px-5 hover:bg-indigo-500 focus:outline-none md:focus:ring-2 focus:ring-0 focus:ring-offset-2 focus:ring-indigo-600"><i class="fas fa-user-circle text-primary-400 mr-2"></i> {{$page.props.user.email}}</inertia-link>
                            </div>
                        </div>
                    </div>
                    <div @click="showMenu = !showMenu" class="absolute right-0 flex flex-col items-center items-end justify-center w-10 h-10 bg-white rounded-full cursor-pointer md:hidden hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-700" v-show="!showMenu" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" v-cloak>
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="w-6 h-6 text-gray-700" v-show="showMenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" v-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </nav>

            <!-- Main Hero Content -->
            <div class="container max-w-lg px-4 py-32 mx-auto text-left md:max-w-none md:text-center">
                <h1 class="text-5xl font-extrabold leading-10 tracking-tight text-left text-gray-900 md:text-center sm:leading-none md:text-6xl lg:text-7xl"><span class="inline md:block">Savannabits</span> <span class="relative mt-2 text-transparent bg-clip-text bg-gradient-to-br from-secondary to-primary-400 md:inline-block">J-I-Generator</span></h1>
                <div class="mx-auto mt-5 text-gray-500 md:mt-12 md:max-w-lg md:text-center lg:text-lg">Simplifying the creation of your app's admin dashboard in no time!</div>
                <div class="flex flex-col items-center mt-12 text-center">
                    <span class="relative inline-flex w-full md:w-auto">
                        <inertia-link :href="route('dashboard')" type="button" class="inline-flex items-center justify-center w-full px-8 py-4 text-base font-bold leading-6 text-white bg-primary border border-transparent rounded-full md:w-auto hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                            Go to App
                        </inertia-link>
                    </span>
                </div>
            </div>
            <!-- End Main Hero Content -->
        </div>
    </section>

</template>

<script>
import ApplicationLogo from "@/JigComponents/ApplicationLogo.vue";
export default {
    name: "LandingPage",
    components: {ApplicationLogo},
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
    },
    data() {
        return {
            showMenu: false,
        }
    }
}
</script>

<style scoped>

</style>
