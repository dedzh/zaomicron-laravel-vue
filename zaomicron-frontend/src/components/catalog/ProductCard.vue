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
export default {
  name: 'ProductCard',
  props: {
    id: [String, Number],
    name: String,
    sku: String,
    price: [String, Number],
    emoji: String,
    image: String,
    isFavorite: Boolean
  },
  methods: {
    addToCart() {
      this.$emit('add-to-cart', { id: this.id, name: this.name, price: this.price });
    },
    toggleFavorite() {
      this.$emit('toggle-favorite', this.id);
    }
  }
};
</script>

<style scoped>
.product-card {
  background: white;
  border: 1px solid #eaeef2;
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
}
.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.product-image {
  position: relative;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8f9fa;
}
.product-image img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}
.favorite-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: white;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  cursor: pointer;
  font-size: 18px;
  transition: all 0.2s;
}
.favorite-btn.active {
  color: #ff4444;
}
.product-info {
  padding: 15px;
}
.product-info h3 {
  font-size: 16px;
  margin-bottom: 5px;
}
.product-sku {
  color: #999;
  font-size: 12px;
  margin-bottom: 10px;
}
.product-price {
  font-weight: 700;
  color: #51AE45;
  margin-bottom: 15px;
}
</style>