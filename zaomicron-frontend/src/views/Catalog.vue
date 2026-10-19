<template>
  <v-container class="catalog-page animated-fade">
    <v-row>
      <v-col cols="12">
        <nav v-if="!searchQuery && browseBreadcrumbs.length" class="catalog-breadcrumb text-body-2 mb-2">
          <router-link to="/catalog" class="catalog-breadcrumb__link">Каталог</router-link>
          <template v-for="(crumb, i) in browseBreadcrumbs" :key="crumb.id">
            <span class="catalog-breadcrumb__sep">/</span>
            <router-link
              v-if="i < browseBreadcrumbs.length - 1"
              :to="`/catalog/${crumb.id}`"
              class="catalog-breadcrumb__link"
            >{{ crumb.name }}</router-link>
            <span v-else class="catalog-breadcrumb__current">{{ crumb.name }}</span>
          </template>
        </nav>

        <div class="catalog-toolbar animated-rise">
          <div class="catalog-toolbar__head">
            <h1 class="catalog-title text-h4 font-weight-bold">{{ categoryName }}</h1>
            <div v-if="showBrowseStep && showProductsSection" class="catalog-counter text-medium-emphasis">
              {{ browseChildren.length }} {{ browseChildren.length === 1 ? 'подраздел' : 'подразделов' }}
              · {{ totalItems }} {{ totalItems === 1 ? 'товар' : 'товаров' }} в разделе и подкатегориях
            </div>
            <div v-else-if="showProductsSection" class="catalog-counter">{{ totalItems }} товаров</div>
            <div v-else-if="showBrowseStep" class="catalog-counter text-medium-emphasis">
              {{ browseChildren.length }} {{ browseChildren.length === 1 ? 'раздел' : 'разделов' }}
            </div>
          </div>
          <div v-if="showProductsSection" class="catalog-toolbar__sort">
            <v-select
              v-model="sortBy"
              :items="sortOptions"
              item-title="text"
              item-value="value"
              label="Сортировка"
              density="compact"
              variant="outlined"
              hide-details
              @update:model-value="changeSort"
            />
          </div>
        </div>
      </v-col>

      <!-- Подкатегории -->
      <template v-if="showBrowseStep">
        <v-col cols="12" class="animated-rise" :class="{ 'mb-4': showBrowseStep && showProductsSection }" style="animation-delay: 80ms">
          <h2 v-if="showProductsSection" class="text-subtitle-1 font-weight-bold mb-3">Подразделы</h2>
          <v-row v-if="browseLoading">
            <v-col v-for="n in 6" :key="n" cols="12" sm="6" md="4">
              <v-skeleton-loader type="card" />
            </v-col>
          </v-row>
          <v-row v-else>
            <v-col
              v-for="(child, index) in browseChildren"
              :key="child.id"
              cols="12"
              sm="6"
              md="4"
              :style="{ animationDelay: `${80 + (index % 9) * 30}ms` }"
              class="animated-rise"
            >
              <router-link :to="`/catalog/${child.id}`" class="catalog-subcat-card__link">
                <v-card class="catalog-subcat-card" hover rounded="lg" border>
                  <v-card-title class="d-flex align-center text-h6">
                    <img
                      v-if="getCategoryIcon(child)"
                      :src="getCategoryIcon(child)"
                      class="catalog-subcat-icon"
                      @error="onIconError"
                      alt=""
                    />
                    {{ child.name }}
                  </v-card-title>
                  <v-card-text class="text-body-2 text-medium-emphasis pt-0">
                    {{ child.has_children ? 'Есть вложенные разделы' : 'Перейти к товарам' }}
                  </v-card-text>
                </v-card>
              </router-link>
            </v-col>
          </v-row>
        </v-col>
      </template>

      <!-- Пустой корень -->
      <v-col v-if="emptyRootCatalog" cols="12">
        <v-alert type="info" class="animated-rise">
          Категории каталога пока не загружены.
        </v-alert>
      </v-col>

      <!-- Товары: вся ветка категории (включая подкатегории), либо поиск -->
      <template v-if="showProductsSection">
        <v-col cols="12" class="d-md-none animated-rise" style="animation-delay: 60ms">
          <v-btn variant="tonal" block class="catalog-filter-mobile-btn" @click="filterDialog = true">
            <v-icon class="mr-2">mdi-filter-variant</v-icon>
            Фильтры и цена
          </v-btn>
        </v-col>

        <v-col cols="12" md="3" class="d-none d-md-block animated-rise" style="animation-delay: 80ms">
          <FiltersSidebar inline @apply="applyFilters" />
        </v-col>

        <v-col cols="12" md="9" class="animated-rise" style="animation-delay: 120ms">
          <h2 v-if="showBrowseStep && showProductsSection" class="text-subtitle-1 font-weight-bold mb-3">Товары</h2>
          <v-row v-if="loading">
            <v-col v-for="n in 6" :key="n" cols="12" sm="6" lg="4">
              <v-skeleton-loader type="card" />
            </v-col>
          </v-row>

          <v-alert v-else-if="error" type="error" class="mb-4">
            {{ error }}
          </v-alert>

          <v-alert
            v-else-if="!products.length"
            type="info"
            class="mb-4"
          >
            Товары не найдены
          </v-alert>

          <v-row v-else>
            <v-col
              v-for="(product, index) in paginatedProducts"
              :key="product.id"
              cols="12" sm="6" lg="4"
              :style="{ animationDelay: `${120 + (index % 9) * 30}ms` }"
              class="catalog-product-col animated-rise"
            >
              <ProductCard :product="product" />
            </v-col>
          </v-row>

          <div v-if="totalPages > 1" class="d-flex justify-center mt-6">
            <Pagination
              :current-page="currentPage"
              :total-pages="totalPages"
              @page-changed="changePage"
            />
          </div>
        </v-col>
      </template>
    </v-row>

    <v-dialog v-model="filterDialog" max-width="400">
      <v-card>
        <v-card-title class="d-flex justify-space-between align-center">
          Фильтры
          <v-btn icon @click="filterDialog = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <FiltersSidebar inline @apply="applyFiltersAndCloseDialog" />
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import ProductCard from '@/components/catalog/ProductCard.vue';
import FiltersSidebar from '@/components/catalog/FiltersSidebar.vue';
import Pagination from '@/components/ui/Pagination.vue';
import { sanitizeFilterPrice } from '@/utils/price';
import logoFallback from '@/assets/logo.png';
import { resolveMediaUrl } from '@/utils/media';

