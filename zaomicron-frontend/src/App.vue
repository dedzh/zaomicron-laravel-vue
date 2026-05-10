<template>
  <div>
    <InfoBar />
    <Header />
    <Navigation />
    <router-view />
    <Footer />
 
  </div>
</template>

<script>
import InfoBar from '@/components/layout/InfoBar.vue';
import Header from '@/components/layout/Header.vue';
import Navigation from '@/components/layout/Navigation.vue';
import Footer from '@/components/layout/Footer.vue';
import { useCartStore } from '@/stores/cart';

export default {
  name: 'App',
  components: {
    InfoBar,
    Header,
    Navigation,
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