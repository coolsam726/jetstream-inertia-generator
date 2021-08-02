<template>
    <landing-page :can-login="canLogin" :can-register="canRegister"></landing-page>
</template>

<style scoped>
</style>

<script>
import InertiaButton from "@/JigComponents/InertiaButton.vue";
import LandingPage from "@/Pages/LandingPage.vue";
export default {
    name: "Welcome",
    components: {LandingPage, InertiaButton},
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    }
}
</script>
