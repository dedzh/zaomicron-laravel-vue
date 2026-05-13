<template>
  <div class="home-page animated-fade">
    <HeroSlider />
    <section class="home-section animated-rise">
      <PromoGrid />
    </section>
    <section class="home-section animated-rise" style="animation-delay: 80ms">
      <Marquee />
    </section>
    <section class="home-section animated-rise" style="animation-delay: 140ms">
      <ProductGrid :products="featuredProducts" />
    </section>
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
    return { featuredProducts: [] };
  },
  async created() {
    await this.fetchFeaturedProducts();
  },
  methods: {
    async fetchFeaturedProducts() {
      try {
        const { data } = await axios.get('/api/catalog/featured?limit=9');
        this.featuredProducts = Array.isArray(data) ? data : [];
      } catch (e) {
        console.error(e);
      }
    }
  }
};
</script>

<style scoped>
.home-page {
  padding-bottom: 28px;
}

.home-section {
  margin-top: 8px;
}
</style>