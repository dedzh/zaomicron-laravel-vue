<template>
  <footer class="site-footer">
    <div class="footer-main">
      <div class="container">
        <div class="footer-columns">
          <div class="footer-column">
            <h3>Компания</h3>
            <ul class="footer-links">
              <li><a href="/about">О нас</a></li>
              <li><a href="/history">История</a></li>
              <li><a href="/certificates">Сертификаты</a></li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Каталог</h3>
            <ul class="footer-links">
              <li v-for="cat in categories" :key="cat.id">
                <router-link :to="'/catalog/' + cat.id">{{ cat.name }}</router-link>
              </li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Услуги</h3>
            <ul class="footer-links">
              <li><a href="/services#verification">Поверка</a></li>
              <li><a href="/services#calibration">Калибровка</a></li>
              <li><a href="/services#repair">Ремонт</a></li>
              <li><a href="/services#consulting">Консультации</a></li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Контакты</h3>
            <address>
              <p>Москва, ул. Примерная, д. 1</p>
              <p>Тел: <a href="tel:+74951234567">+7 (495) 123-45-67</a></p>
              <p>Email: <a href="mailto:info@zaomicron.ru">info@zaomicron.ru</a></p>
            </address>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>© 2026 MICRON. Все права защищены.</p>
        <p>
          <a href="/privacy">Политика конфиденциальности</a> |
          <a href="/agreement">Пользовательское соглашение</a>
        </p>
      </div>
    </div>
  </footer>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Footer',
  data() {
    return {
      categories: []
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const { data } = await axios.get('/api/catalog/categories');
        this.categories = data.slice(0, 5); // показываем только первые 5 категорий
      } catch (err) {
        console.error('Ошибка загрузки категорий в футер', err);
      }
    }
  }
};
</script>

<style scoped>
/* Стили остаются, но можно добавить глобальные классы */
.site-footer {
  background: #2c3e50;
  color: #fff;
  margin-top: 50px;
}
.footer-main {
  padding: 40px 0;
}
.footer-columns {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 30px;
}
.footer-column h3 {
  margin-bottom: 20px;
  font-size: 18px;
}
.footer-links {
  list-style: none;
  padding: 0;
}
.footer-links li {
  margin-bottom: 10px;
}
.footer-links a {
  color: #ccc;
  text-decoration: none;
  transition: color 0.2s;
}
.footer-links a:hover {
  color: var(--color-primary);
}
address p {
  margin-bottom: 5px;
}
.footer-bottom {
  background: #1a2a38;
  padding: 15px 0;
  text-align: center;
  font-size: 14px;
}
.footer-bottom a {
  color: #ccc;
  text-decoration: none;
}
.footer-bottom a:hover {
  color: var(--color-primary);
}
</style>