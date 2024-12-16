import './bootstrap';

import { createApp } from 'vue'
import ChatComponent from './components/ChatComponent.vue'

const app = createApp({});

app.component('chatting-app-component', ChatComponent);
app.mount('#app');
