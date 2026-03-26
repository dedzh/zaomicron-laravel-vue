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
        <div class="products-grid">
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

export default {
  components: { FiltersSidebar, ProductCard, Pagination },
  data() {
    return {
      category: null,
      children: [],
      products: [],
      loading: true,
      sortBy: 'popular',
      currentPage: 1,
      itemsPerPage: 12,
      filters: {},
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
        if (id) this.fetchCategoryData(id);
      },
    },
  },
  methods: {
    async fetchCategoryData(id) {
      this.loading = true;
      try {
        const response = await axios.get(`/api/catalog/category/${id}`);
        this.category = response.data.category || null;
        // На бэке children лежит в category.children
        this.children = this.category?.children || [];

        const paginator = response.data.items || response.data.products || null;
        const rawProducts = paginator?.data || [];

        // Приводим поля к формату, который ожидает ProductCard.
        this.products = rawProducts.map(p => ({
          ...p,
          sku: p.sku ?? p.article ?? p.model ?? '',
          price: p.price ?? p.price_1 ?? 0,
          image: p.image ?? p.image_url ?? null,
          isFavorite: false
        }));
      } catch (error) {
        console.error('Ошибка загрузки категории', error);
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
      console.log('Добавлено в корзину:', product);
    },
    toggleFavorite(productId) {
      // Пока просто выводим в консоль
      console.log('Избранное для товара', productId);
    },
  },
};
</script>

<style scoped>

</style>