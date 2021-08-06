require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress';
import Notifications from "vue3-vt-notifications";
import VueNumerals from "vue-numerals"
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime"
import advancedFormat from "dayjs/plugin/advancedFormat"
import 'flatpickr/dist/flatpickr.css';
import {emitter} from "./JigComponents/eventHub";


dayjs.extend(relativeTime);
dayjs.extend(advancedFormat)
const refreshDt = tableId => {
    emitter.emit('refresh-dt', { tableId: tableId });
}
createInertiaApp({
    resolve: name => import(`./Pages/${name}`).then(module => module.default),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({ methods: { route, dayjs, refreshDt } })
            .use(InertiaPlugin)
            .use(Notifications)
            .use(VueNumerals)
            .component('datepicker', () => import('vue-flatpickr-component'))
            .component("inertia-link", Link)
            .provide("$refreshDt", refreshDt)
            .provide("$dayjs", dayjs)
            .mount(el)
    },
});

InertiaProgress.init({ color: '#F59E0B' });
