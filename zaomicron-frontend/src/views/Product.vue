<template>
  <main class="product-page">
    <div v-if="loading" class="product-page__loader">
      <div class="loader-card">
        <div class="loader-card__spinner"></div>
        <div class="loader-card__text">Загрузка товара...</div>
      </div>
    </div>

    <div v-else-if="!product" class="product-page__error">
      <div class="error-card">
        <i class="fas fa-box-open"></i>
        <div class="error-card__title">Товар не найден</div>
        <div class="error-card__text">Проверьте ссылку или вернитесь в каталог.</div>
      </div>
    </div>

    <template v-else>
      <div class="product-layout">
        <div class="product-gallery" :class="{ 'product-gallery--single': !hasMultipleImages }">
          <div class="product-gallery__main">
            <button
              v-if="hasMultipleImages"
              class="gallery-arrow gallery-arrow--prev"
              type="button"
              aria-label="Предыдущее изображение"
              @click="prevImage"
            >
              <i class="fas fa-chevron-left"></i>
            </button>

            <transition name="fade-zoom" mode="out-in">
              <img
                :key="mainImageKey"
                class="product-gallery__image"
                :src="mainImageSrc"
                :alt="product.name"
                loading="eager"
                @error="handleMainImageError"
              />
            </transition>

            <button
              v-if="hasMultipleImages"
              class="gallery-arrow gallery-arrow--next"
              type="button"
              aria-label="Следующее изображение"
              @click="nextImage"
            >
              <i class="fas fa-chevron-right"></i>
            </button>

            <div v-if="hasMultipleImages" class="product-gallery__counter">
              {{ activeImageIndex + 1 }} / {{ productImages.length }}
            </div>
          </div>

          <div v-if="hasMultipleImages" class="product-gallery__thumbnails">
            <button
              v-for="(img, idx) in productImages"
              :key="`${idx}-${img}`"
              class="thumbnail-btn"
              :class="{ active: activeImageIndex === idx }"
              type="button"
              :aria-label="`Фото ${idx + 1}`"
              @click="activeImageIndex = idx"
            >
              <img
                :src="resolveMediaUrl(img, logoFallback)"
                :alt="`${product.name} — фото ${idx + 1}`"
                loading="lazy"
                class="thumbnail-img"
                @error="(e) => onThumbError(e)"
              />
            </button>
          </div>
        </div>

        <div class="product-info">
          <div class="product-info__topline">
            <span v-if="isInStock" class="stock-badge stock-badge--ok">
              <i class="fas fa-check-circle"></i>
              В наличии
            </span>
            <span v-else class="stock-badge stock-badge--warn">
              <i class="fas fa-triangle-exclamation"></i>
              Нет в наличии
            </span>
          </div>

          <h1 class="product-info__title">{{ product.name }}</h1>

          <div class="product-info__meta">
            <div v-if="product.sku" class="product-info__sku">Артикул: {{ product.sku }}</div>
            <div v-if="product.brand" class="product-info__brand">Бренд: {{ product.brand }}</div>
          </div>

          <div class="product-info__price-wrap">
            <div class="product-info__price">{{ formattedPrice }}</div>
            <div v-if="isInStock" class="product-info__stock">
              Остаток: {{ safeStock }} шт.
            </div>
          </div>

          <div class="product-info__quantity">
            <label class="quantity-label">Количество</label>
            <div class="quantity-control">
              <button class="quantity-btn" type="button" @click="decrement" :disabled="quantity <= 1">
                −
              </button>
              <input
                v-model.number="quantity"
                type="number"
                class="quantity-input"
                min="1"
                :max="maxQuantity"
                inputmode="numeric"
              />
              <button
                class="quantity-btn"
                type="button"
                @click="increment"
                :disabled="quantity >= maxQuantity"
              >
                +
              </button>
            </div>
          </div>

          <div class="product-info__actions">
            <button
              class="btn btn-primary btn-lg"
              type="button"
              :class="{ added: isAdded }"
              :disabled="!canAddToCart"
              @click="addToCart"
            >
              <i :class="isAdded ? 'fas fa-check' : 'fas fa-cart-shopping'"></i>
              {{ isAdded ? 'Добавлено' : 'В корзину' }}
            </button>

            <button class="btn btn-icon" type="button" @click="toggleFavorite">
              <i :class="isFavorite ? 'fas fa-heart' : 'far fa-heart'"></i>
            </button>
          </div>

          <div v-if="product.shortDescription" class="product-info__desc">
            <h3>Краткое описание</h3>
            <p>{{ product.shortDescription }}</p>
          </div>

          <div class="product-info__hint">
            <i class="fas fa-hand-pointer"></i>
            Используйте стрелки на фото или клавиши ← → для переключения изображений.
          </div>
        </div>
      </div>

      <section class="product-tabs">
        <div class="tabs-header">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            class="tab-btn"
            :class="{ active: activeTab === tab.id }"
            type="button"
            @click="activeTab = tab.id"
          >
            {{ tab.name }}
          </button>
        </div>

        <div class="tabs-body">
          <transition name="tab-fade" mode="out-in">
            <div v-if="activeTab === 'description'" key="description" class="tab-panel">
              <div
                v-if="product.fullDescription"
                class="content-html"
                v-html="product.fullDescription"
              ></div>
              <p v-else class="empty-state">Описание пока не заполнено.</p>
            </div>

            <div v-else-if="activeTab === 'specs'" key="specs" class="tab-panel">
              <table v-if="hasSpecs" class="specs-table">
                <tr v-for="(value, key) in normalizedSpecs" :key="key">
                  <th>{{ key }}</th>
                  <td>{{ value }}</td>
                </tr>
              </table>
              <p v-else class="empty-state">Характеристики пока не заполнены.</p>
            </div>

            <div v-else key="docs" class="tab-panel">
              <ul v-if="hasDocs" class="docs-list">
                <li v-for="doc in normalizedDocs" :key="doc.name">
                  <i class="fas fa-file-pdf"></i>
                  <a :href="doc.url" target="_blank" rel="noopener">{{ doc.name }}</a>
                </li>
              </ul>
              <p v-else class="empty-state">Документация пока не добавлена.</p>
            </div>
          </transition>
        </div>
      </section>

      <section v-if="relatedProducts.length" class="related-products">
        <div class="related-products__head">
          <div>
            <h2 class="related-products__title">Рекомендуем также</h2>
            <p class="related-products__subtitle">Похожие и дополняющие товары для этой позиции.</p>
          </div>

          <div v-if="relatedProducts.length > 3" class="related-products__controls">
            <button type="button" class="related-nav-btn" @click="scrollRelated('left')">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="related-nav-btn" @click="scrollRelated('right')">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>

        <div ref="relatedScrollRef" class="related-products__rail">
          <div
            v-for="rp in relatedProducts"
            :key="rp.id"
            class="related-products__item"
          >
            <ProductCard :product="rp" />
          </div>
        </div>
      </section>
    </template>
  </main>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import { useFavoritesStore } from '@/stores/favorites'
