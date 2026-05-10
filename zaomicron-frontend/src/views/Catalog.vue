<template>
  <div class="catalog-page">
    <div class="catalog-header">
      <h1>{{ category ? category.title : 'Каталог' }}</h1>
      <div class="sorting-options">
        <span>Сортировка:</span>
        <select v-model="sortBy">
          <option value="popular">По популярности</option>
          <option value="price_asc">По цене (возрастание)</option>
          <option value="price_desc">По цене (убывание)</option>
        </select>
      </div>
    </div>

    <div class="catalog-layout">
      <FiltersSidebar @apply="applyFilters" />

      <div class="catalog-main">
        <div v-if="loading" class="loading-spinner">
          <i class="fas fa-spinner fa-pulse"></i> Загрузка...
        </div>
        <div v-else-if="error" class="error-message">
          <p>{{ error }}</p>
          <button class="btn btn-primary" @click="retryFetch">Повторить</button>
        </div>
        <template v-else>
          <p v-if="!products.length && category && category.id !== 1" class="catalog-empty">
            В этой категории пока нет позиций.
          </p>
         
          <div class="products-grid">
            <ProductCard
              v-for="product in paginatedProducts"
              :key="String(product.id)"
              :id="product.id"
              :name="product.name"
              :sku="product.sku"
              :price="product.price"
              :image="product.image"
              :is-favorite="product.isFavorite"
              @add-to-cart="addToCart"
              @toggle-favorite="toggleFavorite"
            />
          </div>
        </template>

        <Pagination
          v-if="totalPages > 1"
          :current-page="currentPage"
          :total-pages="totalPages"
          @page-changed="changePage"
        />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import FiltersSidebar from '@/components/catalog/FiltersSidebar.vue';
import ProductCard from '@/components/catalog/ProductCard.vue';
import Pagination from '@/components/ui/Pagination.vue';
import { useCartStore } from '@/stores/cart';

export default {
  components: { FiltersSidebar, ProductCard, Pagination },
  data() {
    return {
      category: null,
      children: [],
      products: [],
      loading: false,
      error: null,
      sortBy: 'popular',
      currentPage: 1,
      serverLastPage: 1,
      filters: {
        minPrice: null,
        maxPrice: null,
        brands: [],
        types: []
      },
    };
  },
  computed: {
    filteredProducts() {
      let filtered = [...this.products];
      if (this.filters.minPrice != null && this.filters.minPrice !== '') {
        filtered = filtered.filter(p => Number(p.price) >= Number(this.filters.minPrice));
      }
      if (this.filters.maxPrice != null && this.filters.maxPrice !== '') {
        filtered = filtered.filter(p => Number(p.price) <= Number(this.filters.maxPrice));
      }
      if (this.filters.brands && this.filters.brands.length) {
        filtered = filtered.filter(p => this.filters.brands.includes(p.brand));
      }
      if (this.filters.types && this.filters.types.length) {
        filtered = filtered.filter(p => this.filters.types.includes(p.type));
      }
      return filtered;
    },
    sortedProducts() {
      return [...this.filteredProducts];
    },
    paginatedProducts() {
      return this.sortedProducts;
    },
    totalPages() {
      return this.serverLastPage;
    },
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler(id) {
        this.currentPage = 1;
        this.fetchCategoryData(id || 1);
      },
    },
    sortBy() {
      this.currentPage = 1;
      const id = this.$route.params.id || 1;
      this.fetchCategoryData(id);
    },
  },
  methods: {
    async fetchCategoryData(id) {
      this.loading = true;
      this.error = null;
      try {
        const params = { page: this.currentPage };
        if (this.sortBy === 'price_asc') {
          params.sort = 'price_asc';
        }
        if (this.sortBy === 'price_desc') {
          params.sort = 'price_desc';
        }

        const response = await axios.get(`/api/catalog/category/${id}`, { params });
        this.category = response.data.category || null;
        this.children = this.category?.children || [];

        const payload = response.data?.items ?? response.data?.products;
        const rawProducts = Array.isArray(payload?.data)
          ? payload.data
          : Array.isArray(payload)
            ? payload
            : [];

        this.serverLastPage = Math.max(1, Number(payload?.last_page) || 1);
        if (payload?.current_page) {
          this.currentPage = Number(payload.current_page);
        }

        this.products = rawProducts.map(p => ({
          id: p.id ?? p.ID,
          name: p.name ?? p.NAME,
          sku: p.sku ?? p.article ?? p.model ?? String(p.id ?? ''),
          price: Number(p.price ?? p.PRICE ?? 0),
          image: p.image ?? p.image_url ?? p.main_img ?? null,
          brand: p.brand ?? p.BRAND ?? '',
          type: p.type ?? p.TYPE ?? '',
          isFavorite: p.isFavorite ?? false,
        }));
      } catch (err) {
        console.error('Ошибка загрузки категории', err);
        this.error = 'Не удалось загрузить товары. Попробуйте позже.';
      } finally {
        this.loading = false;
      }
    },
    applyFilters(filters) {
      this.filters = filters;
    },
    changePage(page) {
      this.currentPage = page;
      const id = this.$route.params.id || 1;
      this.fetchCategoryData(id);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    addToCart(product) {
      useCartStore().addToCart(product, 1);
    },
    toggleFavorite(productId) {
      const product = this.products.find(p => p.id === productId);
      if (product) {
        product.isFavorite = !product.isFavorite;
      }
    },
    retryFetch() {
      this.fetchCategoryData(this.$route.params.id || 1);
    },
  },
};
</script>

<style scoped>
.catalog-page {
  padding: 20px 0;
}
.catalog-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 15px;
}
.catalog-header h1 {
  font-size: 28px;
  font-weight: 800;
  color: #333;
}
.sorting-options {
  display: flex;
  align-items: center;
  gap: 10px;
}
.sorting-options select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
  background-color: white;
  cursor: pointer;
}
.catalog-layout {
  display: grid;
  grid-template-columns: 250px 1fr;
  gap: 30px;
  align-items: start;
}
.catalog-main {
  min-width: 0;
}
.catalog-empty,
.catalog-hint {
  margin: 0 0 16px;
  padding: 12px 14px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  color: #475569;
  font-size: 14px;
}
.loading-spinner {
  text-align: center;
  padding: 50px;
  font-size: 18px;
  color: #51ae45;
}
.error-message {
  text-align: center;
  padding: 50px;
  background: #fff2f0;
  border: 1px solid #ffccc7;
  border-radius: 8px;
  color: #d32f2f;
}
.error-message .btn {
  margin-top: 15px;
  background-color: #d32f2f;
  border: none;
  color: white;
  padding: 8px 20px;
  cursor: pointer;
  border-radius: 4px;
}
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 240px), 1fr));
  gap: clamp(14px, 2vw, 22px);
  margin-top: 12px;
}
@media (max-width: 1024px) {
  .catalog-layout {
    grid-template-columns: 220px 1fr;
    gap: 20px;
  }
}
@media (max-width: 768px) {
  .catalog-layout {
    grid-template-columns: 1fr;
  }
  .filters-sidebar {
    order: 2;
  }
  .products-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 480px) {
  .products-grid {
    grid-template-columns: 1fr;
  }
  .catalog-header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
