<template>
  <v-app>
    <AppHeader />
    <v-main>
      <router-view />
    </v-main>
    <AppFooter />
    <!-- Кнопка чата (можно вынести в отдельный компонент) -->
    <v-btn
      class="webim-button"
      color="primary"
      icon="mdi-chat"
      fab
      fixed
      bottom
      right
    ></v-btn>
  </v-app>
</template>

<script setup>
import AppHeader from '@/components/layout/AppHeader.vue'
import AppFooter from '@/components/layout/AppFooter.vue'
</script>