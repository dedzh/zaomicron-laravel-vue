import { createRouter, createWebHistory } from 'vue-router';


const routes = [
  { path: '/', name: 'home', component: () => import('@/views/Home.vue') },
 { path: '/catalog/:id?', name: 'catalog', component: () => import('@/views/Catalog.vue') },
  { path: '/catalogPDF', name: 'catalogPGF', component: () => import('@/components/catalog/CatalogPDF.vue') },
  { path: '/product/:id', name: 'product', component: () => import('@/views/Product.vue') },
  { path: '/cart', name: 'cart', component: () => import('@/views/Cart.vue') },
  { path: '/favorites', name: 'favorites', component: () => import('@/views/Favorites.vue') },
  { path: '/privacy', name: 'privacy', component: () => import('@/views/PrivacyInfo.vue') },
    { path: '/about', name: 'about', component: () => import('@/views/About.vue') },
      { path: '/certificate', name: 'certificate', component: () => import('@/views//Certificate.vue') },
  { path: '/blog', name: 'blog', component: () => import('@/views/Blog.vue') },
    { path: '/legalinfo', name: 'legalinfo', component: () => import('@/views/LegalInfo.vue') },
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