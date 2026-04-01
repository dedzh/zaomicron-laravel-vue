<template>
  <div class="catalog-page">
    <div class="catalog-header">
      <h1>{{ category ? category.title : 'Каталог' }}</h1>
      <div class="sorting-options">
        <span>Сортировка:</span>
        <select v-model="sortBy" @change="changeSort">
          <option value="popular">По популярности</option>
          <option value="price_asc">По цене (возрастание)</option>
          <option value="price_desc">По цене (убывание)</option>
        </select>
      </div>
    </div>

    <div class="catalog-layout">
      <FiltersSidebar @apply="applyFilters" />

      <div>
        <div v-if="loading" class="loading-spinner">
          <i class="fas fa-spinner fa-pulse"></i> Загрузка...
        </div>
        <div v-else-if="error" class="error-message">
          <p>{{ error }}</p>
          <button class="btn btn-primary" @click="retryFetch">Повторить</button>
        </div>
        <div v-else class="products-grid">
          <ProductCard
            v-for="product in paginatedProducts"
            :key="product.id"
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
      itemsPerPage: 12,
      filters: {
        minPrice: null,
        maxPrice: null,
        brands: [],
        types: []
      },
      defaultImage: '/img/placeholder.jpg',
    };
  },
  computed: {
    filteredProducts() {
      let filtered = [...this.products];
      if (this.filters.minPrice) {
        filtered = filtered.filter(p => Number(p.price) >= Number(this.filters.minPrice));
      }
      if (this.filters.maxPrice) {
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
      let sorted = [...this.filteredProducts];
      if (this.sortBy === 'price_asc') {
        sorted.sort((a, b) => a.price - b.price);
      } else if (this.sortBy === 'price_desc') {
        sorted.sort((a, b) => b.price - a.price);
      }
      return sorted;
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.sortedProducts.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.sortedProducts.length / this.itemsPerPage);
    },
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler(id) {
        this.fetchCategoryData(id || 1);
      },
    },
  },
  methods: {
    async fetchCategoryData(id) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/catalog/category/${id}`);
        this.category = response.data.category || null;
        this.children = this.category?.children || [];

        const itemsFromApi = response.data?.items?.data ?? response.data?.items ?? response.data?.products?.data ?? response.data?.products ?? [];
        const rawProducts = Array.isArray(itemsFromApi) ? itemsFromApi : [];

        this.products = rawProducts.map(p => ({
          id: p.id ?? p.ID,
          name: p.name ?? p.NAME,
          sku: p.sku ?? p.article ?? p.model ?? p.ID ?? '',
          price: Number(p.price ?? p.PRICE ?? 0),
          image: p.image ?? p.image_url ?? p.main_img ?? null,
          brand: p.brand ?? p.BRAND ?? '',
          type: p.type ?? p.TYPE ?? ''
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
      this.currentPage = 1;
    },
    changePage(page) {
      this.currentPage = page;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    changeSort() {
      this.currentPage = 1;
    },
    addToCart(product) {
      const cartStore = useCartStore();
      cartStore.addToCart(product, 1);
    },
    toggleFavorite(productId) {
      const product = this.products.find(p => p.id === productId);
      if (product) {
        product.isFavorite = !product.isFavorite;
        // Здесь можно отправить запрос на сервер для сохранения избранного
      }
    },
    retryFetch() {
      const id = this.$route.params.id || 1;
      this.fetchCategoryData(id);
    }
  }
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
}
.loading-spinner {
  text-align: center;
  padding: 50px;
  font-size: 18px;
  color: #51AE45;
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
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 20px;
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
    grid-template-columns: repeat(2, 1fr);
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