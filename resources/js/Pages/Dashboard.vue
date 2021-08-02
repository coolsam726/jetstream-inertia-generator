<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-secondary leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden p-4 shadow-xl sm:rounded-lg">
                    <h2 class="font-bold text-3xl">Welcome, {{$page.props.user.name}}</h2>
                    <hr>
                    <div class="md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 py-4">
                        <div class="p-3">
                            <stats-card class="h-full" bg-class="bg-success-100" icon-classes="bg-success text-white">
                                <template #title>Sample Revenue</template>
                                <template #icon><i class="fas fa-coins fa-2x"></i></template>
                                1,200.00
                            </stats-card>
                        </div>

                        <div class="p-3">
                            <stats-card bg-class="bg-primary-100" class="h-full" icon-classes="bg-danger text-white">
                                <template #title>Sample Items Sold</template>
                                <template #icon><i class="fas fa-briefcase fa-2x"></i></template>
                                1,300
                            </stats-card>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Welcome from '@/Jetstream/Welcome.vue'
    import StatsCard from "@/JigComponents/StatsCard.vue";

    export default {
        components: {
            StatsCard,
            AppLayout,
            Welcome,
        },
    }
</script>
