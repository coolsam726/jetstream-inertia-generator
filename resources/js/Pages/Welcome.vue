<template>
    <landing-page :can-login="canLogin" :can-register="canRegister"></landing-page>
</template>

<style scoped>
</style>

<script>
import InertiaButton from "@/JigComponents/InertiaButton";
import LandingPage from "@/Pages/LandingPage";
export default {
    components: {LandingPage, InertiaButton},
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    }
}
</script>
