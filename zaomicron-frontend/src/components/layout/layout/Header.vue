<template>
  <header>
    <!-- Верхний инфо-бар -->
    <v-toolbar density="compact" color="grey-darken-3" dark>
      <v-container>
        <v-row align="center" justify="space-between">
          <v-col cols="auto">
            <div class="d-flex ga-4">
              <a href="#delivery" class="text-white text-decoration-none">Доставка</a>
              <a href="#payment" class="text-white text-decoration-none">Оплата</a>
              <a href="#warranty" class="text-white text-decoration-none">Гарантия</a>
              <a href="/contacts" class="text-white text-decoration-none">Контакты</a>
              <a href="/partners" class="text-white text-decoration-none">Партнерам</a>
            </div>
          </v-col>
          <v-col cols="auto">
            <div>
              <a href="/account" class="text-white text-decoration-none">Вход</a>
              <span class="text-white mx-2">|</span>
              <a href="#register" class="text-white text-decoration-none">Регистрация</a>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-toolbar>

    <!-- Основная шапка -->
    <v-toolbar density="comfortable" color="white" elevation="2">
      <v-container>
        <v-row align="center">
          <!-- Логотип -->
          <v-col cols="auto">
            <router-link to="/" class="d-flex align-center text-decoration-none">
              <v-img src="/img/logo1.gif" max-width="40" class="mr-2"></v-img>
              <div>
                <v-img src="/img/logo.png" max-width="150"></v-img>
                <div class="text-caption text-grey">Контрольно-измерительные приборы</div>
              </div>
            </router-link>
          </v-col>

          <!-- Поиск -->
          <v-col>
            <v-text-field
              v-model="searchQuery"
              density="comfortable"
              variant="outlined"
              placeholder="Поиск по каталогу..."
              hide-details
              append-inner-icon="mdi-magnify"
              @click:append-inner="handleSearch"
            ></v-text-field>
          </v-col>

          <!-- Действия пользователя -->
          <v-col cols="auto" class="d-flex ga-2">
            <v-btn variant="text" to="/compare" prepend-icon="mdi-scale-balance">Сравнить</v-btn>
            <v-btn variant="text" to="/favorites" prepend-icon="mdi-heart">
              Избранное
              <v-badge v-if="favoritesCount > 0" :content="favoritesCount" color="error" inline></v-badge>
            </v-btn>
            <v-btn variant="text" to="/cart" prepend-icon="mdi-cart">
              Корзина
              <v-badge v-if="cartCount > 0" :content="cartCount" color="error" inline></v-badge>
            </v-btn>
            <v-btn variant="text" to="/account" prepend-icon="mdi-account">Кабинет</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-toolbar>

    <!-- Навигационное меню -->
    <v-toolbar density="compact" color="grey-lighten-3" flat>
      <v-container>
        <v-row>
          <v-col>
            <div class="d-flex ga-6">
              <v-menu open-on-hover>
                <template v-slot:activator="{ props }">
                  <v-btn v-bind="props" to="/catalog" variant="text">Каталог</v-btn>
                </template>
                <v-list>
                  <v-list-item to="/catalog?category=manometers">Манометры</v-list-item>
                  <v-list-item to="/catalog?category=thermometers">Термометры</v-list-item>
                  <v-list-item to="/catalog?category=flowmeters">Расходомеры</v-list-item>
                  <v-list-item to="/catalog?category=analyzers">Анализаторы</v-list-item>
                </v-list>
              </v-menu>
              <v-btn to="/service" variant="text">Услуги</v-btn>
              <v-btn to="/blog" variant="text">Блог</v-btn>
              <v-btn to="/news" variant="text">Новости</v-btn>
              <v-btn to="/partners" variant="text">Партнерам</v-btn>
              <v-btn to="/contacts" variant="text">Контакты</v-btn>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-toolbar>
  </header>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')
const favoritesCount = ref(3)   // заменить на реальные данные из store
const cartCount = ref(5)

function handleSearch() {
  // логика поиска
}
</script>