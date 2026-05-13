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
          <button
            v-for="(brand, idx) in visibleBrands"
            :key="'brand-' + idx + '-' + brand"
            type="button"
            class="filters-brand-row"
            :class="{ 'filters-brand-row--on': isBrandSelected(brand) }"
            @click="toggleBrand(brand)"
          >
            <span class="filters-brand-row__icon" aria-hidden="true">
              <v-icon v-if="isBrandSelected(brand)" size="20" color="primary">mdi-checkbox-marked</v-icon>
              <v-icon v-else size="20" color="medium-emphasis">mdi-checkbox-blank-outline</v-icon>
            </span>
            <span class="filters-brand-row__text">{{ brand }}</span>
          </button>
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
          <button
            v-for="(brand, idx) in visibleBrands"
            :key="'brand-' + idx + '-' + brand"
            type="button"
            class="filters-brand-row"
            :class="{ 'filters-brand-row--on': isBrandSelected(brand) }"
            @click="toggleBrand(brand)"
          >
            <span class="filters-brand-row__icon" aria-hidden="true">
              <v-icon v-if="isBrandSelected(brand)" size="20" color="primary">mdi-checkbox-marked</v-icon>
              <v-icon v-else size="20" color="medium-emphasis">mdi-checkbox-blank-outline</v-icon>
            </span>
            <span class="filters-brand-row__text">{{ brand }}</span>
          </button>
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
import { sanitizeFilterPrice } from '@/utils/price';

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

function isBrandSelected(brand) {
  const b = String(brand);
  return selectedBrands.value.some((x) => String(x) === b);
}

function toggleBrand(brand) {
  const b = String(brand);
  const cur = selectedBrands.value.map((x) => String(x));
  const i = cur.indexOf(b);
  if (i === -1) {
    selectedBrands.value = [...cur, b];
  } else {
    cur.splice(i, 1);
    selectedBrands.value = [...cur];
  }
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/catalog/filters');
    const raw = Array.isArray(data.brands) ? data.brands : [];
    brands.value = [...new Set(raw.map((x) => String(x)))].sort((a, b) => a.localeCompare(b, 'ru'));
  } catch (e) {
    console.error('Ошибка загрузки брендов', e);
  }
});

function applyFilters() {
  emit('apply', {
    minPrice: sanitizeFilterPrice(minPrice.value),
    maxPrice: sanitizeFilterPrice(maxPrice.value),
    brands: [...selectedBrands.value]
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
  position: relative;
  z-index: 1;
}

.filters-brand-row {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  width: 100%;
  margin: 0;
  padding: 8px 6px;
  border: none;
  border-radius: 8px;
  background: transparent;
  cursor: pointer;
  text-align: left;
  font: inherit;
  color: inherit;
  line-height: 1.35;
}

.filters-brand-row:hover {
  background: rgba(0, 0, 0, 0.04);
}

.filters-brand-row--on {
  background: rgba(var(--v-theme-primary), 0.08);
}

.filters-brand-row__icon {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  min-height: 24px;
}

.filters-brand-row__text {
  flex: 1;
  word-break: break-word;
}
</style>
