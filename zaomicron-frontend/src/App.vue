<template>
  <div>
    <InfoBar />
    <Header />
    <Navigation />
    <router-view />
    <Footer />
    <CookieConsent />
 
  </div>
</template>

<script>
import InfoBar from '@/components/layout/InfoBar.vue';
import Header from '@/components/layout/Header.vue';
import Navigation from '@/components/layout/Navigation.vue';
import Footer from '@/components/layout/Footer.vue';
import { useCartStore } from '@/stores/cart';
import CookieConsent from '@/components/ui/CookieConsent.vue';

export default {
  name: 'App',
  components: {
    InfoBar,
    Header,
    Navigation,
    CookieConsent,
    Footer

  },
  mounted() {
    useCartStore().loadFromLocalStorage();
  },
};
</script>

<style>
@import './style.css';
</style>