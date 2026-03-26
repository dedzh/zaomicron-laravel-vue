<template>
  <div class="news-page">
    <div class="news-header">
      <h1>Новости компании</h1>
      <p>Свежие события, анонсы и важные объявления</p>
    </div>

    <div class="news-filters">
      <button v-for="filter in filters" :key="filter" class="filter-btn" :class="{ active: activeFilter === filter }" @click="activeFilter = filter">{{ filter }}</button>
    </div>

    <div class="catalog-layout">
      <div>
        <div class="news-grid">
          <article v-for="item in filteredNews" :key="item.id" class="news-card">
            <div class="news-image">
              <div class="news-date">{{ item.date }}</div>
              <div style="font-size: 40px;">{{ item.emoji }}</div>
            </div>
            <div class="news-content">
              <span class="news-category" :class="item.categoryClass">{{ item.category }}</span>
              <h3 class="news-title">
                <router-link :to="'/news/' + item.id">{{ item.title }}</router-link>
              </h3>
              <p class="news-excerpt">{{ item.excerpt }}</p>
              <router-link :to="'/news/' + item.id" class="news-read-more">
                Читать полностью <span>→</span>
              </router-link>
            </div>
          </article>
        </div>

        <div class="news-pagination">
          <a href="#" class="pagination-btn disabled">←</a>
          <a href="#" class="pagination-btn active">1</a>
          <a href="#" class="pagination-btn">2</a>
          <a href="#" class="pagination-btn">3</a>
          <a href="#" class="pagination-btn">4</a>
          <a href="#" class="pagination-btn">→</a>
        </div>
      </div>

      <aside class="news-sidebar">
        <div class="recent-news-widget">
          <h3>Свежие новости</h3>
          <ul class="recent-news">
            <li v-for="item in recentNews" :key="item.id">
              <router-link :to="'/news/' + item.id">{{ item.title }}</router-link>
              <span class="recent-date">{{ item.date }}</span>
            </li>
          </ul>
        </div>

        <div class="news-archive">
          <h3>Архив новостей</h3>
          <div class="archive-years">
            <a v-for="year in archiveYears" :key="year" href="#">{{ year }}</a>
          </div>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #e8f5e9; border-radius: 4px;">
          <h4>Подписка на новости</h4>
          <p style="font-size: 14px; margin: 10px 0;">Будьте в курсе всех новостей и акций</p>
          <form @submit.prevent="subscribe">
            <input type="email" v-model="subscribeEmail" placeholder="Ваш email" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px;">Подписаться</button>
          </form>
        </div>
      </aside>
    </div>
  </div>
</template>

<script>
export default {
  name: 'News',
  data() {
    return {
      filters: ['Все новости', 'Компания', 'Новинки', 'События', 'Партнерство'],
      activeFilter: 'Все новости',
      newsItems: [
        { id: 1, date: '25.01.2024', emoji: '🏆', category: 'Компания', categoryClass: 'company', title: 'АО ТД «Завод «Микрон» присвоен статус «Успешный поставщик»', excerpt: 'Наша компания получила престижный статус...' },
        { id: 2, date: '18.01.2024', emoji: '🎯', category: 'Новинки', categoryClass: 'products', title: 'Микрометры электронные МКЦ PRO', excerpt: 'Представляем новую линейку цифровых микрометров...' },
        { id: 3, date: '12.01.2024', emoji: '🎪', category: 'События', categoryClass: 'events', title: 'Специализированная выставка «Станкостроение...»', excerpt: 'Приглашаем посетить наш стенд на международной выставке...' },
        { id: 4, date: '05.01.2024', emoji: '🤝', category: 'Партнерство', categoryClass: 'company', title: 'АО ТД «Завод «Микрон» - лауреат конкурса...', excerpt: 'Мы стали лауреатами ежегодного конкурса...' }
      ],
      recentNews: [
        { id: 5, title: 'Запуск новой линии производства', date: '20.01.2024' },
        { id: 6, title: 'Обновление гарантийных условий', date: '15.01.2024' },
        { id: 7, title: 'Новое оборудование в аренду', date: '10.01.2024' }
      ],
      archiveYears: ['2024', '2023', '2022', '2021', '2020'],
      subscribeEmail: ''
    };
  },
  computed: {
    filteredNews() {
      if (this.activeFilter === 'Все новости') return this.newsItems;
      return this.newsItems.filter(item => item.category === this.activeFilter);
    }
  },
  methods: {
    subscribe() {
      console.log('Subscribe with email:', this.subscribeEmail);
    }
  }
};
</script>