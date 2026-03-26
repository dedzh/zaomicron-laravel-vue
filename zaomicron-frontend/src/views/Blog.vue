<template>
  <div>
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 40px;">
      <aside>
        <h3 style="margin-bottom: 15px;">Категории</h3>
        <ul style="list-style: none;">
          <li v-for="cat in categories" :key="cat" :style="{ marginBottom: '10px' }">
            <a href="#" :style="{ color: cat === activeCategory ? '#51AE45' : '#333', textDecoration: 'none', fontWeight: cat === activeCategory ? 600 : 400 }" @click.prevent="activeCategory = cat">{{ cat }}</a>
          </li>
        </ul>
      </aside>

      <div>
        <h1>Блог и новости</h1>
        <p style="margin-bottom: 30px; color: #666;">Актуальные статьи, новинки оборудования и полезные советы</p>

        <div class="blog-grid">
          <article v-for="article in articles" :key="article.id" class="blog-card">
            <div class="blog-card-image">{{ article.emoji }}</div>
            <div class="blog-card-content">
              <div style="display: flex; gap: 15px; color: #666; font-size: 14px; margin-bottom: 10px;">
                <span>{{ article.date }}</span>
                <span>{{ article.category }}</span>
              </div>
              <h3 style="margin-bottom: 10px;">{{ article.title }}</h3>
              <p style="margin-bottom: 15px;">{{ article.excerpt }}</p>
              <router-link :to="article.link" class="btn btn-secondary">Читать далее</router-link>
            </div>
          </article>
        </div>

        <Pagination :current-page="1" :total-pages="5" @page-changed="changePage" />
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from '@/components/ui/Pagination.vue';

export default {
  name: 'Blog',
  components: { Pagination },
  data() {
    return {
      categories: ['Все статьи', 'Новинки', 'Манометры', 'Термометры', 'Расходомеры', 'Советы по выбору'],
      activeCategory: 'Все статьи',
      articles: [
        { id: 1, emoji: '📰', date: '12.12.2024', category: 'Новинки', title: 'Новая линейка цифровых манометров WIKA', excerpt: 'Представляем новую серию цифровых манометров с беспроводной передачей данных...', link: '/blog/1' },
        { id: 2, emoji: '🔧', date: '10.12.2024', category: 'Советы', title: 'Как выбрать промышленный термометр', excerpt: 'Полное руководство по выбору термометра для промышленных применений...', link: '/blog/2' },
        { id: 3, emoji: '⚙️', date: '05.12.2024', category: 'Обслуживание', title: 'Поверка расходомеров: сроки и процедуры', excerpt: 'Все о периодической поверке расходомеров...', link: '/blog/3' }
      ]
    };
  },
  methods: {
    changePage(page) {
      console.log('Page changed to', page);
    }
  }
};
</script>