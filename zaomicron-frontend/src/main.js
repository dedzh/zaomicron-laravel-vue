import { createApp } from 'vue'
import { createPinia } from 'pinia'          
import './assets/global.css'
import './style.css'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import '@fortawesome/fontawesome-free/css/all.css';

const app = createApp(App);
app.use(router);
app.use(vuetify);
app.use(createPinia());                       // подключаем Pinia
app.mount('#app');