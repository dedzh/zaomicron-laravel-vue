<template>
  <article class="product-card" @click="$router.push(`/product/${product.id}`)">
    <div class="product-card__image">
      <img
        :src="productImage"
        :alt="product.name"
        loading="lazy"
        @error="handleImageError"
      />
      <span class="product-card__badge">В наличии</span>
      <button
        class="product-card__favorite"
        :aria-label="isFavorite ? 'Убрать из избранного' : 'Добавить в избранное'"
        @click.stop="toggleFavorite"
      >
        <i :class="isFavorite ? 'fas fa-heart' : 'far fa-heart'" />
      </button>
    </div>

    <div class="product-card__body">
      <h3 class="product-card__name">{{ product.name }}</h3>
      <span v-if="product.sku" class="product-card__sku">Арт. {{ product.sku }}</span>
      <div class="product-card__price">{{ formatProductPriceRub(product.price) }}</div>
      <button class="product-card__cart-btn" @click.stop="addToCart">
        <i class="fas fa-cart-plus" /> В корзину
      </button>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useFavoritesStore } from '@/stores/favorites'
import logoFallback from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'
import { formatProductPriceRub } from '@/utils/price'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()
const favoritesStore = useFavoritesStore()

const isFavorite = computed(() => favoritesStore.isFavorite(props.product.id))

// Формируем полный URL изображения
const productImage = computed(() => {
  return resolveMediaUrl(props.product.image || props.product.image_url, logoFallback)
})

const addToCart = () => cartStore.addToCart(props.product, 1)
const toggleFavorite = () => favoritesStore.toggleFavorite(props.product.id)
const handleImageError = (e) => {
  if (e?.target) e.target.src = logoFallback
}
</script>

<style scoped>
.product-card {
  background: #fff;
  border: 1px solid #eef2ee;
  border-radius: 14px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1),
              box-shadow 0.3s ease,
              border-color 0.3s ease;
  display: flex;
  flex-direction: column;
  height: 100%;
  position: relative;
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 14px 32px rgba(0, 0, 0, 0.09);
  border-color: rgba(81, 174, 69, 0.3);
}

.product-card__image {
  position: relative;
  width: 100%;
  aspect-ratio: 1 / 1;
  max-height: 260px;
  overflow: hidden;
  background: linear-gradient(180deg, #f8fcf8 0%, #f2f8f2 100%);
}

.product-card__image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 16px;
  transition: transform 0.45s ease;
}

.product-card:hover .product-card__image img {
  transform: scale(1.07);
}

.product-card__badge {
  position: absolute;
  left: 12px;
  top: 12px;
  background: #51ae45;
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 5px 12px;
  border-radius: 100px;
  letter-spacing: 0.3px;
  z-index: 2;
}

.product-card__favorite {
  position: absolute;
  top: 12px;
  right: 12px;
  background: rgba(255, 255, 255, 0.92);
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #d32f2f;
  font-size: 18px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: background 0.2s, color 0.2s, transform 0.2s;
  z-index: 3;
}

.product-card__favorite:hover {
  background: #fff;
  color: #b71c1c;
  transform: scale(1.1);
}

.product-card__body {
  padding: 18px 16px 0;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-card__name {
  font-family: 'Mulish', 'Arial', sans-serif;
  font-weight: 700;
  font-size: 16px;
  color: #111;
  line-height: 1.35;
  margin: 0 0 6px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-decoration: none;
}

.product-card__sku {
  font-family: 'Arimo', 'Helvetica', sans-serif;
  font-size: 13px;
  color: #777;
  margin-bottom: 12px;
}

.product-card__price {
  font-family: 'Arimo', sans-serif;
  font-size: 22px;
  font-weight: 700;
  color: #156135;
  margin-top: auto;
  margin-bottom: 70px; /* резервируем место под выезжающую кнопку */
}

.product-card__cart-btn {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 14px;
  background: #51ae45;
  color: #fff;
  border: none;
  font-family: 'Arimo', sans-serif;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: opacity 0.35s ease, visibility 0.35s ease, transform 0.35s ease, background 0.2s;
  opacity: 0;
  visibility: hidden;
  transform: translateY(100%);
  z-index: 2;
}

.product-card:hover .product-card__cart-btn {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.product-card__cart-btn:hover {
  background: #156135;
}

/* Адаптивность */
@media (max-width: 768px) {
  .product-card__image {
    max-height: 220px;
  }
  .product-card__name {
    font-size: 15px;
  }
  .product-card__price {
    font-size: 20px;
    margin-bottom: 60px;
  }
}
</style>