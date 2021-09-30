/*
|--------------------------------------------------------------------------
| Main entry point
|--------------------------------------------------------------------------
| Files in the "resources/scripts" directory are considered entrypoints
| by default.
|
| -> https://vitejs.dev
| -> https://github.com/innocenzi/laravel-vite
*/

// Import modules...
import 'dynamic-import-polyfill';
import "~/css/app.css"
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import Notifications from "vue3-vt-notifications";
import VueNumerals from "vue-numerals"
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime"
import advancedFormat from "dayjs/plugin/advancedFormat"
import 'flatpickr/dist/flatpickr.css';
import { emitter } from "@/JigComponents/eventHub.js";
import { Link } from "@inertiajs/inertia-vue3"

const JsBootstrap = import("@/bootstrap");


dayjs.extend(relativeTime);
dayjs.extend(advancedFormat)

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const pages = import.meta.glob('/resources/js/Pages/**/*.vue');
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const importPage = pages[`/resources/js/Pages/${name}.vue`];

        if (!importPage) {
            throw new Error(`Unknown page ${name}. Is it located under Pages with a .vue extension?`);
        }

        return importPage().then(module => module.default);
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route, dayjs } })
            .use(Notifications)
            .use(VueNumerals)
            .component('datepicker', () => import('vue-flatpickr-component'))
            .component("inertia-link", Link)
            .provide("$refreshDt", function (tableId) {
                emitter.emit('refresh-dt', { tableId: tableId });
            })
            .provide("$dayjs", dayjs)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#F59E0B' });
