<template>
  <div class="promo-grid-wrapper">
    <h2 class="section-title">Популярные категории</h2>
    <div class="promo-grid">
      <a
        v-for="item in promoItems"
        :key="item.id"
        :href="item.link"
        class="promo-grid-item"
      >
        <div class="grid-item-image">
          <img :src="item.image" :alt="item.title">
        </div>
        <div class="grid-item-content">
          <h3>{{ item.title }}</h3>
          <p>{{ item.description }}</p>
          <span class="grid-item-link">Смотреть каталог →</span>
        </div>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PromoGrid',
  data() {
    return {
      promoItems: [
        {
          id: 1,
          link: './online/76259.html',
          image: '@/assets/micrometer.png',
          title: 'Микрометры',
          description: 'Точные измерения до микрометра'
        },
        {
          id: 2,
          link: '/online/76255.html',
          image: './assets/slider_5.jpgpressure-gauge.png',
          title: 'Индикаторы',
          description: 'Часовые и цифровые индикаторы'
        },
        {
          id: 3,
          link: '/online/76488.html',
          image: './assets/thermometers.png',
          title: 'Термометр',
          description: 'Промышленные и нефтяные калибры'
        },
        {
          id: 4,
          link: '/online/76278.html',
          image: './assets/vernier-caliper.png',
          title: 'Штангенциркули',
          description: 'Точные измерения длин и диаметров'
        }
      ]
    };
  }
};
</script>

<style>
@import '@/css/style.css'; 
</style>