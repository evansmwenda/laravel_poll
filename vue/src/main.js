import { createApp } from 'vue'
import './style.css'
import store from './store'
import router from './router'
import App from './App.vue'
import './index.css'

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    wsHost: window.location.hostname,//'127.0.0.1',
    wsPort: 6001,
    disableStats: true,
    forceTLS: false,
    cluster: 'mt1'
    // enabledTransports: ['ws', 'wss'],
});


createApp(App)
.use(store)
.use(router)
.mount('#app')
