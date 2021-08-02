import _ from "lodash";
import * as Axios from "axios";
// import * as Pusher from "pusher-js";
// import Echo from 'laravel-echo';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = Axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/*window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: import.meta.env.MIX_LARAVEL_WEBSOCKETS_PORT,
    disableStats: true,
    forceTLS: false,
    enabledTransports: ['ws', 'wss']
});*/