import { useCartStore } from '@/stores/cart'
import logoFallback from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'
import { getProductImageCandidates } from '@/utils/product'
import ProductCard from '@/components/catalog/ProductCard.vue'

const route = useRoute()
const favoritesStore = useFavoritesStore()
const cartStore = useCartStore()

const product = ref(null)
const relatedProducts = ref([])
const loading = ref(true)
const quantity = ref(1)
const activeTab = ref('description')
const isAdded = ref(false)
const activeImageIndex = ref(0)
const relatedScrollRef = ref(null)

const tabs = [
  { id: 'description', name: 'Описание' },
  { id: 'specs', name: 'Характеристики' },
  { id: 'docs', name: 'Документация' },
]

const normalizeImage = (img) => {
  if (!img) return ''
  if (typeof img === 'string') return img
  return img.url || img.src || img.image || img.path || ''
}

const productImages = computed(() => {
  if (!product.value) return []
  return getProductImageCandidates(product.value).map(normalizeImage).filter(Boolean)
})

const hasMultipleImages = computed(() => productImages.value.length > 1)

const mainImageSrc = computed(() => {
  if (productImages.value.length) {
    const index = Math.min(activeImageIndex.value, productImages.value.length - 1)
    return resolveMediaUrl(productImages.value[index], logoFallback)
  }
  return resolveMediaUrl(normalizeImage(product.value?.image || product.value?.image_url), logoFallback)
})

