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
import axios from 'axios';

export default {
  name: 'PromoGrid',
  data() {
    return {
      promoItems: []
    };
  },
  mounted() {
    this.fetchPromo();
  },
  methods: {
    async fetchPromo() {
      try {
        const { data } = await axios.get('/api/main/promo');
        this.promoItems = data;
      } catch (err) {
        console.error('Ошибка загрузки промо', err);
      }
    }
  }
};
</script>

<style scoped>
/* Оставить существующие стили, но добавить адаптивность при необходимости */
</style>