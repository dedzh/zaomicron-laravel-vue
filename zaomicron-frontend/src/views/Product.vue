<template>
  <main class="product-page">
    <div v-if="loading" class="product-page__loader">Загрузка...</div>
    <div v-else-if="!product" class="product-page__error">Товар не найден</div>

    <template v-else>
      <div class="product-layout">
        <div class="product-gallery">
          <div class="product-gallery__main">
            <img
              :src="mainImageSrc"
              :alt="product.name"
              @error="handleMainImageError"
            />
          </div>
        </div>

        <div class="product-info">
          <h1 class="product-info__title">{{ product.name }}</h1>
          <div class="product-info__sku">Артикул: {{ product.sku }}</div>

          <div class="product-info__price">{{ formatPrice(product.price) }} ₽</div>

          <div class="product-info__stock" :class="{ low: product.stock < 10 }">
            <i class="fas fa-check-circle" /> В наличии: {{ product.stock }} шт.
          </div>

          <div class="product-info__quantity">
            <label class="quantity-label">Количество:</label>
            <div class="quantity-control">
              <button class="quantity-btn" @click="decrement">−</button>
              <input
                type="number"
                class="quantity-input"
                v-model.number="quantity"
                min="1"
                :max="product.stock"
              />
              <button class="quantity-btn" @click="increment">+</button>
            </div>
          </div>

          <div class="product-info__actions">
            <button class="btn btn-primary btn-lg" @click="addToCart">
              <i class="fas fa-shopping-cart" /> В корзину
            </button>
            <button class="btn btn-icon" @click="toggleFavorite">
              <i :class="isFavorite ? 'fas fa-heart' : 'far fa-heart'" />
            </button>
          </div>

          <div class="product-info__desc">
            <h3 class="h3">Краткое описание</h3>
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
        <div class="tabs-body">
          <div v-show="activeTab === 'description'" class="tab-panel" v-html="product.fullDescription" />
          <div v-show="activeTab === 'specs'" class="tab-panel">
            <table class="specs-table">
              <tr v-for="(value, key) in product.specs" :key="key">
                <th>{{ key }}</th>
                <td>{{ value }}</td>
              </tr>
            </table>
          </div>
          <div v-show="activeTab === 'docs'" class="tab-panel">
            <ul class="docs-list">
              <li v-for="doc in product.docs" :key="doc.name">
                <i class="fas fa-file-pdf" />
                <a :href="doc.url" target="_blank" rel="noopener">{{ doc.name }}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </template>
  </main>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useFavoritesStore } from '@/stores/favorites'
import { addToCart as addToCartUtil } from '@/utils/cart'
import logoFallback from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'

const route = useRoute()
const favoritesStore = useFavoritesStore()

const product = ref(null)
const loading = ref(true)
const quantity = ref(1)
const activeTab = ref('description')
const tabs = [
  { id: 'description', name: 'Описание' },
  { id: 'specs', name: 'Характеристики' },
  { id: 'docs', name: 'Документация' }
]

const mainImageSrc = computed(() => {
  if (!product.value) return logoFallback
  return resolveMediaUrl(product.value.image || product.value.image_url, logoFallback)
})

const isFavorite = computed(() => {
  return product.value ? favoritesStore.isFavorite(product.value.id) : false
})

const fetchProduct = async (id) => {
  loading.value = true
  try {
    const { data } = await axios.get(`/api/catalog/product/${id}`)
    product.value = data
  } catch (e) {
    console.error('Ошибка загрузки товара', e)
    product.value = null
  } finally {
    loading.value = false
  }
}

const formatPrice = (price) => {
  const num = Number(price)
  return Number.isFinite(num) ? new Intl.NumberFormat('ru-RU').format(num) : price
}

const increment = () => {
  if (product.value && quantity.value < product.value.stock) quantity.value++
}
const decrement = () => {
  if (quantity.value > 1) quantity.value--
}
const addToCart = () => {
  if (!product.value) return
  addToCartUtil(
    {
      id: product.value.id,
      name: product.value.name,
      sku: product.value.sku,
      price: product.value.price,
      image: product.value.image || logoFallback
    },
    quantity.value
  )
}
const toggleFavorite = () => {
  if (product.value) favoritesStore.toggleFavorite(product.value.id)
}
const handleMainImageError = (e) => {
  if (e?.target) e.target.src = logoFallback
}