const mainImageKey = computed(() => `${activeImageIndex.value}-${mainImageSrc.value}`)

const isFavorite = computed(() =>
  product.value ? favoritesStore.isFavorite(product.value.id) : false
)

const safeStock = computed(() => {
  const stock = Number(product.value?.stock ?? 0)
  return Number.isFinite(stock) && stock > 0 ? stock : 0
})

const isInStock = computed(() => safeStock.value > 0)

const maxQuantity = computed(() => Math.max(1, safeStock.value || 1))

const canAddToCart = computed(() => !!product.value && isInStock.value)

const formattedPrice = computed(() => {
  const price = Number(product.value?.price)
  if (!Number.isFinite(price) || price <= 0) return 'по запросу'
  return `${new Intl.NumberFormat('ru-RU').format(price)} ₽`
})

const normalizedSpecs = computed(() => {
  const specs = product.value?.specs
  if (!specs || typeof specs !== 'object' || Array.isArray(specs)) return {}
  return specs
})

const hasSpecs = computed(() => Object.keys(normalizedSpecs.value).length > 0)

const normalizedDocs = computed(() => {
  const docs = Array.isArray(product.value?.docs) ? product.value.docs : []
  return docs
    .map((doc) => ({
      name: doc?.name || doc?.title || 'Документ',
      url: doc?.url || doc?.href || '',
    }))
    .filter((doc) => doc.url)
})

const hasDocs = computed(() => normalizedDocs.value.length > 0)

async function fetchProduct(id) {
  loading.value = true
  product.value = null
  relatedProducts.value = []
  activeImageIndex.value = 0
  quantity.value = 1

  try {
    const [productResponse, relatedResponse] = await Promise.all([
      axios.get(`/api/catalog/product/${id}`),
      axios.get(`/api/catalog/related/${id}`),
    ])

    product.value = productResponse.data || null
    relatedProducts.value = Array.isArray(relatedResponse.data)
      ? relatedResponse.data.slice(0, 8)
      : []

    if (product.value) {
      quantity.value = 1
      await nextTick()
    }
  } catch (e) {
    console.error(e)
    product.value = null
    relatedProducts.value = []
  } finally {
    loading.value = false
  }
}

function formatPrice(p) {
  const n = Number(p)
  if (!Number.isFinite(n) || n <= 0) return 'по запросу'
  return new Intl.NumberFormat('ru-RU').format(n)
}

function increment() {
  if (quantity.value < maxQuantity.value) quantity.value++
}

function decrement() {
  if (quantity.value > 1) quantity.value--
}

function addToCart() {
  if (!product.value || !canAddToCart.value) return

  // Ограничиваем количество максимально допустимым значением
  const safeQty = Math.min(quantity.value, maxQuantity.value)
  if (safeQty < 1) return

  cartStore.addToCart(
    {
      id: product.value.id,
      name: product.value.name,
      sku: product.value.sku,
      price: product.value.price,
      image: mainImageSrc.value,
    },
    safeQty   // передаём уже проверенное количество
  )

  isAdded.value = true
  setTimeout(() => {
    isAdded.value = false
  }, 700)
}

function toggleFavorite() {
  if (!product.value) return
  favoritesStore.toggleFavorite(product.value.id)
}

function handleMainImageError(event) {
  if (event?.target) event.target.src = logoFallback
}

function onThumbError(event) {
  if (event?.target) event.target.src = logoFallback
}

function nextImage() {
  if (!productImages.value.length) return
  activeImageIndex.value = (activeImageIndex.value + 1) % productImages.value.length
}

function prevImage() {
  if (!productImages.value.length) return
  activeImageIndex.value =
    (activeImageIndex.value - 1 + productImages.value.length) % productImages.value.length
}