const route = useRoute();
const categoryName = ref('Каталог');
const loading = ref(false);
const error = ref(null);
const products = ref([]);
const paginatedProducts = computed(() => (Array.isArray(products.value) ? products.value : []));
const currentPage = ref(1);
const lastPage = ref(1);
const totalItems = ref(0);
const itemsPerPage = 12;
const filterDialog = ref(false);
const sortBy = ref('name_asc');
const filters = ref({});

const browseChildren = ref([]);
const browseBreadcrumbs = ref([]);
const browseCategory = ref(null);
const browseLoading = ref(false);
const browseLoaded = ref(false);

const sortOptions = [
  { text: 'По названию', value: 'name_asc' },
  { text: 'Цена по возрастанию', value: 'price_asc' },
  { text: 'Цена по убыванию', value: 'price_desc' },
];

const categoryId = computed(() => route.params.id || null);
const searchQuery = computed(() => route.query.search || '');

const showBrowseStep = computed(() => {
  if (searchQuery.value) return false;
  return browseChildren.value.length > 0;
});

const showProductsSection = computed(() => {
  if (searchQuery.value) return true;
  return !!categoryId.value;
});

const emptyRootCatalog = computed(() => (
  !searchQuery.value
  && !categoryId.value
  && browseLoaded.value
  && !browseLoading.value
  && browseChildren.value.length === 0
));

async function fetchBrowse() {
  browseLoading.value = true;
  try {
    const url = categoryId.value
      ? `/api/catalog/browse/${categoryId.value}`
      : '/api/catalog/browse';
    const { data } = await axios.get(url);
    browseChildren.value = Array.isArray(data.children) ? data.children : [];
    browseBreadcrumbs.value = Array.isArray(data.breadcrumbs) ? data.breadcrumbs : [];
    browseCategory.value = data.category || null;
  } catch (e) {
    browseChildren.value = [];
    browseBreadcrumbs.value = [];
    browseCategory.value = null;
    console.error(e);
  } finally {
    browseLoading.value = false;
    browseLoaded.value = true;
  }
}

async function fetchProducts() {
  loading.value = true;
  error.value = null;
  try {
    const params = buildProductQueryParams();
    const qs = serializeProductQueryParams(params);
    const { data } = await axios.get(`/api/catalog/products?${qs}`);
    products.value = Array.isArray(data.data) ? data.data : [];
    if (data.meta) {
      currentPage.value = data.meta.current_page;
      lastPage.value = data.meta.last_page;
      totalItems.value = data.meta.total ?? products.value.length;
      categoryName.value = data.meta.category_name || 'Каталог';
    }
  } catch (e) {
    error.value = 'Ошибка загрузки товаров';
    console.error(e);
  } finally {
    loading.value = false;
  }
}

function buildProductQueryParams() {
  const f = filters.value || {};
  const params = {
    page: currentPage.value,
    per_page: itemsPerPage,
    sort: sortBy.value,
  };
  if (categoryId.value) {
    params.category_id = categoryId.value;
  }
  if (searchQuery.value) {
    params.q = searchQuery.value;
  }
  const minP = sanitizeFilterPrice(f.minPrice);
  if (minP !== null) {
    params.minPrice = minP;
  }
  const maxP = sanitizeFilterPrice(f.maxPrice);
  if (maxP !== null) {
    params.maxPrice = maxP;
  }
  if (Array.isArray(f.brands) && f.brands.length) {
    params.brands = f.brands;
  }
  if (Array.isArray(f.types) && f.types.length) {
    params.types = f.types;
  }
  if (f.attrs && typeof f.attrs === 'object') {
    params.attrs = f.attrs;
  }
  return params;
}

