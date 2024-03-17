import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

let options = {};

if (import.meta.env.VITE_PUSHER_ENV === "local") {
    options = {
        broadcaster: "reverb",
        cluster: import.meta.env.VITE_PUSHER_CLUSTER,
        disableStats: true,
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
        encrypted: true
    };
} else {
    options = {
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_CLUSTER,
        forceTLS: true
    };
}

window.Echo = new Echo(options);