watch(() => route.params.id, (newId) => {
  if (newId) fetchProduct(newId)
}, { immediate: true })
</script>

<style scoped>
.product-page {
  max-width: 1280px;
  margin: 0 auto;
  padding: 32px 16px;
  font-family: 'Arimo', sans-serif;
}

.product-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 48px;
  align-items: start;
  margin-bottom: 48px;
}

.product-gallery__main {
  background: #f8fcf8;
  border: 1px solid #eef2ee;
  border-radius: 16px;
  padding: 24px;
  aspect-ratio: 1/1;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-gallery__main img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.fallback {
  opacity: 0.8;
  max-width: 70%;
}

.product-info__title {
  font-family: 'Mulish', sans-serif;
  font-size: 32px;
  font-weight: 800;
  color: #111;
  margin: 0 0 12px;
  line-height: 1.3;
}

.product-info__sku {
  font-size: 14px;
  color: #666;
  margin-bottom: 20px;
}

.product-info__price {
  font-family: 'Mulish', sans-serif;
  font-size: 34px;
  font-weight: 800;
  color: #156135;
  margin: 20px 0;
}

.product-info__stock {
  font-size: 15px;
  color: #51ae45;
  margin-bottom: 24px;
}
.product-info__stock.low {
  color: #e53935;
}

.product-info__quantity {
  margin-bottom: 28px;
}
.quantity-label {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 6px;
  display: block;
  color: #333;
}

.quantity-control {
  display: flex;
  align-items: center;
  gap: 0;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  width: fit-content;
}

.quantity-btn {
  width: 40px;
  height: 40px;
  border: none;
  background: #f5f5f5;
  font-size: 20px;
  cursor: pointer;
  transition: background 0.2s;
  color: #333;
}
.quantity-btn:hover {
  background: #e0e0e0;
}

.quantity-input {
  width: 60px;
  text-align: center;
  border: none;
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
  font-size: 16px;
  padding: 0;
}

.product-info__actions {
  display: flex;
  gap: 12px;
  margin-bottom: 30px;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  border-radius: 8px;
  font-family: 'Arimo', sans-serif;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
}
.btn-primary {
  background: #51ae45;
  color: #fff;
}
.btn-primary:hover {
  background: #156135;
}
.btn-lg {
  padding: 14px 28px;
  font-size: 16px;
}
.btn-icon {
  background: #f5f5f5;
  color: #d32f2f;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  font-size: 20px;
}
.btn-icon:hover {
  background: #fff0f0;
}

.product-info__desc h3 {
  font-family: 'Mulish', sans-serif;
  font-weight: 700;
  font-size: 18px;
  margin-bottom: 8px;
}
.product-info__desc p {
  color: #555;
  line-height: 1.6;
}

/* Табы */
.product-tabs {
  border: 1px solid #eef2ee;
  border-radius: 14px;
  overflow: hidden;
}
.tabs-header {
  display: flex;
  background: #f8fcf8;
  border-bottom: 1px solid #eef2ee;
}
.tab-btn {
  flex: 1;
  padding: 14px;
  background: transparent;
  border: none;
  font-family: 'Mulish', sans-serif;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  color: #555;
  transition: background 0.2s, color 0.2s;
}
.tab-btn.active {
  background: #fff;
  color: #51ae45;
  border-bottom: 2px solid #51ae45;
}
.tab-btn:hover:not(.active) {
  background: #f2f8f2;
}
.tabs-body {
  padding: 24px;
  background: #fff;
}
.tab-panel {
  animation: fadeIn 0.2s ease;
}
.specs-table {
  width: 100%;
  border-collapse: collapse;
}
.specs-table th,
.specs-table td {
  padding: 10px 12px;
  border-bottom: 1px solid #eee;
  text-align: left;
}
.specs-table th {
  background: #f9f9f9;
  font-weight: 600;
}
.docs-list {
  list-style: none;
  padding: 0;
}
.docs-list li {
  margin-bottom: 8px;
}
.docs-list a {
  color: #51ae45;
  text-decoration: none;
  margin-left: 8px;
}
.docs-list a:hover {
  text-decoration: underline;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(4px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 900px) {
  .product-layout {
    grid-template-columns: 1fr;
    gap: 32px;
  }
  .product-info__title {
    font-size: 26px;
  }
  .product-info__price {
    font-size: 28px;
  }
}
</style>