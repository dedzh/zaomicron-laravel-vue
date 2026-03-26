<template>
  <aside class="filters-sidebar">
    <div class="filter-section">
      <h3 class="filter-title">Цена, ₽</h3>
      <div class="price-inputs">
        <input type="number" v-model="minPrice" placeholder="От" class="form-control">
        <input type="number" v-model="maxPrice" placeholder="До" class="form-control">
      </div>
    </div>

    <div class="filter-section">
      <h3 class="filter-title">Производитель</h3>
      <label class="filter-check" v-for="brand in brands" :key="brand">
        <input type="checkbox" :value="brand" v-model="selectedBrands"> {{ brand }}
      </label>
    </div>

    <div class="filter-section">
      <h3 class="filter-title">Тип</h3>
      <label class="filter-check" v-for="type in types" :key="type">
        <input type="checkbox" :value="type" v-model="selectedTypes"> {{ type }}
      </label>
    </div>

    <button class="btn btn-primary" style="width: 100%; margin-bottom: 10px;" @click="applyFilters">Применить</button>
    <button class="btn btn-secondary" style="width: 100%;" @click="resetFilters">Сбросить</button>
  </aside>
</template>

<script>
export default {
  name: 'FiltersSidebar',
  data() {
    return {
      minPrice: null,
      maxPrice: null,
      brands: ['WIKA', 'Endress+Hauser', 'Siemens'],
      types: ['Цифровые', 'Аналоговые', 'Промышленные'],
      selectedBrands: [],
      selectedTypes: []
    };
  },
  methods: {
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
  border-radius: 8px;
  padding: 20px;
  border: 1px solid #eaeef2;
}
.filter-section {
  margin-bottom: 24px;
}
.filter-title {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
}
.filter-check {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
  cursor: pointer;
}
.price-inputs {
  display: flex;
  gap: 8px;
}

</style>