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
    wsHost: import.meta.env.VUE_APP_WEBSOCKETS_SERVER,
    wsPort: 6001,
    disableStats: true,
    forceTLS: false,
    // enabledTransports: ['ws', 'wss'],
});


createApp(App)
.use(store)
.use(router)
.mount('#app')
