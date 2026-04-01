<template>
  <div class="marquee-container">
    <div class="marquee-content">
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
      newsItems: []
    };
  },
  mounted() {
    this.fetchNews();
  },
  methods: {
    async fetchNews() {
      try {
        const { data } = await axios.get('/api/news');
        this.newsItems = data.map(item => ({
          text: item.name,
          link: `/news/${item.id}`
        }));
      } catch (err) {
        console.error('Ошибка загрузки новостей', err);
      }
    }
  }
};
</script>

<style scoped>
/* Можно оставить пустым или добавить стили бегущей строки */
.marquee-container {
  overflow: hidden;
  white-space: nowrap;
  background: #f0f0f0;
  padding: 10px 0;
}
.marquee-content {
  display: inline-block;
  animation: marquee 20s linear infinite;
}
.marquee-content a {
  margin-right: 40px;
  text-decoration: none;
  color: #333;
}
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
</style>