function scrollRelated(direction) {
  const el = relatedScrollRef.value
  if (!el) return

  const amount = Math.max(320, Math.floor(el.clientWidth * 0.8))
  el.scrollBy({
    left: direction === 'left' ? -amount : amount,
    behavior: 'smooth',
  })
}

function onKeydown(event) {
  if (!product.value || !hasMultipleImages.value) return

  const tag = event.target?.tagName?.toLowerCase()
  if (tag === 'input' || tag === 'textarea' || event.target?.isContentEditable) return

  if (event.key === 'ArrowLeft') prevImage()
  if (event.key === 'ArrowRight') nextImage()
}

// Автоматическая корректировка количества, если пользователь ввел значение вручную
watch(quantity, (newVal) => {
  if (!Number.isFinite(newVal) || newVal < 1) {
    quantity.value = 1
    return
  }
  if (newVal > maxQuantity.value) {
    quantity.value = maxQuantity.value
  }
})

watch(
  () => route.params.id,
  (newId) => {
    if (newId) fetchProduct(newId)
  },
  { immediate: true }
)

watch(productImages, (images) => {
  if (!images.length) {
    activeImageIndex.value = 0
    return
  }
  if (activeImageIndex.value >= images.length) {
    activeImageIndex.value = 0
  }
})

onMounted(() => {
  window.addEventListener('keydown', onKeydown)
})

onBeforeUnmount(() => {
  window.removeEventListener('keydown', onKeydown)
})
</script>

<style scoped>
.product-page {
  max-width: 1320px;
  margin: 0 auto;
  padding: 32px 16px 48px;
  font-family: 'Arimo', sans-serif;
  color: #1a1f25;
  --bg: #ffffff;
  --surface: #ffffff;
  --surface-alt: #f7faf8;
  --text: #1a1f25;
  --muted: #6b7280;
  --border: #e6ece8;
  --accent: #51ae45;
  --accent-strong: #156135;
  --accent-soft: rgba(81, 174, 69, 0.12);
  --shadow: 0 16px 40px rgba(17, 24, 39, 0.08);
  --shadow-soft: 0 10px 24px rgba(17, 24, 39, 0.06);
}

:global([data-theme='dark']) .product-page {
  --bg: #0f1412;
  --surface: #171d1a;
  --surface-alt: #101612;
  --text: #eef5ee;
  --muted: #a3b0a7;
  --border: #243028;
  --accent: #6fd15f;
  --accent-strong: #9ce28d;
  --accent-soft: rgba(111, 209, 95, 0.12);
  --shadow: 0 18px 48px rgba(0, 0, 0, 0.34);
  --shadow-soft: 0 10px 24px rgba(0, 0, 0, 0.22);
  background: transparent;
}

.product-page__loader,
.product-page__error {
  min-height: 60vh;
  display: grid;
  place-items: center;
}

