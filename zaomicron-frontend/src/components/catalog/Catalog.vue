<template>
  <div class="catalog-page">
    

    <!-- Заголовок и сортировка -->
    <div class="catalog-header">
      <h1>{{ categoryName }}</h1>
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
      <!-- Фильтры -->
      <FiltersSidebar @apply="applyFilters" />

      <!-- Товары + пагинация -->
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
import FiltersSidebar from '@/components/catalog/FiltersSidebar.vue';
import ProductCard from '@/components/catalog/ProductCard.vue';
import Pagination from '@/components/ui/Pagination.vue';

export default {
  name: 'Catalog',
  components: {
    FiltersSidebar,
    ProductCard,
    Pagination
  },
  data() {
    return {
      categoryName: 'Инструмент',
      breadcrumbs: [
        { text: 'Главная', url: '/' },
        { text: 'Каталог', url: '/catalog' },
        { text: 'Инструмент' }
      ],
      sortBy: 'popular',
      currentPage: 1,
      itemsPerPage: 12,
      filters: {},
      productsData: [
        {
          id: 1,
          name: 'Цифровой манометр DM-100',
          sku: 'DM-100-2024',
          price: 15800,
          image: '@/assets/manometer.jpg',
          isFavorite: false,
          brand: 'WIKA',
          type: 'Цифровые'
        },
        {
          id: 2,
          name: 'Термометр ТП-202',
          sku: 'ТП-202-PRO',
          price: 8900,
          image: '@/assets/thermometer.jpg',
          isFavorite: false,
          brand: 'Endress+Hauser',
          type: 'Цифровые'
        },
        {
          id: 3,
          name: 'Расходомер RF-400',
          sku: 'RF-400-ULT',
          price: 45200,
          image: '@/assets/flowmeter.jpg',
          isFavorite: false,
          brand: 'Siemens',
          type: 'Промышленные'
        },
        {
          id: 4,
          name: 'Анализатор газа AG-5',
          sku: 'AG-5-PORT',
          price: 67500,
          image: '@/assets/analyzer.jpg',
          isFavorite: false,
          brand: 'WIKA',
          type: 'Аналоговые'
        },
        {
          id: 5,
          name: 'Манометр аналоговый PM-200',
          sku: 'PM-200-IND',
          price: 5400,
          image: '@/assets/manometer-analog.jpg',
          isFavorite: false,
          brand: 'Siemens',
          type: 'Аналоговые'
        },
        {
          id: 6,
          name: 'Термометр цифровой TT-150',
          sku: 'TT-150-DIG',
          price: 12300,
          image: '@/assets/thermometer-digital.jpg',
          isFavorite: false,
          brand: 'Endress+Hauser',
          type: 'Цифровые'
        }
      ]
    };
  },
  computed: {
    filteredProducts() {
      let filtered = [...this.productsData];

      if (this.filters.minPrice) {
        filtered = filtered.filter(p => p.price >= Number(this.filters.minPrice));
      }
      if (this.filters.maxPrice) {
        filtered = filtered.filter(p => p.price <= Number(this.filters.maxPrice));
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
    }
  },
  methods: {
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
      // эмит или вызов store
    },
    toggleFavorite(productId) {
      const product = this.productsData.find(p => p.id === productId);
      if (product) {
        product.isFavorite = !product.isFavorite;
      }
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