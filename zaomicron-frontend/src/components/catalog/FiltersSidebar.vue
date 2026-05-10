<template>
  <div class="filters-root">
    <div class="filters-mobile-bar">
      <button type="button" class="filters-open-btn" @click="openDrawer">
        <i class="fas fa-sliders-h" aria-hidden="true" />
        <span>Фильтры</span>
        <span v-if="activeFilterCount" class="filters-badge">{{ activeFilterCount }}</span>
      </button>
    </div>

    <Teleport to="body">
      <transition name="fade">
        <div
          v-if="drawerOpen && isMobileLayout"
          class="filters-backdrop"
          role="presentation"
          @click="closeDrawer"
        />
      </transition>
    </Teleport>

    <aside
      class="filters-sidebar"
      :class="{ 'filters-sidebar--open': drawerOpen && isMobileLayout }"
    >
      <div v-if="isMobileLayout" class="drawer-header">
        <h2 class="drawer-title">Фильтры</h2>
        <button type="button" class="drawer-close" aria-label="Закрыть" @click="closeDrawer">
          <i class="fas fa-times" />
        </button>
      </div>

      <div class="filter-inner">
        <div class="filter-section">
          <h3 class="filter-title">Цена, ₽</h3>
          <div class="price-inputs">
            <input
              v-model="minPrice"
              class="form-control"
              type="number"
              inputmode="decimal"
              min="0"
              step="1"
              placeholder="От"
            >
            <input
              v-model="maxPrice"
              class="form-control"
              type="number"
              inputmode="decimal"
              min="0"
              step="1"
              placeholder="До"
            >
          </div>
        </div>

        <div class="filter-section">
          <h3 class="filter-title">Производитель</h3>
          <label v-for="brand in brands" :key="'b-' + brand" class="filter-check">
            <input v-model="selectedBrands" type="checkbox" :value="brand">
            <span class="checkmark" />
            {{ brand }}
          </label>
        </div>

        <div class="filter-section">
          <h3 class="filter-title">Тип</h3>
          <label v-for="type in types" :key="'t-' + type" class="filter-check">
            <input v-model="selectedTypes" type="checkbox" :value="type">
            <span class="checkmark" />
            {{ type }}
          </label>
        </div>

        <button type="button" class="btn btn-primary" @click="applyAndMaybeClose">Применить</button>
        <button type="button" class="btn btn-secondary" @click="resetAndMaybeClose">Сбросить</button>
      </div>
    </aside>
  </div>
</template>

<script>
import axios from 'axios';

const MQ = '(max-width: 768px)';

export default {
  name: 'FiltersSidebar',
  data() {
    return {
      minPrice: null,
      maxPrice: null,
      brands: [],
      types: [],
      selectedBrands: [],
      selectedTypes: [],
      drawerOpen: false,
      isMobileLayout: false,
      mql: null,
    };
  },
  computed: {
    activeFilterCount() {
      let n = 0;
      if (this.minPrice != null && this.minPrice !== '') {
        n++;
      }
      if (this.maxPrice != null && this.maxPrice !== '') {
        n++;
      }
      n += this.selectedBrands.length;
      n += this.selectedTypes.length;
      return n;
    },
  },
  mounted() {
    this.mql = window.matchMedia(MQ);
    this.isMobileLayout = this.mql.matches;
    this.mql.addEventListener('change', this.onMq);
    this.fetchFilters();
  },
  beforeUnmount() {
    this.mql?.removeEventListener('change', this.onMq);
    document.body.classList.remove('filters-drawer-open');
  },
  methods: {
    onMq(e) {
      this.isMobileLayout = e.matches;
      if (!this.isMobileLayout) {
        this.drawerOpen = false;
        document.body.classList.remove('filters-drawer-open');
      }
    },
    openDrawer() {
      this.drawerOpen = true;
      document.body.classList.add('filters-drawer-open');
    },
    closeDrawer() {
      this.drawerOpen = false;
      document.body.classList.remove('filters-drawer-open');
    },
    appliedPayload() {
      const toNum = (v) => {
        if (v === '' || v === null || v === undefined) {
          return null;
        }
        const n = v;
        const num = Number(n);
        return Number.isFinite(num) ? num : null;
      };

      return {
        minPrice: toNum(this.minPrice),
        maxPrice: toNum(this.maxPrice),
        brands: this.selectedBrands,
        types: this.selectedTypes,
      };
    },
    applyAndMaybeClose() {
      this.$emit('apply', this.appliedPayload());
      if (this.isMobileLayout) {
        this.closeDrawer();
      }
    },
    resetAndMaybeClose() {
      this.minPrice = null;
      this.maxPrice = null;
      this.selectedBrands = [];
      this.selectedTypes = [];
      this.$emit('apply', this.appliedPayload());
      if (this.isMobileLayout) {
        this.closeDrawer();
      }
    },
    async fetchFilters() {
      try {
        const { data } = await axios.get('/api/catalog/filters');
        this.brands = data.brands || [];
        this.types = data.types || [];
        this.categories = data.slice(0, 5);
      } catch (err) {
        console.error('Ошибка загрузки фильтров', err);
      }
    },
  },
};
</script>

