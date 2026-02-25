<template>
  <v-footer class="bg-grey-darken-4 text-white pa-8">
    <v-container>
      <v-row>
        <v-col cols="3">
          <h3 class="text-h6 mb-4">Компания</h3>
          <v-list density="compact" bg-color="transparent">
            <v-list-item to="#about">О нас</v-list-item>
            <v-list-item to="#history">История</v-list-item>
            <v-list-item to="#certificates">Сертификаты</v-list-item>
            <v-list-item to="#vacancies">Вакансии</v-list-item>
          </v-list>
        </v-col>
        <v-col cols="3">
          <h3 class="text-h6 mb-4">Каталог</h3>
          <v-list density="compact" bg-color="transparent">
            <v-list-item to="/catalog?category=manometers">Манометры</v-list-item>
            <v-list-item to="/catalog?category=thermometers">Термометры</v-list-item>
            <v-list-item to="/catalog?category=flowmeters">Расходомеры</v-list-item>
            <v-list-item to="/catalog?category=analyzers">Анализаторы</v-list-item>
          </v-list>
        </v-col>
        <v-col cols="3">
          <h3 class="text-h6 mb-4">Услуги</h3>
          <v-list density="compact" bg-color="transparent">
            <v-list-item to="/service#verification">Поверка</v-list-item>
            <v-list-item to="/service#calibration">Калибровка</v-list-item>
            <v-list-item to="/service#repair">Ремонт</v-list-item>
            <v-list-item to="/service#consulting">Консультации</v-list-item>
          </v-list>
        </v-col>
        <v-col cols="3">
          <h3 class="text-h6 mb-4">Контакты</h3>
          <address>
            <p>Москва, ул. Примерная, 15</p>
            <p>Тел: <a href="tel:+74951234567" class="text-white">+7 (495) 123-45-67</a></p>
            <p>Email: <a href="mailto:info@zaomicron.ru" class="text-white">info@zaomicron.ru</a></p>
          </address>
        </v-col>
      </v-row>
      <v-divider class="my-4" color="white"></v-divider>
      <v-row justify="space-between">
        <v-col cols="auto">© 2026 MICRON. Все права защищены.</v-col>
        <v-col cols="auto">
          <a href="#privacy" class="text-white text-decoration-none">Политика конфиденциальности</a> |
          <a href="#agreement" class="text-white text-decoration-none">Пользовательское соглашение</a>
        </v-col>
      </v-row>
    </v-container>
  </v-footer>
</template>