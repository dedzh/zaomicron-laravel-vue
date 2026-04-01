<template>
  <div class="product-page" v-if="product">
    <div class="product-detail-layout">
      <div class="product-gallery">
        <div class="product-main-image">
          <i v-if="!product.image" :class="product.icon"></i>
          <img v-else :src="product.image" :alt="product.name" style="width:100%">
        </div>
        <div class="product-thumbnails">
          <div v-for="n in 3" :key="n" class="thumbnail">
            <i class="fas fa-image"></i>
          </div>
        </div>
      </div>

      <div class="product-info">
        <h1 class="product-title">{{ product.name }}</h1>
        <div class="product-sku">Артикул: {{ product.sku }}</div>
        <div class="product-price">{{ formatPrice(product.price) }} ₽</div>
        <div class="product-stock" :class="{ 'low-stock': product.stock < 10 }">
          <i class="fas fa-check-circle"></i> В наличии: {{ product.stock }} шт.
        </div>

        <div class="product-quantity">
          <div class="quantity-label">Количество:</div>
          <div class="quantity-control">
            <button class="quantity-btn" @click="decrement">−</button>
            <input type="number" v-model.number="quantity" class="quantity-input" min="1" :max="product.stock">
            <button class="quantity-btn" @click="increment">+</button>
          </div>
        </div>

        <div class="product-actions">
          <button class="btn btn-primary btn-large" @click="addToCart">
            <i class="fas fa-shopping-cart"></i> Добавить в корзину
          </button>
          <button class="btn btn-secondary btn-icon" @click="toggleFavorite">
            <i :class="isFavorite ? 'fas fa-heart' : 'far fa-heart'"></i>
          </button>
        </div>

        <div class="product-short-desc">
          <h3>Краткое описание</h3>
          <p>{{ product.shortDescription }}</p>
        </div>
      </div>
    </div>

    <div class="product-tabs">
      <div class="tabs-header">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          class="tab-btn"
          :class="{ active: activeTab === tab.id }"
          @click="activeTab = tab.id"
        >
          {{ tab.name }}
        </button>
      </div>
      <div class="tabs-content">
        <div v-show="activeTab === 'description'" class="tab-pane" v-html="product.fullDescription"></div>
        <div v-show="activeTab === 'specs'" class="tab-pane">
          <table class="specs-table">
            <tr v-for="(value, key) in product.specs" :key="key">
              <th>{{ key }}</th>
              <td>{{ value }}</td>
            </tr>
          </table>
        </div>
        <div v-show="activeTab === 'docs'" class="tab-pane">
          <ul class="docs-list">
            <li v-for="doc in product.docs" :key="doc.name">
              <i class="fas fa-file-pdf"></i>
              <a :href="doc.url" target="_blank">{{ doc.name }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div v-else-if="loading">Загрузка...</div>
  <div v-else>Товар не найден</div>
</template>

<script>
import axios from 'axios';
import { addToCart as addToCartUtil } from '@/utils/cart';

export default {
  data() {
    return {
      product: null,
      loading: true,
      quantity: 1,
      activeTab: 'description',
      isFavorite: false,
      tabs: [
        { id: 'description', name: 'Описание' },
        { id: 'specs', name: 'Характеристики' },
        { id: 'docs', name: 'Документация' },
      ],
    };
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      async handler(id) {
        if (id) await this.fetchProduct(id);
      },
    },
  },
  methods: {
    async fetchProduct(id) {
      this.loading = true;
      try {
        const response = await axios.get(`/api/catalog/product/${id}`);
        this.product = response.data;
      } catch (error) {
        console.error('Ошибка загрузки товара', error);
      } finally {
        this.loading = false;
      }
    },
    increment() {
      if (this.quantity < this.product.stock) this.quantity++;
    },
    decrement() {
      if (this.quantity > 1) this.quantity--;
    },
    addToCart() {
      addToCartUtil(
        {
          id: this.product?.id,
          name: this.product?.name,
          sku: this.product?.sku,
          price: this.product?.price,
          emoji: this.product?.emoji
        },
        this.quantity
      );
    },
    toggleFavorite() {
      this.isFavorite = !this.isFavorite;
    },
    formatPrice(price) {
      return new Intl.NumberFormat('ru-RU').format(price);
    },
  },
};
</script>

<style scoped>
/* Ваши стили из исходного файла */
</style>