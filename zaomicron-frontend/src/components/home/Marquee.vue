<template>
  <div class="marquee-container">
    <div v-if="loading" class="loading-spinner">
      <i class="fas fa-spinner fa-pulse"></i> Загрузка новостей...
    </div>
    <div v-else-if="error" class="error-message" style="margin:0;">
      {{ error }}
    </div>
    <div v-else class="marquee-content">
      <a
        v-for="(item, index) in newsItems"
        :key="index"
        :href="item.link"
      >{{ item.text }}</a>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Marquee',
  data() {
    return {
      newsItems: [],
      loading: false,
      error: null,
    };
  },
  mounted() {
    this.fetchNews();
  },
  methods: {
    async fetchNews() {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await axios.get('/api/news');
        const apiItems = data.map(item => ({
          text: item.name,
          link: `/news/${item.id}`
        }));
        this.newsItems = [
          { text: 'Новинка: расширенная линейка MICRON уже в каталоге', link: '/catalog' },
          ...apiItems
        ];
      } catch (err) {
        console.error('Ошибка загрузки новостей', err);
        this.newsItems = [
          { text: 'Новости MICRON: новые поступления и спецпредложения каждую неделю', link: '/catalog' }
        ];
        this.error = null;
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.marquee-container {
  overflow: hidden;
  white-space: nowrap;
  background: linear-gradient(90deg, #f5f7fa 0%, #e4e8ed 100%);
  padding: 10px 0;
  margin: 20px 0;
  border-radius: var(--radius-sm);
}
.marquee-content {
  display: inline-block;
  animation: marquee 25s linear infinite;
  padding-left: 100%;
  font-family: var(--font-body);
}
.marquee-content:hover {
  animation-play-state: paused;
}
.marquee-content a {
  margin: 0 20px;
  color: var(--color-text);
  text-decoration: none;
  font-weight: 500;
}
.marquee-content a:hover {
  color: var(--color-primary);
  text-decoration: underline;
}
.marquee-content a:not(:last-child)::after {
  content: "|";
  margin-left: 20px;
  color: #ccc;
}
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-100%); }
}
</style>