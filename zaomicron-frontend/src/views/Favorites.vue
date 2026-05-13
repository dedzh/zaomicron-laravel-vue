<template>
  <div class="favorites-page container">
    <h1>Избранное</h1>
    <div v-if="loading" class="loading-spinner">
      <i class="fas fa-spinner fa-pulse"></i> Загрузка...
    </div>
    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
      <button class="btn btn-primary" @click="fetchFavorites">Повторить</button>
    </div>
    <div v-else-if="favoriteProducts.length === 0" class="empty-favorites">
      <p>У вас нет избранных товаров</p>
      <router-link to="/catalog" class="btn btn-primary">Перейти в каталог</router-link>
    </div>
    <div v-else class="products-grid">
      <ProductCard
        v-for="product in favoriteProducts"
        :key="product.id"
        :product="product"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import ProductCard from '@/components/catalog/ProductCard.vue';
import { useFavoritesStore } from '@/stores/favorites';

export default {
  name: 'Favorites',
  components: { ProductCard },
  data() {
    return {
      favoriteProducts: [],
      loading: false,
      error: null,
    };
  },
  computed: {
    favoritesStore() {
      return useFavoritesStore();
    }
  },
  mounted() {
    this.fetchFavorites();
  },
  methods: {
    async fetchFavorites() {
      this.loading = true;
      this.error = null;
      try {
        const ids = this.favoritesStore.items;
        if (!ids.length) {
          this.favoriteProducts = [];
          return;
        }
        const response = await axios.get('/api/catalog/favorites', {
          params: { ids },
          paramsSerializer: params => params.ids.map(id => `ids[]=${id}`).join('&')
        });
        this.favoriteProducts = response.data;
      } catch (err) {
        console.error('Ошибка загрузки избранных товаров', err);
        this.error = 'Не удалось загрузить избранные товары. Попробуйте позже.';
      } finally {
        this.loading = false;
      }
    }
  },
};
</script>

<style scoped>
.favorites-page { padding: 20px 0; }
.empty-favorites { text-align: center; padding: 50px; }
</style>