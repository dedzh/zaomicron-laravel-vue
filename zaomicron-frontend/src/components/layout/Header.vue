<template>
  <header class="site-header">
    <div class="main-header" style="position: relative;">
      <div class="container">
        <div class="header-content">
          <a href="/" class="logo">
            <img src="@/assets/logo1.gif" class="logo-image" alt="Микрон">
            <div class="logo-text">
              <img src="@/assets/logo.png" class="logo-full" alt="Микрон">
              <div class="logo-tagline">Контрольно-измерительные приборы</div>
            </div>
          </a>

          <div class="catalog-button-wrapper" ref="catalogButtonWrapper">
            <button class="catalog-button" @click="toggleCatalogMenu">
              <i class="fas fa-bars"></i>
              <span>Каталог</span>
            </button>
          </div>

          <div class="search-block">
            <form class="search-form" @submit.prevent="handleSearch">
              <input type="search" class="search-input" v-model="searchQuery" placeholder="Поиск по каталогу...">
              <button type="submit" class="search-button">
                <i class="fas fa-search"></i>
                <span class="search-button-text">Найти</span>
              </button>
            </form>
          </div>

          <div class="user-actions desktop-actions">
            <router-link to="/services" class="action-button">
              <i class="action-icon fas fa-wrench"></i>
              <span>Услуги</span>
            </router-link>
            <router-link to="/favorites" class="action-button">
              <i class="action-icon far fa-heart"></i>
              <span>Избранное</span>
              <!-- <span class="action-badge">0</span> -->
            </router-link>
            <router-link to="/cart" class="action-button">
              <i class="action-icon fas fa-shopping-cart"></i>
              <span>Корзина</span>
              <span class="action-badge">{{ cartTotalQuantity }}</span>
            </router-link>
          </div>

          <button class="mobile-menu-btn" @click="toggleMobileMenu" aria-label="Меню">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>

      <CatalogMenu
        ref="catalogMenu"
        :is-open="isCatalogOpen"
        @close="closeCatalogMenu"
      />
    </div>

    <transition name="slide-fade">
      <div v-if="mobileMenuOpen" class="mobile-menu-overlay" @click="closeMobileMenu">
        <div class="mobile-menu-panel" @click.stop>
          <div class="mobile-menu-header">
            <h3>Меню</h3>
            <button class="close-menu" @click="closeMobileMenu">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="mobile-menu-actions">
            <router-link to="/services" class="mobile-action" @click="closeMobileMenu">
              <i class="fas fa-wrench"></i>
              <span>Услуги</span>
            </router-link>
            <router-link to="/favorites" class="mobile-action" @click="closeMobileMenu">
              <i class="far fa-heart"></i>
              <span>Избранное</span>
            </router-link>
            <router-link to="/cart" class="mobile-action" @click="closeMobileMenu">
              <i class="fas fa-shopping-cart"></i>
              <span>Корзина</span>
              <span class="action-badge">{{ cartTotalQuantity }}</span>
            </router-link>
          </div>
        </div>
      </div>
    </transition>
  </header>
</template>

<script>
import axios from 'axios';
import { useCartStore } from '@/stores/cart';
import { mapState } from 'pinia';
import CatalogMenu from '@/components/catalog/CatalogMenu.vue';

