<template>
  <template v-if="inline">
    <v-sheet class="filters-panel" rounded="lg" border>
      <div class="filters-panel__header">
        <h3 class="text-subtitle-1 font-weight-bold">Фильтры</h3>
        <v-btn variant="text" size="small" color="primary" @click="resetFilters">Сбросить</v-btn>
      </div>

      <div class="filters-section">
        <h4 class="text-subtitle-2 mb-2">Цена, ₽</h4>
        <v-row dense>
          <v-col cols="6">
            <v-text-field
              v-model.number="minPrice"
              label="От"
              type="number"
              hide-details
              density="compact"
              variant="outlined"
            />
          </v-col>
          <v-col cols="6">
            <v-text-field
              v-model.number="maxPrice"
              label="До"
              type="number"
              hide-details
              density="compact"
              variant="outlined"
            />
          </v-col>
        </v-row>
      </div>

      <div v-if="brands.length" class="filters-section">
        <h4 class="text-subtitle-2 mb-2">Бренд</h4>
        <v-text-field
          v-model="brandSearch"
          density="compact"
          variant="outlined"
          hide-details
          label="Поиск бренда"
          class="mb-2"
        />
        <div class="filters-brand-list">
          <v-checkbox
            v-for="(brand, idx) in visibleBrands"
            :key="'brand-' + idx"
            v-model="selectedBrands"
            multiple
            :label="brand"
            :value="brand"
            density="compact"
            hide-details
            color="primary"
          />
        </div>
      </div>

      <v-btn color="primary" block class="mt-4" @click="applyFilters">Показать товары</v-btn>
    </v-sheet>
  </template>

  <v-navigation-drawer v-else v-model="drawer" temporary location="left" width="300">
    <v-sheet class="pa-4" height="100%">
      <div class="d-flex align-center justify-space-between mb-4">
        <h3 class="text-h6">Фильтры</h3>
        <v-btn icon @click="drawer = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </div>

      <div class="filters-section">
        <h4 class="text-subtitle-2 mb-2">Цена, ₽</h4>
        <v-row dense>
          <v-col cols="6">
            <v-text-field
              v-model.number="minPrice"
              label="От"
              type="number"
              hide-details
              density="compact"
              variant="outlined"
            />
          </v-col>
          <v-col cols="6">
            <v-text-field
              v-model.number="maxPrice"
              label="До"
              type="number"
              hide-details
              density="compact"
              variant="outlined"
            />
          </v-col>
        </v-row>
      </div>

      <div v-if="brands.length" class="filters-section">
        <h4 class="text-subtitle-2 mb-2">Бренд</h4>
        <v-text-field
          v-model="brandSearch"
          density="compact"
          variant="outlined"
          hide-details
          label="Поиск бренда"
          class="mb-2"
        />
        <div class="filters-brand-list">
          <v-checkbox
            v-for="(brand, idx) in visibleBrands"
            :key="'brand-' + idx"
            v-model="selectedBrands"
            multiple
            :label="brand"
            :value="brand"
            density="compact"
            hide-details
            color="primary"
          />
        </div>
      </div>

      <v-divider class="my-4" />

      <div class="d-flex flex-column ga-2">
        <v-btn color="primary" block @click="applyFilters">Показать товары</v-btn>
        <v-btn variant="tonal" block @click="resetFilters">Сбросить</v-btn>
      </div>
    </v-sheet>
  </v-navigation-drawer>
</template>

<script setup>
import { ref, computed, onMounted, defineProps, defineEmits } from 'vue';
import axios from 'axios';

const props = defineProps({
  inline: {
    type: Boolean,
    default: false
  }
});

const drawer = defineModel('drawer', { type: Boolean, default: false });
const emit = defineEmits(['apply']);

const minPrice = ref(null);
const maxPrice = ref(null);
const selectedBrands = ref([]);
const brandSearch = ref('');

const brands = ref([]);

const visibleBrands = computed(() => {
  const q = brandSearch.value.trim().toLowerCase();
  const source = Array.isArray(brands.value) ? brands.value : [];
  if (!q) {
    return source;
  }
  return source.filter((name) => String(name).toLowerCase().includes(q));
});

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/catalog/filters');
    const raw = Array.isArray(data.brands) ? data.brands : [];
    brands.value = [...new Set(raw.map((b) => String(b)))];
  } catch (e) {
    console.error('Ошибка загрузки брендов', e);
  }
});

function applyFilters() {
  emit('apply', {
    minPrice: minPrice.value,
    maxPrice: maxPrice.value,
    brands: selectedBrands.value
  });
  if (!props.inline) {
    drawer.value = false;
  }
}

function resetFilters() {
  minPrice.value = null;
  maxPrice.value = null;
  selectedBrands.value = [];
  brandSearch.value = '';
  applyFilters();
}
</script>

<style scoped>
.filters-panel {
  padding: 14px;
  background: #fff;
}

.filters-panel__header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
  padding-bottom: 8px;
  border-bottom: 1px solid var(--color-border);
}

.filters-section {
  margin-bottom: 16px;
}

.filters-brand-list {
  max-height: 260px;
  overflow: auto;
  padding-right: 4px;
}
</style>