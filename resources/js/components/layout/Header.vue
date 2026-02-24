<template>
  <header class="site-header">
    <div class="main-header">
      <div class="container">
        <div class="header-content">
          <a href="/" class="logo">
            <img src="/zao-refactor-slim-backend/img/logo1.gif" class="logo-image">
            <div class="logo-text">
              <img src="/zao-refactor-slim-backend/img/logo.png" class="logo-full">
              <div class="logo-tagline">Контрольно-измерительные приборы</div>
            </div>
          </a>

          <div class="search-block">
            <form class="search-form" @submit.prevent="handleSearch">
              <input type="search" class="search-input" v-model="searchQuery" placeholder="Поиск по каталогу...">
              <button type="submit" class="search-button">Найти</button>
            </form>
          </div>

          <div class="user-actions">
            <a href="#compare" class="action-button">
              <span class="action-icon">⚖️</span>
              <span>Сравнить</span>
            </a>
            <a href="#favorites" class="action-button">
              <span class="action-icon">❤️</span>
              <span>Избранное</span>
              <span class="action-badge">3</span>
            </a>
            <a href="/pages/cart" class="action-button">
              <span class="action-icon">🛒</span>
              <span>Корзина</span>
              <span class="action-badge">5</span>
            </a>
            <a href="/pages/account" class="action-button">
              <span class="action-icon">👤</span>
              <span>Кабинет</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- Навигацию тоже можно вынести в отдельный компонент и вставить сюда -->
  </header>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: ''
    };
  },
  methods: {
    handleSearch() {
      console.log('Поиск:', this.searchQuery);
    }
  }
};
</script>

<style scoped>

</style>