export default {
  name: 'Header',
  components: { CatalogMenu },
  data() {
    return {
      searchQuery: '',
      mobileMenuOpen: false,
      isCatalogOpen: false,
    };
  },
  computed: {
    ...mapState(useCartStore, ['totalQuantity']),
    cartTotalQuantity() {
      return this.totalQuantity || 0;
    },
  },
  mounted() {
    document.addEventListener('click', this.handleOutsideClick);
    document.addEventListener('keydown', this.handleEscKey);
    // Загружаем корзину из localStorage
    const cartStore = useCartStore();
    cartStore.loadFromLocalStorage();
  },
  beforeUnmount() {
    document.body.style.overflow = '';
    document.removeEventListener('click', this.handleOutsideClick);
    document.removeEventListener('keydown', this.handleEscKey);
  },
  methods: {
    async handleSearch() {
      if (!this.searchQuery.trim()) return;
      try {
        const { data } = await axios.get(`/api/catalog/search?q=${encodeURIComponent(this.searchQuery)}`);
        this.$router.push({ path: '/catalog', query: { search: this.searchQuery } });
      } catch (error) {
        console.error(error);
      }
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
      if (this.mobileMenuOpen) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    },
    closeMobileMenu() {
      this.mobileMenuOpen = false;
      document.body.style.overflow = '';
    },
    toggleCatalogMenu() {
      this.isCatalogOpen = !this.isCatalogOpen;
    },
    closeCatalogMenu() {
      this.isCatalogOpen = false;
    },
    handleOutsideClick(event) {
      if (!this.isCatalogOpen) return;
      const wrapper = this.$refs.catalogButtonWrapper;
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
/* Оставляем только специфические стили, общие вынесены в global.css */
.site-header {
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 100;
}

.main-header {
  padding: 15px 0;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.logo {
  display: flex;
  align-items: center;
  text-decoration: none;
  gap: 12px;
  flex-shrink: 0;
  transition: transform 0.2s ease;
}
.logo:hover {
  transform: scale(1.02);
}
.logo-image {
  height: 40px;
  width: auto;
}
.logo-text {
  display: flex;
  flex-direction: column;
}
.logo-full {
  height: 35px;
  width: auto;
  object-fit: contain;
}
.logo-tagline {
  font-size: 12px;
  color: #666;
  margin-top: 2px;
}

.search-block {
  flex: 1;
  max-width: 500px;
  min-width: 200px;
}
.search-form {
  display: flex;
  border: 2px solid var(--color-primary);
  border-radius: 8px;
  overflow: hidden;
}
.search-form:focus-within {
  box-shadow: 0 0 0 3px rgba(81, 174, 69, 0.2);
}
.search-input {
  flex: 1;
  padding: 12px 15px;
  border: none;
  outline: none;
  font-size: 16px;
}
.search-button {
  background: var(--color-primary);
  color: white;
  border: none;
  padding: 0 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background 0.2s ease;
}
.search-button:hover {
  background: var(--color-primary-dark);
}

.user-actions {
  display: flex;
  gap: 20px;
  flex-shrink: 0;
}
.action-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: #333;
  position: relative;
  transition: color 0.2s ease;
}
.action-button:hover {
  color: var(--color-primary);
}
.action-icon {
  font-size: 22px;
  margin-bottom: 5px;
}
.action-button span:not(.action-badge) {
  font-size: 12px;
  font-weight: 500;
}
.action-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #ff4444;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #333;
  padding: 8px;
}
.mobile-menu-btn:hover {
  color: var(--color-primary);
}

/* Мобильное меню */
.mobile-menu-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1000;
  display: flex;
  justify-content: flex-end;
  align-items: flex-start;
}
.mobile-menu-panel {
  width: 280px;
  background: white;
  height: 100%;
  box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
  animation: slideIn 0.3s ease;
}
@keyframes slideIn {
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}
.mobile-menu-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #eee;
}
.mobile-menu-header h3 {
  margin: 0;
  font-size: 18px;
}
.close-menu {
  background: none;
  border: none;
  font-size: 20px;
  cursor: pointer;
  color: #666;
}
.close-menu:hover {
  color: #ff4444;
}
.mobile-menu-actions {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}
.mobile-action {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 12px;
  text-decoration: none;
  color: #333;
  border-radius: 8px;
  transition: background 0.2s ease;
  position: relative;
}
.mobile-action:hover {
  background: #f5f7fa;
}
.mobile-action i {
  width: 24px;
  font-size: 20px;
  color: var(--color-primary);
}
.mobile-action .action-badge {
  position: relative;
  top: 0;
  right: auto;
  margin-left: auto;
}

/* Адаптивность */
@media (max-width: 1024px) {
  .logo-image { height: 35px; }
  .logo-full { height: 30px; }
  .logo-tagline { font-size: 11px; }
  .search-block { max-width: 400px; }
  .user-actions { gap: 15px; }
  .action-icon { font-size: 20px; }
}
@media (max-width: 768px) {
  .main-header { padding: 12px 0; }
  .logo-image { height: 30px; }
  .logo-full { height: 25px; }
  .logo-text { display: none; }
  .search-block { max-width: 300px; }
  .search-input { padding: 10px 12px; font-size: 14px; }
  .search-button { padding: 0 15px; }
  .search-button-text { display: none; }
  .search-button i { font-size: 18px; }
  .user-actions { gap: 12px; }
  .action-button span:not(.action-badge) { font-size: 10px; }
  .action-icon { font-size: 18px; }
}
@media (max-width: 600px) {
  .header-content { flex-wrap: wrap; gap: 12px; }
  .logo { order: 1; }
  .search-block { order: 3; flex: 1 1 100%; max-width: none; margin-top: 5px; }
  .mobile-menu-btn { display: block; order: 2; }
  .desktop-actions { display: none; }
  .logo-text { display: flex; }
  .logo-full { height: 22px; }
  .logo-tagline { font-size: 10px; }
  .logo-image { height: 28px; }
}
@media (max-width: 480px) {
  .logo-image { height: 26px; }
  .logo-full { height: 20px; }
  .logo-tagline { font-size: 9px; }
  .search-input { padding: 8px 10px; font-size: 13px; }
  .search-button { padding: 0 12px; }
  .search-button i { font-size: 16px; }
}
</style>