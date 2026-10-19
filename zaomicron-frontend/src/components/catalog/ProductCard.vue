<template>
  <article class="product-card" @click="$router.push(`/product/${product.id}`)">
    <div class="product-card__image">
      <img
        :src="currentImage"
        :alt="product.name"
        loading="lazy"
        @error="handleImageError"
      />
      <span
        class="product-card__badge"
        :class="inStock ? 'product-card__badge--ok' : 'product-card__badge--out'"
      >
        {{ inStock ? 'В наличии' : 'Нет в наличии' }}
      </span>
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
      <div class="product-card__price">{{ formattedPrice }}</div>
      <button
        class="product-card__cart-btn"
        :class="{ added: isAdded }"
        :disabled="!inStock"
        @click.stop="handleAddToCart"
      >
        <i :class="isAdded ? 'fas fa-check' : 'fas fa-cart-plus'" />
        {{ isAdded ? 'Добавлено' : 'В корзину' }}
      </button>
    </div>
  </article>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useFavoritesStore } from '@/stores/favorites'
import logoFallback from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'
import { getProductImageCandidates, isProductInStock } from '@/utils/product'

const props = defineProps({
  product: { type: Object, required: true }
})

const cartStore = useCartStore()
const favoritesStore = useFavoritesStore()

const isFavorite = computed(() => favoritesStore.isFavorite(props.product.id))
const isAdded = ref(false)
const hover = ref(false)

const inStock = computed(() => isProductInStock(props.product))

const images = computed(() => getProductImageCandidates(props.product))

const currentImage = computed(() => {
  if (images.value.length === 0) return logoFallback
  // при наведении показываем второе изображение, если есть
  if (hover.value && images.value.length > 1) {
    return resolveMediaUrl(images.value[1], logoFallback)
  }
  return resolveMediaUrl(images.value[0], logoFallback)
})

const formattedPrice = computed(() => {
  const price = Number(props.product.price)
  if (!Number.isFinite(price) || price === 0) return 'по запросу'
  return new Intl.NumberFormat('ru-RU').format(price) + ' ₽'
})

function handleAddToCart() {
  if (!inStock.value) return
  cartStore.addToCart(props.product, 1)
  isAdded.value = true
  setTimeout(() => { isAdded.value = false }, 600)
}

const toggleFavorite = () => favoritesStore.toggleFavorite(props.product.id)
const handleImageError = (e) => {
  if (e?.target) e.target.src = logoFallback
}
</script>

<style scoped>

.product-card__image img {
  transition: opacity 0.25s ease;
}

.product-card {
  background: #fff;
  border: 1px solid #dfe5df;
  border-radius: 14px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
    box-shadow 0.28s cubic-bezier(0.22, 1, 0.36, 1),
    border-color 0.28s cubic-bezier(0.22, 1, 0.36, 1);
  display: flex;
  flex-direction: column;
  height: 100%;
  position: relative;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.14);
  border-color: rgba(81, 174, 69, 0.4);
}

.product-card__image {
  position: relative;
  width: 100%;
  height: 210px;
  overflow: hidden;
  background: linear-gradient(180deg, #f7faf7 0%, #eef4ee 100%);
}

.product-card__image img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 12px;
  transition: transform 0.36s ease;
}

.product-card:hover .product-card__image img {
  transform: scale(1.06);
}

.product-card__badge {
  position: absolute;
  left: 10px;
  top: 10px;
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  padding: 5px 9px;
  border-radius: 999px;
  letter-spacing: 0.2px;
}

.product-card__badge--ok {
  background: rgba(81, 174, 69, 0.9);
}

.product-card__badge--out {
  background: rgba(198, 40, 40, 0.88);
}

.product-card__favorite {
  position: absolute;
  top: 8px;
  right: 8px;
  background: rgba(255, 255, 255, 0.85);
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.2s, color 0.2s, transform 0.22s ease;
  color: #d32f2f;
  font-size: 16px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.product-card__favorite:hover {
  background: #fff;
  color: #b71c1c;
  transform: scale(1.08);
}

.product-card__body {
  padding: 16px 16px 14px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-card__name {
  font-size: 16px;
  font-weight: 600;
  color: #333;
  margin: 0 0 8px;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-card__sku {
  font-size: 12px;
  color: #888;
  margin-bottom: 8px;
}

.product-card__price {
  font-size: 22px;
  font-weight: 700;
  color: #51ae45;
  margin-top: auto;
  margin-bottom: 10px;
}

.product-card__cart-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 10px;
  background: #e8f5e9;
  color: #2e7d32;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.24s ease, color 0.24s ease, transform 0.2s ease,
    opacity 0.35s ease, visibility 0.35s ease;
}

.product-card__cart-btn.added {
  background: #51ae45;
  color: white;
}

.product-card__cart-btn:hover {
  background: #51ae45;
  color: #fff;
  transform: translateY(-1px);
}

.product-card__cart-btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.product-card__cart-btn i {
  font-size: 16px;
}

@media (hover: hover) {
  .product-card__cart-btn {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
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
}

@media (max-width: 768px) {
  .product-card__image {
    height: 180px;
  }

  .product-card__cart-btn {
    position: static;
    opacity: 1;
    visibility: visible;
    transform: none;
    margin-top: 8px;
  }
}
</style>