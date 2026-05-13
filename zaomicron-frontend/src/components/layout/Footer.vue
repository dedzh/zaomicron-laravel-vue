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
            <div v-if="loadingCat" class="loading-spinner" style="font-size:0.9rem; padding:0.5rem 0;">
              <i class="fas fa-spinner fa-pulse"></i> Загрузка...
            </div>
            <div v-else-if="catError" class="error-message" style="padding:0.5rem;">
              {{ catError }}
            </div>
            <ul v-else class="footer-links">
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
      categories: [],
      loadingCat: false,
      catError: null,
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      this.loadingCat = true;
      this.catError = null;
      try {
        const { data } = await axios.get('/api/catalog/categories');
        this.categories = data.slice(0, 5);
      } catch (err) {
        console.error('Ошибка загрузки категорий в футер', err);
        this.catError = 'Не удалось загрузить категории';
      } finally {
        this.loadingCat = false;
      }
    }
  }
};
</script>

<style scoped>
.site-footer {
  position: relative;
  background:
    linear-gradient(145deg, #232a33 0%, #59616b 40%, #1f252d 72%, #3a434d 100%);
  color: #e8edf2;
  margin-top: 50px;
  font-family: var(--font-body);
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
}
.site-footer::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(110deg, transparent 15%, rgba(255, 255, 255, 0.14) 48%, transparent 82%);
  mix-blend-mode: screen;
  opacity: 0.45;
  pointer-events: none;
}
.footer-main {
  padding: 40px 0;
  position: relative;
  z-index: 1;
}
.footer-columns {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 30px;
}
.footer-column h3 {
  color: white;
  margin-bottom: 20px;
  font-size: 18px;
  font-family: var(--font-heading);
}
.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}
.footer-links li {
  margin-bottom: 10px;
}
.footer-links a {
  color: rgba(232, 237, 242, 0.92);
  text-decoration: none;
  transition: color var(--transition);
}
.footer-links a:hover {
  color: #35bc1d; 
}
address p {
  margin-bottom: 5px;
  font-style: normal;
}
address a {
  color: rgba(232, 237, 242, 0.92);
  text-decoration: none;
}
address a:hover {
  color: #35bc1d; 
}
.footer-bottom {
  background: linear-gradient(90deg, #11161d 0%, #2a313a 50%, #10151b 100%);
  padding: 15px 0;
  text-align: center;
  font-size: 14px;
  border-top: 1px solid rgba(255, 255, 255, 0.12);
  position: relative;
  z-index: 1;
}
.footer-bottom a {
  color: rgba(208, 216, 224, 0.88);
  text-decoration: none;
}
.footer-bottom a:hover {
   color: #35bc1d; 

}
</style>