.loader-card,
.error-card {
  width: min(100%, 460px);
  padding: 28px;
  border-radius: 22px;
  background: var(--surface);
  box-shadow: var(--shadow);
  border: 1px solid var(--border);
  text-align: center;
  animation: riseIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.loader-card__spinner {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  margin: 0 auto 14px;
  border: 3px solid var(--border);
  border-top-color: var(--accent);
  animation: spin 0.8s linear infinite;
}

.loader-card__text {
  color: var(--muted);
  font-weight: 600;
}

.error-card i {
  font-size: 40px;
  color: var(--accent);
  margin-bottom: 14px;
}

.error-card__title {
  font-size: 22px;
  font-weight: 800;
  margin-bottom: 8px;
  color: var(--text);
}

.error-card__text {
  color: var(--muted);
}

.product-layout {
  display: grid;
  grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
  gap: 44px;
  align-items: start;
  margin-bottom: 36px;
}

.product-gallery {
  display: flex;
  flex-direction: column;
  gap: 12px;
  animation: riseIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.product-gallery__main {
  position: relative;
  border-radius: 22px;
  overflow: hidden;
  background:
    radial-gradient(circle at top, rgba(81, 174, 69, 0.12), transparent 42%),
    var(--surface-alt);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  aspect-ratio: 1 / 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.product-gallery__image {
  width: 100%;
  height: 100%;
  object-fit: contain;
  will-change: transform, opacity;
  transition: transform 0.35s ease;
}

.product-gallery__main:hover .product-gallery__image {
  transform: scale(1.03);
}

.product-gallery__counter {
  position: absolute;
  left: 16px;
  bottom: 16px;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(15, 20, 18, 0.72);
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.2px;
  backdrop-filter: blur(8px);
}

.gallery-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 44px;
  height: 44px;
  border: 0;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.88);
  color: #111;
  display: grid;
  place-items: center;
  cursor: pointer;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
  transition: transform 0.2s ease, background 0.2s ease, opacity 0.2s ease;
  z-index: 2;
}

:global([data-theme='dark']) .gallery-arrow {
  background: rgba(30, 37, 33, 0.9);
  color: #fff;
}

.gallery-arrow:hover {
  transform: translateY(-50%) scale(1.06);
  background: rgba(255, 255, 255, 1);
}

.gallery-arrow--prev {
  left: 14px;
}

.gallery-arrow--next {
  right: 14px;
}

.product-gallery__thumbnails {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  padding: 2px 2px 0;
}

.thumbnail-btn {
  border: 1px solid var(--border);
  background: var(--surface);
  border-radius: 14px;
  padding: 6px;
  cursor: pointer;
  transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
  box-shadow: var(--shadow-soft);
}

.thumbnail-btn:hover {
  transform: translateY(-2px);
  border-color: rgba(81, 174, 69, 0.35);
}

.thumbnail-btn.active {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--accent-soft);
}

.thumbnail-img {
  width: 62px;
  height: 62px;
  object-fit: contain;
  border-radius: 10px;
  display: block;
}

.product-info {
  min-width: 0;
  animation: riseIn 0.58s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.product-info__topline {
  display: flex;
  gap: 10px;
  margin-bottom: 14px;
}

.stock-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border-radius: 999px;
  padding: 8px 12px;
  font-size: 13px;
  font-weight: 700;
}

.stock-badge--ok {
  background: rgba(81, 174, 69, 0.12);
  color: var(--accent-strong);
}

.stock-badge--warn {
  background: rgba(245, 158, 11, 0.12);
  color: #c57d00;
}

.product-info__title {
  margin: 0 0 12px;
  font-size: clamp(28px, 3vw, 38px);
  line-height: 1.18;
  letter-spacing: -0.02em;
  color: var(--text);
  font-weight: 900;
}

.product-info__meta {
  display: flex;
  flex-wrap: wrap;
  gap: 10px 16px;
  color: var(--muted);
  font-size: 14px;
  margin-bottom: 18px;
}

.product-info__price-wrap {
  padding: 18px 18px 16px;
  border: 1px solid var(--border);
  border-radius: 18px;
  background:
    linear-gradient(180deg, rgba(81, 174, 69, 0.06), transparent),
    var(--surface);
  box-shadow: var(--shadow-soft);
  margin-bottom: 18px;
}

.product-info__price {
  font-size: clamp(30px, 3vw, 40px);
  line-height: 1.1;
  font-weight: 900;
  color: var(--accent-strong);
}

.product-info__stock {
  margin-top: 6px;
  font-size: 14px;
  color: var(--muted);
}

.product-info__quantity {
  margin-bottom: 22px;
}

.quantity-label {
  display: block;
  margin-bottom: 8px;
  color: var(--text);
  font-weight: 700;
  font-size: 14px;
}

.quantity-control {
  display: inline-flex;
  align-items: center;
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  background: var(--surface);
  box-shadow: var(--shadow-soft);
}

.quantity-btn {
  width: 44px;
  height: 44px;
  border: 0;
  background: var(--surface-alt);
  color: var(--text);
  font-size: 22px;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
}

.quantity-btn:hover:not(:disabled) {
  background: rgba(81, 174, 69, 0.08);
}

.quantity-btn:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

