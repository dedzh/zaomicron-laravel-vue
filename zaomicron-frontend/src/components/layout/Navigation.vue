<template>
  <nav class="main-navigation">
    <div class="container">
      <ul class="nav-menu">
        <li
          v-for="cat in categories"
          :key="cat.id"
          class="nav-item"
          :class="{ 'has-dropdown': cat.children && cat.children.length }"
        >
          <router-link :to="'/catalog/' + cat.id" class="nav-link">{{ cat.name }}</router-link>
          <div v-if="cat.children && cat.children.length" class="dropdown-menu">
            <router-link
              v-for="child in (cat.children || [])"
              :key="child.id"
              :to="'/catalog/' + child.id"
              class="dropdown-item"
            >
              {{ child.name }}
            </router-link>
          </div>
        </li>
        <!-- Статические пункты -->
        <li class="nav-item"><router-link to="/service" class="nav-link">Услуги</router-link></li>
        <li class="nav-item"><router-link to="/contacts" class="nav-link">Контакты</router-link></li>
        <li class="nav-item"><router-link to="/partners" class="nav-link">Партнерам</router-link></li>
      </ul>
    </div>
  </nav>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Navigation',
  data() {
    return {
      categories: [] // обязательно инициализируем как массив
    };
  },
  async created() {
    try {
      const response = await axios.get('/api/catalog/categories');
      const raw = Array.isArray(response.data) ? response.data : [];
      // На случай если бэк вернул relation без children/null — делаем безопасную форму.
      this.categories = raw.map(cat => ({
        ...cat,
        children: Array.isArray(cat.children) ? cat.children : []
      }));
    } catch (error) {
      console.error('Ошибка загрузки категорий', error);
    }
  }
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
  flex-wrap: wrap;          /* перенос на новую строку при нехватке места */
  list-style: none;
  gap: 30px;
  margin: 0;
  padding: 0;
}

.nav-item {
  position: relative;
}

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

/* ===== Выпадающее меню ===== */
.has-dropdown:hover .dropdown-menu {
  display: block;
  animation: fadeIn 0.2s ease;
}

.dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  border-radius: 8px;
  min-width: 200px;
  z-index: 1000;
  padding: 8px 0;
}

.dropdown-item {
  display: block;
  padding: 10px 20px;
  text-decoration: none;
  color: #333;
  transition: background 0.2s ease, color 0.2s ease;
  font-size: 14px;
}

.dropdown-item:hover {
  background: #f5f7fa;
  color: #51AE45;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ===== Адаптивность ===== */
@media (max-width: 1024px) {
  .nav-menu {
    gap: 20px;
  }
  .nav-link {
    font-size: 14px;
  }
}

@media (max-width: 768px) {
  /* Убираем горизонтальный скролл, разрешаем перенос строк */
  .main-navigation {
    overflow-x: visible;
  }
  .nav-menu {
    gap: 15px;
    row-gap: 5px;
  }
  .nav-item {
    /* для правильного позиционирования выпадающего меню */
    position: relative;
  }
  .dropdown-menu {
    position: absolute;
    left: 0;
    min-width: 180px;
    /* на маленьких экранах ограничиваем ширину, чтобы не вылезало за край */
    max-width: 90vw;
  }
  .dropdown-item {
    padding: 10px 15px;
    font-size: 13px;
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
  .dropdown-menu {
    min-width: 160px;
  }
}
</style>