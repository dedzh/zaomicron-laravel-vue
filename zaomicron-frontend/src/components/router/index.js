import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { path: '/', name: 'home', component: () => import('@/views/Home.vue') },
  { path: '/catalog', name: 'catalog', component: () => import('@/views/Catalog.vue') },
  { path: '/product/:id', name: 'product', component: () => import('@/views/Product.vue') },
  { path: '/cart', name: 'cart', component: () => import('@/views/Cart.vue') },
  { path: '/blog', name: 'blog', component: () => import('@/views/Blog.vue') },
  { path: '/news', name: 'news', component: () => import('@/views/News.vue') },
  { path: '/contacts', name: 'contacts', component: () => import('@/views/Contacts.vue') },
  { path: '/partners', name: 'partners', component: () => import('@/views/Partners.vue') },
  { path: '/service', name: 'service', component: () => import('@/views/Service.vue') },
  { path: '/forms', name: 'forms', component: () => import('@/views/Forms.vue') },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/views/NotFound.vue') }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;
    return { top: 0 };
  }
});

export default router;