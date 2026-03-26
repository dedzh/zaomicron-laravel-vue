<template>
  <div>
    <HeroSlider />
    <PromoGrid />
    <Marquee />
    <ProductGrid />
  </div>
</template>

<script>
import HeroSlider from '@/components/home/HeroSlider.vue';
import PromoGrid from '@/components/home/PromoGrid.vue';
import Marquee from '@/components/home/Marquee.vue';
import ProductGrid from '@/components/home/ProductGrid.vue';

export default {
  name: 'Home',
  components: {
    HeroSlider,
    PromoGrid,
    Marquee,
    ProductGrid
  }
};
</script>