require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Notifications from "vue3-vt-notifications";
import VueNumerals from "vue-numerals"
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime"
import advancedFormat from "dayjs/plugin/advancedFormat"
import 'flatpickr/dist/flatpickr.css';
dayjs.extend(relativeTime);
dayjs.extend(advancedFormat)

const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => import(`./Pages/${name}`).then(module => module.default),
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(Notifications)
    .use(VueNumerals)
    .component('datepicker',() => import('vue-flatpickr-component'))
    .provide("$refreshDt",function(tableId) {
        this.$root.$emit('refresh-dt',{tableId: tableId});
    })
    .provide("$dayjs",dayjs)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
