import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import vuetify from './plugins/vuetify'
import { createApp } from 'vue'
import './style.css'
const router = createRouter({
  history: createWebHistory(),          // используем обычные URL (без #)
  routes: [
    { path: '/', component: () => import('./views/Home.vue') },
    { path: '/:pathMatch(.*)*', component: () => import('./views/NotFound.vue') }
  ]
})

// Создаём приложение, подключаем роутер и Vuetify
createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#app')
