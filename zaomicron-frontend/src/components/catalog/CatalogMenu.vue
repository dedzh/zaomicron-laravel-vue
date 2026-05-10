<template>
  <div class="catalog-menu" v-if="isOpen">
    <div class="catalog-menu__container">
      <!-- Левая панель: корневые категории -->
      <div class="catalog-menu__left">
        <ul class="catalog-menu__list">
          <li
            v-for="cat in categories"
            :key="cat.id"
            class="catalog-menu__item"
            @mouseenter="selectCategory(cat)"
            @click="goToCategory(cat.id)"
          >
            <div class="catalog-menu__item-content">
              <span>{{ cat.name }}</span>
              <i class="catalog-menu__arrow vi-icon-arrow-right" v-if="cat.children?.length"></i>
            </div>
          </li>
        </ul>
      </div>

      <!-- Правая панель: подкатегории выбранной категории -->
      <div class="catalog-menu__right" v-if="selectedCategory && selectedCategory.children?.length">
        <div class="catalog-menu__subcategories">
          <div
            v-for="child in selectedCategory.children"
            :key="child.id"
            class="catalog-menu__subcategory"
            @click="goToCategory(child.id)"
          >
            {{ child.name }}
          </div>
        </div>
      </div>
      <div class="catalog-menu__right" v-else-if="selectedCategory">
        <!-- Если нет подкатегорий, можно показать заглушку -->
        <div class="catalog-menu__empty">Нет подкатегорий</div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CatalogMenu',
  props: {
    isOpen: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      categories: [],
      selectedCategory: null,
    };
  },
  watch: {
    isOpen: {
      immediate: true,
      handler(val) {
        if (val && !this.categories.length) {
          this.fetchCategories();
        }
      },
    },
  },
  methods: {
    async fetchCategories() {
      try {
        const { data } = await axios.get('/api/catalog/categories');
        this.categories = data;
        if (this.categories.length) {
          this.selectedCategory = this.categories[0];
        }
      } catch (e) {
        console.error('Ошибка загрузки категорий', e);
      }
    },
    selectCategory(cat) {
      this.selectedCategory = cat;
    },
    goToCategory(id) {
      this.$router.push(`/catalog/${id}`);
      this.$emit('close');
    },
  },
};
</script>

<style scoped>
.catalog-menu {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  border-radius: 0 0 16px 16px;
  overflow: hidden;
}

.catalog-menu__container {
  display: flex;
  min-height: 400px;
  max-height: 70vh;
  overflow-y: auto;
}

.catalog-menu__left {
  width: 280px;
  background-color: #f8f9fa;
  border-right: 1px solid #e9ecef;
}

.catalog-menu__list {
  list-style: none;
  margin: 0;
  padding: 12px 0;
}

.catalog-menu__item {
  cursor: pointer;
  transition: background 0.2s;
}

.catalog-menu__item:hover {
  background-color: #e9ecef;
}

.catalog-menu__item-content {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  font-size: 14px;
  font-weight: 500;
  color: #212529;
}

.catalog-menu__icon {
  width: 20px;
  height: 20px;
  object-fit: contain;
}

.catalog-menu__arrow {
  margin-left: auto;
  font-size: 12px;
  color: #6c757d;
}

.catalog-menu__right {
  flex: 1;
  padding: 16px;
  background: #fff;
}

.catalog-menu__subcategories {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 12px;
}

.catalog-menu__subcategory {
  padding: 8px 12px;
  cursor: pointer;
  font-size: 14px;
  color: #495057;
  border-radius: 6px;
  transition: background 0.2s;
}

.catalog-menu__subcategory:hover {
  background-color: #f1f3f5;
  color: #d60000;
}

.catalog-menu__empty {
  padding: 24px;
  text-align: center;
  color: #6c757d;
}

@media (max-width: 768px) {
  .catalog-menu__container {
    flex-direction: column;
  }
  .catalog-menu__left {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #e9ecef;  }
}
</style>