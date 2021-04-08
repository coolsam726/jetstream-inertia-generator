<template>
    <inertia-link :href="$page.props.app.url">
        <img :alt="$page.props.app.name" class="text-white max-h-full" :src="logoUrl">
    </inertia-link>
</template>
<script>
export default {
    data() {
        return {
            logoUrl: `${this.$page.props.app.url}/vendor/jig/images/savannabits-banner-white.png`
        }
    }
}
</script>
