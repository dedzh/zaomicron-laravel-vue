<template>
  <nav class="main-navigation">
    <div class="container">
      <ul class="nav-menu">

        <!--пункты меню  -->
        <li class="nav-item">
          <router-link to="/about" class="nav-link" exact-active-class="active">О кампании</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/catalog" class="nav-link" exact-active-class="active">Каталог</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/service" class="nav-link" exact-active-class="active">Услуги</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/blog" class="nav-link" exact-active-class="active">Блог</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/partners" class="nav-link" exact-active-class="active">Партнерам</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/contacts" class="nav-link" exact-active-class="active">Контакты</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import axios from 'axios';
import CatalogMenu from '@/components/catalog/CatalogMenu.vue';

export default {
  name: 'Navigation',
  components: { CatalogMenu },
  data() {
    return {
      isCatalogOpen: false,
    };
  },
  mounted() {
    document.addEventListener('click', this.handleOutsideClick);
    document.addEventListener('keydown', this.handleEscKey);
  },
  beforeUnmount() {
    document.removeEventListener('click', this.handleOutsideClick);
    document.removeEventListener('keydown', this.handleEscKey);
  },
  methods: {
    toggleCatalogMenu() {
      this.isCatalogOpen = !this.isCatalogOpen;
    },
    closeCatalogMenu() {
      this.isCatalogOpen = false;
    },
    handleOutsideClick(event) {
      if (!this.isCatalogOpen) return;
      const wrapper = this.$refs.catalogWrapper;
      const menu = this.$refs.catalogMenu;
      if (wrapper && !wrapper.contains(event.target) && menu && !menu.$el.contains(event.target)) {
        this.closeCatalogMenu();
      }
    },
    handleEscKey(event) {
      if (event.key === 'Escape' && this.isCatalogOpen) {
        this.closeCatalogMenu();
      }
    },
  },
};
</script>

<style scoped>
/* ===== Основные стили ===== */
.main-navigation {
  background: #f8f9fa;
  border-top: 1px solid #e0e0e0;
  border-bottom: 1px solid #e0e0e0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
}

.nav-menu {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  gap: 30px;
  margin: 0;
  padding: 0;
}

.nav-item {
  position: relative;
}

/* Стили для обычных пунктов меню */
.nav-link {
  display: block;
  padding: 15px 0;
  text-decoration: none;
  color: #333;
  font-weight: 600;
  transition: color 0.2s ease;
  position: relative;
}

.nav-link:hover {
  color: #51AE45;
}

.nav-link.active {
  color: #51AE45;
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: #51AE45;
  border-radius: 2px;
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from {
    transform: scaleX(0);
  }
  to {
    transform: scaleX(1);
  }
}

/* ===== Кнопка каталога ===== */
.catalog-item {
  display: flex;
  align-items: center;
}

.catalog-button {
  display: flex;
  align-items: center;
  gap: 8px;
  background-color: #51AE45;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.catalog-button:hover {
  background-color: #3e8c34;
}

.catalog-button i {
  font-size: 16px;
}

/* ===== Адаптивность ===== */
@media (max-width: 1024px) {
  .nav-menu {
    gap: 20px;
  }
  .nav-link {
    font-size: 14px;
  }
  .catalog-button {
    padding: 6px 12px;
    font-size: 13px;
  }
}

@media (max-width: 768px) {
  .main-navigation {
    overflow-x: visible;
  }
  .nav-menu {
    gap: 15px;
    row-gap: 5px;
  }
  .catalog-button {
    padding: 5px 10px;
    font-size: 12px;
  }
  .catalog-button i {
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .nav-menu {
    gap: 12px;
  }
  .nav-link {
    font-size: 13px;
    padding: 12px 0;
  }
  .catalog-button {
    padding: 4px 8px;
    font-size: 12px;
  }
}
</style>