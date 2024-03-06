import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store';
import './style.css'

const apiUrl = 'http://localhost:8000/api';
const app = createApp(App);

app.config.globalProperties.apiUrl = apiUrl;

app.use(router).use(store).mount('#app');