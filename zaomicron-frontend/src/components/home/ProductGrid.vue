<template>
  <div class="main-products-grid">
    <div v-for="product in products" :key="product.id" class="main-product-item">
      <a :href="product.link">
        <img :src="product.image" :alt="product.name">
      </a>
      <a :href="product.link" class="product-name">{{ product.name }}</a>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'ProductGrid',
  data() {
    return {
      products: []
    };
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      try {
        const { data } = await axios.get('/api/main/products');
        this.products = data;
      } catch (err) {
        console.error('Ошибка загрузки главных товаров', err);
      }
    }
  }
};
</script>

<style scoped>
/* Стили уже в global.css, но можно оставить специфические */
.main-products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 20px;
}
.main-product-item {
  text-align: center;
}
.main-product-item img {
  max-width: 100%;
  height: auto;
}
</style>