<style scoped>
.filters-root {
  position: relative;
}

.filters-mobile-bar {
  display: none;
  margin-bottom: 14px;
}

.filters-open-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  width: 100%;
  justify-content: center;
  padding: 12px 16px;
  border-radius: 10px;
  border: 1px solid #cfe8ca;
  background: #f4fbf3;
  color: #2c5538;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
}

.filters-open-btn:hover {
  background: #e8f5e6;
  border-color: #51ae45;
}

.filters-badge {
  min-width: 22px;
  height: 22px;
  padding: 0 6px;
  border-radius: 999px;
  background: #51ae45;
  color: #fff;
  font-size: 12px;
  line-height: 22px;
  text-align: center;
}

.filters-backdrop {
  position: fixed;
  inset: 0;
  z-index: 3998;
  background: rgba(15, 23, 42, 0.45);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.filters-sidebar {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  border: 1px solid #eef2f6;
  transition: transform 0.28s ease;
}

.drawer-header {
  display: none;
  align-items: center;
  justify-content: space-between;
  margin: -8px -8px 16px 0;
  padding-bottom: 12px;
  border-bottom: 1px solid #f0f2f5;
}

.drawer-title {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #1e293b;
}

.drawer-close {
  border: none;
  background: #f1f5f9;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  cursor: pointer;
  color: #475569;
  font-size: 18px;
}

.filter-section {
  margin-bottom: 28px;
  border-bottom: 1px solid #f0f2f5;
  padding-bottom: 20px;
}

.filter-section:last-of-type {
  border-bottom: none;
  margin-bottom: 20px;
  padding-bottom: 0;
}

.filter-title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #2c3e50;
}

.price-inputs {
  display: flex;
  gap: 12px;
}

.form-control {
  flex: 1;
  min-width: 0;
  padding: 10px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 14px;
}

.form-control:focus {
  outline: none;
  border-color: #51ae45;
  box-shadow: 0 0 0 3px rgba(81, 174, 69, 0.12);
}

.filter-check {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 14px;
  color: #4a5568;
  position: relative;
  user-select: none;
}

.filter-check input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.checkmark {
  display: inline-block;
  width: 18px;
  height: 18px;
  background-color: #fff;
  border: 2px solid #cbd5e0;
  border-radius: 4px;
  flex-shrink: 0;
  position: relative;
}

.filter-check:hover .checkmark {
  border-color: #51ae45;
}

.filter-check input:checked ~ .checkmark {
  background-color: #51ae45;
  border-color: #51ae45;
}

.checkmark::after {
  content: '';
  position: absolute;
  display: none;
  left: 5px;
  top: 2px;
  width: 4px;
  height: 8px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.filter-check input:checked ~ .checkmark::after {
  display: block;
}

.btn {
  width: 100%;
  font-weight: 500;
  padding: 10px 16px;
  font-size: 14px;
  border-radius: 8px;
  border: none;
  margin-top: 8px;
  cursor: pointer;
}

.btn-primary {
  background-color: #51ae45;
  color: white;
  margin-bottom: 8px;
}

.btn-secondary {
  background-color: #f1f5f9;
  color: #334155;
  border: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
  .filters-mobile-bar {
    display: block;
  }

  .drawer-header {
    display: flex;
  }

  .filters-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: min(360px, 92vw);
    z-index: 3999;
    border-radius: 0;
    overflow-y: auto;
    transform: translateX(-100%);
    box-shadow: 8px 0 32px rgba(0, 0, 0, 0.12);
    border-right: 1px solid #e2e8f0;
    border-top: none;
    border-bottom: none;
    border-left: none;
  }

  .filters-sidebar--open {
    transform: translateX(0);
  }
}
</style>
