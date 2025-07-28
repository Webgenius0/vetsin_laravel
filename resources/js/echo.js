import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
});
// Wait for Echo to connect, then attach socket ID to Axios globally
window.Echo.connector.pusher.connection.bind('connected', () => {
    const socketId = window.Echo.socketId();

    // Attach X-Socket-Id header to all axios requests globally
    window.axios.defaults.headers.common['X-Socket-Id'] = socketId;

    console.log('Cluster:', import.meta.env.VITE_PUSHER_APP_CLUSTER);
    console.log('Echo connected. Socket ID set in Axios:', socketId);
});
