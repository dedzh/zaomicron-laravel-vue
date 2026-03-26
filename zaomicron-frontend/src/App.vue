<template>
  <div>
    <TrackerPixel />
    <InfoBar />
    <Header />
    <Navigation />
    <router-view />
    <Footer />
 
  </div>
</template>

<script>
import TrackerPixel from '@/components/ui/TrackerPixel.vue';
import InfoBar from '@/components/layout/InfoBar.vue';
import Header from '@/components/layout/Header.vue';
import Navigation from '@/components/layout/Navigation.vue';
import Footer from '@/components/layout/Footer.vue';


export default {
  name: 'App',
  components: {
    TrackerPixel,
    InfoBar,
    Header,
    Navigation,
    Footer

  }
};
</script>

<style>
@import './style.css';
</style>