<template>
  <div>
    <HeroSlider :slides="slides" />
    <PromoGrid :items="promoItems" />
    <Marquee :items="tickerItems" />
    <ProductGrid :products="featuredProducts" />
  </div>
</template>

<script>
import axios from 'axios';
import HeroSlider from '@/components/home/HeroSlider.vue';
import PromoGrid from '@/components/home/PromoGrid.vue';
import Marquee from '@/components/home/Marquee.vue';
import ProductGrid from '@/components/home/ProductGrid.vue';

export default {
    name: 'Home',
  components: { HeroSlider, PromoGrid, Marquee, ProductGrid },
  data() {
    return {
      slides: [],
      promoItems: [],
      tickerItems: [],
      featuredProducts: []
    };
  },
  async created() {
    await Promise.all([
      this.fetchSlides(),
      this.fetchPromo(),
      this.fetchTicker(),
      this.fetchFeaturedProducts()
    ]);
  },
  methods: {
    async fetchSlides() {
      try {
        const { data } = await axios.get('/api/slider');
        this.slides = data;
      } catch (e) { console.error(e); }
    },
    async fetchPromo() {
      try {
        const { data } = await axios.get('/api/promo');
        this.promoItems = data;
      } catch (e) { console.error(e); }
    },
    async fetchTicker() {
      try {
        const { data } = await axios.get('/api/ticker');
        this.tickerItems = data;
      } catch (e) { console.error(e); }
    },
    async fetchFeaturedProducts() {
      try {
        const { data } = await axios.get('/api/catalog/featured?limit=9');
        this.featuredProducts = Array.isArray(data) ? data : [];
      } catch (e) { console.error(e); }
    }
  }
};
</script>