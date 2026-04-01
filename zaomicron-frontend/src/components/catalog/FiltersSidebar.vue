<template>
  <aside class="filters-sidebar">
    <div class="filter-section">
      <h3 class="filter-title">Производитель</h3>
      <label class="filter-check" v-for="brand in brands" :key="brand">
        <input type="checkbox" :value="brand" v-model="selectedBrands">
        <span class="checkmark"></span>
        {{ brand }}
      </label>
    </div>

    <div class="filter-section">
      <h3 class="filter-title">Тип</h3>
      <label class="filter-check" v-for="type in types" :key="type">
        <input type="checkbox" :value="type" v-model="selectedTypes">
        <span class="checkmark"></span>
        {{ type }}
      </label>
    </div>

    <button class="btn btn-primary" @click="applyFilters">Применить</button>
    <button class="btn btn-secondary" @click="resetFilters">Сбросить</button>
  </aside>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FiltersSidebar',
  data() {
    return {
      minPrice: null,
      maxPrice: null,
      brands: [],
      types: [],
      selectedBrands: [],
      selectedTypes: []
    };
  },
  mounted() {
    this.fetchFilters();
  },
  methods: {
    async fetchFilters() {
      try {
        const { data } = await axios.get('/api/catalog/filters');
        this.brands = data.brands || [];
        this.types = data.types || [];
      } catch (err) {
        console.error('Ошибка загрузки фильтров', err);
      }
    },
    applyFilters() {
      this.$emit('apply', {
        minPrice: this.minPrice,
        maxPrice: this.maxPrice,
        brands: this.selectedBrands,
        types: this.selectedTypes
      });
    },
    resetFilters() {
      this.minPrice = null;
      this.maxPrice = null;
      this.selectedBrands = [];
      this.selectedTypes = [];
      this.applyFilters();
    }
  }
};
</script>

<style scoped>
.filters-sidebar {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  border: 1px solid #eef2f6;
  transition: all 0.2s ease;
}

.filter-section {
  margin-bottom: 28px;
  border-bottom: 1px solid #f0f2f5;
  padding-bottom: 20px;
}

.filter-section:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.filter-title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 16px;
  color: #2c3e50;
  letter-spacing: -0.2px;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Поля ввода цены */
.price-inputs {
  display: flex;
  gap: 12px;
}

.form-control {
  flex: 1;
  padding: 10px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s ease;
  background-color: #fff;
}

.form-control:focus {
  outline: none;
  border-color: #51AE45;
  box-shadow: 0 0 0 3px rgba(81, 174, 69, 0.1);
}

/* Кастомные чекбоксы */
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
  transition: color 0.2s;
}

.filter-check:hover {
  color: #51AE45;
}

/* Скрываем стандартный чекбокс */
.filter-check input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Создаём свой кастомный чекбокс */
.checkmark {
  position: relative;
  display: inline-block;
  width: 18px;
  height: 18px;
  background-color: #fff;
  border: 2px solid #cbd5e0;
  border-radius: 4px;
  transition: all 0.2s;
  flex-shrink: 0;
}

.filter-check:hover .checkmark {
  border-color: #51AE45;
}

.filter-check input:checked ~ .checkmark {
  background-color: #51AE45;
  border-color: #51AE45;
}

.checkmark:after {
  content: "";
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

.filter-check input:checked ~ .checkmark:after {
  display: block;
}

/* Кнопки */
.btn {
  display: inline-block;
  font-weight: 500;
  text-align: center;
  vertical-align: middle;
  cursor: pointer;
  padding: 10px 16px;
  font-size: 14px;
  line-height: 1.5;
  border-radius: 8px;
  transition: all 0.2s ease;
  width: 100%;
  border: none;
  margin-top: 8px;
}

.btn-primary {
  background-color: #51AE45;
  color: white;
  margin-bottom: 12px;
}

.btn-primary:hover {
  background-color: #3e8c34;
  transform: translateY(-1px);
  box-shadow: 0 2px 6px rgba(81, 174, 69, 0.3);
}

.btn-secondary {
  background-color: #f1f5f9;
  color: #334155;
  border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
  background-color: #e6edf4;
  transform: translateY(-1px);
}

/* Адаптивность */
@media (max-width: 768px) {
  .filters-sidebar {
    padding: 16px;
    border-radius: 12px;
  }
  
  .filter-title {
    font-size: 15px;
    margin-bottom: 12px;
  }
  
  .form-control {
    padding: 8px 10px;
    font-size: 13px;
  }
  
  .filter-check {
    font-size: 13px;
    margin-bottom: 10px;
  }
  
  .btn {
    padding: 8px 12px;
    font-size: 13px;
  }
}
</style>