<template>
  <section v-if="displayList.length" class="product-grid">
    <div
      v-for="(product, idx) in displayList"
      :key="product.id"
      class="product-grid__item"
      :style="{ animationDelay: `${idx * 50}ms` }"
      @click="$router.push(`/product/${product.id}`)"
    >
      <div class="product-grid__image">
        <img
          :src="productImageUrl(product)"
          :alt="product.name"
          loading="lazy"
          @error="(e) => handleImageError(e)"
        />
      </div>
      <div class="product-grid__info">
        <router-link
          :to="'/product/' + product.id"
          class="product-grid__name"
        >{{ product.name }}</router-link>
        <span class="product-grid__price">{{ formatProductPriceRub(product.price) }}</span>
      </div>
    </div>
  </section>
  <p v-else class="product-grid__empty">Нет товаров для отображения</p>
</template>

<script setup>
import { computed } from 'vue'
import imageFallback from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'
import { formatProductPriceRub } from '@/utils/price'

const props = defineProps({
  products: {
    type: Array,
    default: () => []
  }
})

const displayList = computed(() => Array.isArray(props.products) ? props.products : [])

const productImageUrl = (product) => {
  return resolveMediaUrl(product.image || product.image_url, imageFallback)
}

const handleImageError = (event) => {
  if (event?.target) event.target.src = imageFallback
}
</script>

<style scoped>
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 160px), 1fr));
  gap: 18px;
  margin: 24px 0;
}

.product-grid__item {
  background: #fff;
  border: 1px solid #eef2ee;
  border-radius: 12px;
  padding: 12px;
  text-align: center;
  cursor: pointer;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  animation: fadeUp 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.product-grid__item:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  border-color: rgba(81,174,69,0.3);
}

.product-grid__image {
  width: 100%;
  aspect-ratio: 1/1;
  background: #f8fcf8;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-grid__image img {
  max-width: 90%;
  max-height: 90%;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.product-grid__item:hover .product-grid__image img {
  transform: scale(1.05);
}

.product-grid__name {
  font-family: 'Mulish', sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #111;
  text-decoration: none;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  margin-bottom: 6px;
}

.product-grid__name:hover {
  color: #51ae45;
}

.product-grid__price {
  font-family: 'Arimo', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #156135;
}

.product-grid__empty {
  text-align: center;
  color: #888;
  padding: 32px;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 600px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }
  .product-grid__name {
    font-size: 14px;
  }
  .product-grid__price {
    font-size: 16px;
  }
}
</style>