.quantity-input {
  width: 72px;
  height: 44px;
  border: 0;
  text-align: center;
  font-size: 16px;
  color: var(--text);
  background: transparent;
  outline: none;
  -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.product-info__actions {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 22px;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: 0;
  border-radius: 14px;
  cursor: pointer;
  font-weight: 800;
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease,
    background 0.2s ease,
    opacity 0.2s ease;
}

.btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.btn:hover:not(:disabled) {
  transform: translateY(-1px);
}

.btn-primary {
  background: linear-gradient(180deg, var(--accent), #3f9b35);
  color: #fff;
  box-shadow: 0 12px 24px rgba(81, 174, 69, 0.25);
}

.btn-primary:hover:not(:disabled) {
  box-shadow: 0 16px 30px rgba(81, 174, 69, 0.32);
}

.btn-primary.added {
  background: linear-gradient(180deg, #3f9b35, var(--accent-strong));
}

.btn-lg {
  padding: 14px 22px;
  font-size: 15px;
  min-height: 52px;
}

.btn-icon {
  width: 52px;
  height: 52px;
  border-radius: 16px;
  background: var(--surface);
  color: #d23c3c;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-soft);
}

.btn-icon:hover:not(:disabled) {
  background: rgba(210, 60, 60, 0.06);
}

.product-info__desc {
  padding: 18px;
  border: 1px solid var(--border);
  border-radius: 18px;
  background: var(--surface);
  box-shadow: var(--shadow-soft);
}

.product-info__desc h3 {
  margin: 0 0 8px;
  font-size: 18px;
  font-weight: 800;
  color: var(--text);
}

.product-info__desc p {
  margin: 0;
  color: var(--muted);
  line-height: 1.7;
}

.product-info__hint {
  margin-top: 16px;
  display: inline-flex;
  gap: 10px;
  align-items: center;
  color: var(--muted);
  font-size: 13px;
  padding: 10px 12px;
  border-radius: 12px;
  background: var(--surface-alt);
  border: 1px dashed var(--border);
}

.product-tabs {
  border: 1px solid var(--border);
  border-radius: 22px;
  overflow: hidden;
  background: var(--surface);
  box-shadow: var(--shadow);
  margin-bottom: 32px;
  animation: riseIn 0.62s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.tabs-header {
  display: flex;
  flex-wrap: wrap;
  gap: 0;
  background: var(--surface-alt);
  border-bottom: 1px solid var(--border);
}

.tab-btn {
  flex: 1 1 180px;
  min-height: 54px;
  padding: 14px 18px;
  border: 0;
  background: transparent;
  color: var(--muted);
  font-family: 'Mulish', sans-serif;
  font-size: 15px;
  font-weight: 800;
  cursor: pointer;
  transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
}

.tab-btn:hover:not(.active) {
  background: rgba(81, 174, 69, 0.06);
  color: var(--text);
}

.tab-btn.active {
  background: var(--surface);
  color: var(--accent-strong);
  box-shadow: inset 0 -2px 0 var(--accent);
}

.tabs-body {
  padding: 24px;
}

.tab-panel {
  animation: tabRise 0.3s ease both;
}

.content-html {
  color: var(--text);
  line-height: 1.8;
}

.content-html :deep(img) {
  max-width: 100%;
  height: auto;
  display: block;
  margin: 20px auto;
  border-radius: 18px;
  box-shadow: var(--shadow);
  transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.55s ease, filter 0.55s ease;
}

.content-html :deep(img:hover) {
  transform: scale(1.025);
  filter: saturate(1.03) contrast(1.03);
  box-shadow: 0 22px 52px rgba(0, 0, 0, 0.18);
}

.content-html :deep(h1),
.content-html :deep(h2),
.content-html :deep(h3),
.content-html :deep(h4) {
  color: var(--text);
  line-height: 1.25;
  margin-top: 28px;
}

.content-html :deep(p) {
  margin: 0 0 14px;
}

.content-html :deep(ul),
.content-html :deep(ol) {
  padding-left: 20px;
}

.content-html :deep(a) {
  color: var(--accent-strong);
}

.specs-table {
  width: 100%;
  border-collapse: collapse;
}

.specs-table th,
.specs-table td {
  padding: 12px 14px;
  border-bottom: 1px solid var(--border);
  text-align: left;
  vertical-align: top;
}

.specs-table th {
  width: 34%;
  color: var(--text);
  font-weight: 800;
  background: var(--surface-alt);
}

.specs-table td {
  color: var(--muted);
}

.docs-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.docs-list li {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 0;
  border-bottom: 1px solid var(--border);
}

.docs-list li:last-child {
  border-bottom: 0;
}

.docs-list i {
  color: #d64545;
}

.docs-list a {
  color: var(--accent-strong);
  text-decoration: none;
  font-weight: 700;
}

.docs-list a:hover {
  text-decoration: underline;
}

.empty-state {
  color: var(--muted);
  margin: 0;
}

.related-products {
  margin-top: 12px;
  animation: riseIn 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.related-products__head {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 18px;
}

.related-products__title {
  margin: 0;
  font-size: 26px;
  font-family: 'Mulish', sans-serif;
  font-weight: 900;
  color: var(--text);
}

.related-products__subtitle {
  margin: 6px 0 0;
  color: var(--muted);
  font-size: 14px;
}

.related-products__controls {
  display: flex;
  gap: 10px;
}

.related-nav-btn {
  width: 42px;
  height: 42px;
  border: 1px solid var(--border);
  border-radius: 14px;
  background: var(--surface);
  color: var(--text);
  cursor: pointer;
  box-shadow: var(--shadow-soft);
  transition: transform 0.2s ease, background 0.2s ease;
}

.related-nav-btn:hover {
  transform: translateY(-1px);
  background: var(--surface-alt);
}

.related-products__rail {
  display: grid;
  grid-auto-flow: column;
  grid-auto-columns: minmax(220px, 1fr);
  gap: 16px;
  overflow-x: auto;
  padding-bottom: 8px;
  scroll-snap-type: x mandatory;
  scrollbar-width: thin;
}

.related-products__rail::-webkit-scrollbar {
  height: 8px;
}

.related-products__rail::-webkit-scrollbar-thumb {
  background: rgba(120, 120, 120, 0.28);
  border-radius: 999px;
}

.related-products__item {
  scroll-snap-align: start;
  min-width: 220px;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-zoom-enter-active,
.fade-zoom-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.fade-zoom-enter-from,
.fade-zoom-leave-to {
  opacity: 0;
  transform: scale(0.98);
}

.tab-fade-enter-active,
.tab-fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.tab-fade-enter-from,
.tab-fade-leave-to {
  opacity: 0;
  transform: translateY(6px);
}

@keyframes riseIn {
  from {
    opacity: 0;
    transform: translateY(14px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes tabRise {
  from {
    opacity: 0;
    transform: translateY(6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 980px) {
  .product-layout {
    grid-template-columns: 1fr;
    gap: 28px;
  }

  .product-info__title {
    font-size: clamp(26px, 7vw, 34px);
  }

  .related-products__head {
    align-items: start;
    flex-direction: column;
  }
}

@media (max-width: 640px) {
  .product-page {
    padding: 20px 12px 36px;
  }

  .product-gallery__main {
    aspect-ratio: 4 / 4.5;
    padding: 14px;
    border-radius: 18px;
  }

  .gallery-arrow {
    width: 40px;
    height: 40px;
  }

  .gallery-arrow--prev {
    left: 10px;
  }

  .gallery-arrow--next {
    right: 10px;
  }

  .thumbnail-img {
    width: 54px;
    height: 54px;
  }

  .product-info__actions {
    flex-wrap: wrap;
  }

  .btn-lg {
    width: 100%;
  }

  .btn-icon {
    width: 100%;
  }

  .tabs-body {
    padding: 18px;
  }

  .tab-btn {
    flex: 1 1 100%;
  }

  .specs-table th,
  .specs-table td {
    padding: 10px 12px;
  }

  .related-products__rail {
    grid-auto-columns: minmax(190px, 82vw);
  }
}
</style>