function serializeProductQueryParams(params) {
  const usp = new URLSearchParams();
  for (const [key, val] of Object.entries(params)) {
    if (val === null || val === undefined || val === '') {
      continue;
    }
    if (key === 'attrs' && val && typeof val === 'object' && !Array.isArray(val)) {
      for (const [paramId, values] of Object.entries(val)) {
        if (!Array.isArray(values) || !values.length) continue;
        for (const item of values) {
          if (item === null || item === undefined) continue;
          const s = String(item);
          if (s === '') continue;
          usp.append(`attrs[${paramId}][]`, s);
        }
      }
      continue;
    }
    if (Array.isArray(val)) {
      for (const item of val) {
        if (item === null || item === undefined) continue;
        const s = String(item);
        if (s === '') continue;
        usp.append(`${key}[]`, s);
      }
      continue;
    }
    usp.append(key, String(val));
  }
  return usp.toString();
}

async function loadCatalogView() {
  error.value = null;

  if (searchQuery.value) {
    browseChildren.value = [];
    browseBreadcrumbs.value = [];
    browseCategory.value = null;
    await fetchProducts();
    return;
  }

  await fetchBrowse();

  if (categoryId.value) {
    categoryName.value = browseCategory.value?.name || 'Каталог';
    await fetchProducts();
    return;
  }

  products.value = [];
  totalItems.value = 0;
  lastPage.value = 1;
  categoryName.value = 'Каталог';
}

const totalPages = computed(() => lastPage.value);

function applyFilters(newFilters) {
  filters.value = newFilters;
  currentPage.value = 1;
  if (showProductsSection.value) {
    fetchProducts();
  }
}

function applyFiltersAndCloseDialog(newFilters) {
  applyFilters(newFilters);
  filterDialog.value = false;
}

function changeSort() {
  currentPage.value = 1;
  if (showProductsSection.value) {
    fetchProducts();
  }
}

function changePage(page) {
  currentPage.value = page;
  if (showProductsSection.value) {
    fetchProducts();
  }
}

// ========== Новые методы для иконок категорий ==========
function getCategoryIcon(category) {
  const url = category?.icon_url || category?.image_url; // подставь своё поле при необходимости
  if (!url) return null;
  return resolveMediaUrl(url, logoFallback);
}

function onIconError(event) {
  if (event?.target) {
    event.target.style.display = 'none';
  }
}
// ======================================================

watch([categoryId, searchQuery], () => {
  currentPage.value = 1;
  loadCatalogView();
});

onMounted(() => {
  loadCatalogView();
});
</script>

<style scoped>
.catalog-page {
  padding-top: 12px;
  padding-bottom: 32px;
}

.catalog-breadcrumb {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 4px 0;
}

.catalog-breadcrumb__sep {
  margin: 0 6px;
  color: var(--color-text-light, #888);
}

.catalog-breadcrumb__link {
  color: rgb(var(--v-theme-primary));
  text-decoration: none;
}

.catalog-breadcrumb__link:hover {
  text-decoration: underline;
}

.catalog-breadcrumb__current {
  color: var(--color-text, #212529);
  font-weight: 600;
}

.catalog-toolbar {
  display: grid;
  grid-template-columns: 1fr minmax(220px, 260px);
  align-items: end;
  gap: 16px;
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 14px;
  margin-bottom: 18px;
}

.catalog-toolbar__head {
  display: flex;
  flex-wrap: wrap;
  align-items: baseline;
  gap: 8px 12px;
}

.catalog-title {
  letter-spacing: 0.2px;
  margin: 0;
}

.catalog-counter {
  color: var(--color-text-light);
  font-size: 14px;
}

.catalog-toolbar__sort {
  width: 100%;
}

.catalog-filter-mobile-btn {
  border-radius: 10px;
}

.catalog-subcat-card__link {
  display: block;
  height: 100%;
  text-decoration: none;
  color: inherit;
}

.catalog-subcat-card {
  height: 100%;
  transition: box-shadow 0.2s ease, transform 0.2s ease;
}

.catalog-subcat-card__link:hover .catalog-subcat-card {
  transform: translateY(-2px);
}

/* Иконка подкатегории */
.catalog-subcat-icon {
  width: 24px;
  height: 24px;
  object-fit: contain;
  margin-right: 12px;
  flex-shrink: 0;
}

.catalog-product-col {
  will-change: transform;
}

:deep(.v-select .v-field) {
  border-radius: 10px;
}

:deep(.v-alert) {
  border-radius: 12px;
}

:deep(.v-pagination .v-btn) {
  border-radius: 10px;
}

@media (max-width: 960px) {
  .catalog-toolbar {
    grid-template-columns: 1fr;
  }
}
</style>