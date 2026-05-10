<template>
  <footer class="site-footer">
    <div class="footer-main">
      <div class="container">
        <div class="footer-columns">
          <div class="footer-column">
            <h3>Компания</h3>
            <ul class="footer-links">
              <li><a href="/about">О нас</a></li>
              <li><a href="/certificate">Сертификаты</a></li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Каталог</h3>
            <ul class="footer-links">
              <li v-for="cat in categories" :key="cat.id">
                <router-link :to="'/catalog/' + cat.id">{{ cat.name }}</router-link>
              </li>
              <li>
                <a href="/catalogPDF">Каталоги PDF</a>
              </li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Информация</h3>
            <ul class="footer-links">
              <li><a href="/legalinfo">Правовая информация</a></li>
              <li><a href="/privacy">Политика конфиденциальности</a></li>
              <li><a href="/services">Услуги</a></li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Контакты</h3>
            <address>
              <p>г. Москва, ул. Электродная, д. 2, стр. 12</p>
              <p>Тел: <a href="tel:+74951234567">+7 (495) 104-72-41</a></p>
              <p>Email: <a href="mailto:info@zaomicron.ru">micron@microntools.ru</a></p>
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