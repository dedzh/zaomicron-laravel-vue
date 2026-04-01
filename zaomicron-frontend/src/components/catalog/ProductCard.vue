<template>
  <div class="product-card">
    <div class="product-image">
      <img v-if="image" :src="image" :alt="name">
      <span v-else>{{ emoji }}</span>
      <button 
        class="favorite-btn" 
        :class="{ active: isFavorite }"
        @click.stop="toggleFavorite"
      >
        ❤️
      </button>
    </div>
    <div class="product-info">
      <h3><router-link :to="'/product/' + id">{{ name }}</router-link></h3>
      <div class="product-sku">Арт. {{ sku }}</div>
      <div class="product-price">{{ price }} ₽</div>
      <button class="btn btn-primary" @click="addToCart">В корзину</button>
    </div>
  </div>
</template>

<script>
import { useFavoritesStore } from '@/stores/favorites';

export default {
  name: 'ProductCard',
  props: {
    id: [String, Number],
    name: String,
    sku: String,
    price: [String, Number],
    emoji: { type: String, default: '🛠️' },
    image: String,
  },
  computed: {
    isFavorite() {
      const favoritesStore = useFavoritesStore();
      return favoritesStore.isFavorite(this.id);
    }
  },
  methods: {
    addToCart() {
      this.$emit('add-to-cart', {
        id: this.id,
        name: this.name,
        sku: this.sku,
        price: this.price,
        image: this.image,
        emoji: this.emoji
      });
    },
    toggleFavorite() {
      const favoritesStore = useFavoritesStore();
      favoritesStore.toggleFavorite(this.id);
    }
  }
};
</script>