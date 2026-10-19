<template>
  <div>
    <InfoBar />
    <Header />
    <Navigation />
    <router-view />
    <Footer />
    <CookieConsent />
    <CartToast />
  </div>
</template>

<script>
import InfoBar from '@/components/layout/InfoBar.vue';
import Header from '@/components/layout/Header.vue';
import Navigation from '@/components/layout/Navigation.vue';
import Footer from '@/components/layout/Footer.vue';
import { useCartStore } from '@/stores/cart';
import CookieConsent from '@/components/ui/CookieConsent.vue';
import CartToast from '@/components/ui/CartToast.vue';

export default {
  name: 'App',
  components: {
    InfoBar,
    Header,
    Navigation,
    Footer,
    CookieConsent,
    CartToast      
  },
  mounted() {
    useCartStore().loadFromLocalStorage();
  },
};
</script>

<style>
@import './style.css';
</style>