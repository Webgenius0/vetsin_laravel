import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
    },
});
// Wait for Echo to connect, then attach socket ID to Axios globally
window.Echo.connector.pusher.connection.bind('connected', () => {
    const socketId = window.Echo.socketId();

    // Attach X-Socket-Id header to all axios requests globally
    window.axios.defaults.headers.common['X-Socket-Id'] = socketId;

    // console.log('Cluster:', import.meta.env.VITE_PUSHER_APP_CLUSTER);
    // console.log('Echo connected. Socket ID set in Axios:', socketId);
});

// Add error handling
window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('Echo connection error:', error);
});

// Add disconnection handling
window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('Echo disconnected');
});

// Debug Pusher connection states
window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log('Echo connection state changed:', states);
});

// Debug Pusher events
window.Echo.connector.pusher.bind('pusher:subscription_succeeded', (data) => {
    console.log('Pusher subscription succeeded:', data);
});

window.Echo.connector.pusher.bind('pusher:subscription_error', (data) => {
    console.error('Pusher subscription error:', data);
});

window.Echo.connector.pusher.bind('pusher:member_added', (data) => {
    console.log('Pusher member added:', data);
});

window.Echo.connector.pusher.bind('pusher:member_removed', (data) => {
    console.log('Pusher member removed:', data);
});
