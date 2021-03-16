<template>
    <img :alt="$page.props.app.name" class="text-white max-h-full" :src="logoUrl">
</template>
<script>
export default {
    data() {
        return {
            logoUrl: `${this.$page.props.app.url}/vendor/jig/images/savannabits-banner.png`
        }
    }
}
</script>
