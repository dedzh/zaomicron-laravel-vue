<template>
  <!-- Десктопная панель -->
  <template v-if="inline">
    <v-sheet class="filters-panel" rounded="lg" border>
      <div class="filters-panel__header">
        <h3 class="text-subtitle-1 font-weight-bold">Фильтры</h3>
        <v-btn variant="text" size="small" color="primary" @click="resetFilters">Сбросить</v-btn>
      </div>

      <div
        class="filters-scrollable"
        :class="{ 'filters-scrollable--expanded': expanded }"
      >
        <!-- Цена -->
        <div class="filters-section">
          <div class="filters-section__title" @click="toggleSection('price')">
            <v-icon size="20" class="mr-1">mdi-currency-rub</v-icon>
            Цена
            <v-icon size="16" class="ml-auto">
              {{ openSections['price'] ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
            </v-icon>
          </div>
          <v-expand-transition>
            <div v-show="openSections['price']">
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
          </v-expand-transition>
        </div>

        <!-- Бренды -->
        <div v-if="brands.length" class="filters-section">
          <div class="filters-section__title" @click="toggleSection('brands')">
            <v-icon size="20" class="mr-1">mdi-tag-multiple</v-icon>
            Бренд
            <v-icon size="16" class="ml-auto">
              {{ openSections['brands'] ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
            </v-icon>
          </div>
          <v-expand-transition>
            <div v-show="openSections['brands']">
              <v-autocomplete
                v-model="selectedBrands"
                :items="brands"
                multiple
                chips
                label="Выберите бренды"
                density="compact"
                variant="outlined"
                hide-details
                clearable
                auto-select-first
              />
            </div>
          </v-expand-transition>
        </div>

        <!-- Атрибуты (теперь такие же яркие, как бренды) -->
        <div
          v-for="param in params"
          :key="param.id"
          class="filters-section"
        >
          <div
            class="filters-section__title"
            @click="toggleSection('attr-' + param.id)"
          >
            <v-icon size="20" class="mr-1">mdi-format-list-bulleted</v-icon>
            {{ param.name }}
            <v-icon size="16" class="ml-auto">
              {{ openSections['attr-' + param.id] ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
            </v-icon>
          </div>
          <v-expand-transition>
            <div v-show="openSections['attr-' + param.id]">
              <v-autocomplete
                v-model="selectedAttrs[param.id]"
                :items="param.values"
                multiple
                chips
                :label="'Выберите ' + param.name.toLowerCase()"
                density="compact"
                variant="outlined"
                hide-details
                clearable
                auto-select-first
              />
            </div>
          </v-expand-transition>
        </div>
      </div>

      <div class="text-center mt-3">
        <v-btn
          variant="text"
          color="primary"
          @click="expanded = !expanded"
        >
          {{ expanded ? 'Свернуть' : 'Показать все фильтры' }}
        </v-btn>
      </div>

      <v-btn color="primary" block class="mt-4" @click="applyFilters">Показать товары</v-btn>
    </v-sheet>
  </template>

  <!-- Мобильная версия -->
  <v-navigation-drawer v-else v-model="drawer" temporary location="left" width="300">
    <v-sheet class="pa-4" height="100%">
      <div class="d-flex align-center justify-space-between mb-4">
        <h3 class="text-h6">Фильтры</h3>
        <v-btn icon @click="drawer = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </div>

      <div
        class="filters-scrollable"
        :class="{ 'filters-scrollable--expanded': expanded }"
      >
        <!-- Цена -->
        <div class="filters-section">
          <div class="filters-section__title" @click="toggleSection('price')">
            <v-icon size="20" class="mr-1">mdi-currency-rub</v-icon>
            Цена
            <v-icon size="16" class="ml-auto">
              {{ openSections['price'] ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
            </v-icon>
          </div>
          <v-expand-transition>
            <div v-show="openSections['price']">
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
          </v-expand-transition>
        </div>

        <!-- Бренды -->
        <div v-if="brands.length" class="filters-section">
          <div class="filters-section__title" @click="toggleSection('brands')">
            <v-icon size="20" class="mr-1">mdi-tag-multiple</v-icon>
            Бренд
            <v-icon size="16" class="ml-auto">
              {{ openSections['brands'] ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
            </v-icon>
          </div>
          <v-expand-transition>
            <div v-show="openSections['brands']">
              <v-autocomplete
                v-model="selectedBrands"
                :items="brands"
                multiple
                chips
                label="Выберите бренды"
                density="compact"
                variant="outlined"
                hide-details
                clearable
                auto-select-first
              />
            </div>
          </v-expand-transition>
        </div>

        <!-- Атрибуты -->
        <div
          v-for="param in params"
          :key="param.id"
          class="filters-section"
        >
          <div
            class="filters-section__title"
            @click="toggleSection('attr-' + param.id)"
          >
            <v-icon size="20" class="mr-1">mdi-format-list-bulleted</v-icon>
            {{ param.name }}
            <v-icon size="16" class="ml-auto">
              {{ openSections['attr-' + param.id] ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
            </v-icon>
          </div>
          <v-expand-transition>
            <div v-show="openSections['attr-' + param.id]">
              <v-autocomplete
                v-model="selectedAttrs[param.id]"
                :items="param.values"
                multiple
                chips
                :label="'Выберите ' + param.name.toLowerCase()"
                density="compact"
                variant="outlined"
                hide-details
                clearable
                auto-select-first
              />
            </div>
          </v-expand-transition>
        </div>
      </div>

      <div class="text-center mt-3">
        <v-btn
          variant="text"
          color="primary"
          @click="expanded = !expanded"
        >
          {{ expanded ? 'Свернуть' : 'Показать все фильтры' }}
        </v-btn>
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
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { sanitizeFilterPrice } from '@/utils/price';

const props = defineProps({
  inline: { type: Boolean, default: false }
});

const drawer = defineModel('drawer', { type: Boolean, default: false });
const emit = defineEmits(['apply']);

const minPrice = ref(null);
const maxPrice = ref(null);
const selectedBrands = ref([]);
const sortBy = ref('name_asc');

const brands = ref([]);
const params = ref([]);
const selectedAttrs = reactive({});
const expanded = ref(false);

const openSections = reactive({ price: true });

function toggleSection(sectionKey) {
  openSections[sectionKey] = !openSections[sectionKey];
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/api/catalog/filters');
    const raw = Array.isArray(data.brands) ? data.brands : [];
    brands.value = [...new Set(raw)].sort((a, b) => a.localeCompare(b, 'ru'));

    const rawParams = data.params || [];
    const filteredParams = rawParams.filter((p) => p.code !== 'brend');

    filteredParams.forEach((p) => {
      if (!selectedAttrs[p.id]) {
        selectedAttrs[p.id] = [];
      }
      p.values = (p.values || []).sort((a, b) => String(a).localeCompare(String(b), 'ru'));
    });

    params.value = filteredParams;
  } catch (e) {
    console.error('Ошибка загрузки фильтров', e);
  }
});

function applyFilters() {
  try {
    emit('apply', {
      minPrice: sanitizeFilterPrice(minPrice.value),
      maxPrice: sanitizeFilterPrice(maxPrice.value),
      brands: [...selectedBrands.value],
      sort: sortBy.value,
      attrs: { ...selectedAttrs },
    });
    if (!props.inline) {
      drawer.value = false;
    }
  } catch (e) {
    console.error('Ошибка при применении фильтров:', e);
  }
}

function resetFilters() {
  minPrice.value = null;
  maxPrice.value = null;
  selectedBrands.value = [];
  sortBy.value = 'name_asc';

  Object.keys(selectedAttrs).forEach(key => {
    selectedAttrs[key] = [];
  });

  expanded.value = false;
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

.filters-scrollable {
  max-height: 420px;
  overflow-y: auto;
  position: relative;
  transition: max-height 0.3s ease;
}

.filters-scrollable--expanded {
  max-height: none;
}

.filters-section {
  margin-bottom: 16px;
}

.filters-section__title {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
  font-weight: 600;
  font-size: 14px;
  color: #333;
  cursor: pointer;
  user-select: none;
}

.filters-section__title:hover {
  color: var(--v-primary-base);
}
</style>