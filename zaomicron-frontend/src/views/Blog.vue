<template>
  <div class="blog-page">
    <div class="blog-container">
      <div class="blog-layout">
        <aside class="blog-sidebar">
          <h2 class="sidebar-heading">Категории</h2>
          <nav class="blog-cat-nav" aria-label="Категории блога">
            <button
              v-for="cat in categories"
              :key="cat"
              type="button"
              class="cat-link"
              :class="{ active: cat === activeCategory }"
              :aria-current="cat === activeCategory ? 'page' : undefined"
              @click="activeCategory = cat"
            >
              {{ cat }}
            </button>
          </nav>
        </aside>

        <div class="blog-content">
          <header class="blog-header">
            <h1 class="blog-title">Блог и новости</h1>
            <p class="blog-lead">
              Актуальные статьи, новинки оборудования и полезные советы
            </p>
          </header>

          <div v-if="filteredArticles.length === 0" class="blog-empty">
            <p>Нет статей в этой категории.</p>
          </div>

          <div v-else class="blog-grid">
            <article
              v-for="article in filteredArticles"
              :key="article.id"
              class="blog-card"
            >
              <div class="blog-card-image" aria-hidden="true">{{ article.emoji }}</div>
              <div class="blog-card-body">
                <div class="blog-meta">
                  <time :datetime="article.dateIso">{{ article.date }}</time>
                  <span class="blog-meta-sep" />
                  <span>{{ article.category }}</span>
                </div>
                <h3 class="blog-card-title">{{ article.title }}</h3>
                <p class="blog-card-excerpt">{{ article.excerpt }}</p>
                <router-link :to="article.link" class="btn-read">Читать далее</router-link>
              </div>
            </article>
          </div>

          <Pagination
            v-if="totalPages > 1"
            class="blog-pagination"
            :current-page="currentPage"
            :total-pages="totalPages"
            @page-changed="changePage"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from '@/components/ui/Pagination.vue';

const PAGE_SIZE = 6;

export default {
  name: 'Blog',
  components: { Pagination },
  data() {
    return {
      categories: ['Все статьи', 'Новинки', 'Манометры', 'Термометры', 'Расходомеры', 'Советы по выбору', 'Обслуживание'],
      activeCategory: 'Все статьи',
      currentPage: 1,
      articles: [
        { id: 1, emoji: '📰', date: '12.12.2024', dateIso: '2024-12-12', category: 'Новинки', title: 'Новая линейка цифровых манометров WIKA', excerpt: 'Представляем новую серию цифровых манометров с беспроводной передачей данных…', link: '/blog/1' },
        { id: 2, emoji: '🔧', date: '10.12.2024', dateIso: '2024-12-10', category: 'Советы по выбору', title: 'Как выбрать промышленный термометр', excerpt: 'Полное руководство по выбору термометра для промышленных применений…', link: '/blog/2' },
        { id: 3, emoji: '⚙️', date: '05.12.2024', dateIso: '2024-12-05', category: 'Обслуживание', title: 'Поверка расходомеров: сроки и процедуры', excerpt: 'Всё о периодической поверке расходомеров…', link: '/blog/3' },
      ],
    };
  },
  computed: {
    filteredArticles() {
      let list = this.articles;
      if (this.activeCategory !== 'Все статьи') {
        list = list.filter((a) => a.category === this.activeCategory);
      }
      const start = (this.currentPage - 1) * PAGE_SIZE;
      return list.slice(start, start + PAGE_SIZE);
    },
    totalFilteredCount() {
      if (this.activeCategory === 'Все статьи') {
        return this.articles.length;
      }
      return this.articles.filter((a) => a.category === this.activeCategory).length;
    },
    totalPages() {
      return Math.max(1, Math.ceil(this.totalFilteredCount / PAGE_SIZE));
    },
  },
  watch: {
    activeCategory() {
      this.currentPage = 1;
    },
    totalPages(tp) {
      if (this.currentPage > tp) {
        this.currentPage = tp;
      }
    },
  },
  methods: {
    changePage(page) {
      if (page < 1 || page > this.totalPages) return;
      this.currentPage = page;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
  },
};
</script>

<style scoped>
/* Усиленные fallback для CSS-переменных */
.blog-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
}

.blog-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.blog-page {
  padding: 28px 0 48px;
}

.blog-layout {
  display: grid;
  grid-template-columns: minmax(200px, 240px) minmax(0, 1fr);
  gap: clamp(24px, 4vw, 48px);
  align-items: start;
}

.blog-sidebar {
  position: sticky;
  top: 16px;
  padding: 20px 18px;
  border-radius: 12px;
  border: 1px solid var(--color-border);
  background: #fff;
  box-shadow: var(--shadow-sm);
}

.sidebar-heading {
  font-size: 1rem;
  font-weight: 700;
  margin: 0 0 14px;
  color: #1e293b;
}

.blog-cat-nav {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.cat-link {
  display: block;
  width: 100%;
  text-align: left;
  padding: 10px 12px;
  border: none;
  border-radius: 8px;
  background: transparent;
  color: #334155;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}

.cat-link:hover {
  background: #f1f5f9;
  color: #51ae45;
}

.cat-link.active {
  background: #e8f5e9;
  color: #2e7d32;
  font-weight: 600;
}

.blog-content {
  min-width: 0;
}

.blog-header {
  margin-bottom: 28px;
}

.blog-title {
  font-size: clamp(1.5rem, 2.5vw, 2rem);
  font-weight: 800;
  margin: 0 0 12px;
  color: #0f172a;
  letter-spacing: -0.02em;
}

.blog-lead {
  margin: 0;
  max-width: 52ch;
  color: var(--color-text-light);
  font-size: clamp(14px, 1.5vw, 16px);
  line-height: 1.55;
}

.blog-empty {
  text-align: center;
  padding: 48px 20px;
  background: #f8fafc;
  border-radius: 20px;
  color: #475569;
}

.blog-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));
  gap: clamp(18px, 3vw, 26px);
}

.blog-card {
  display: flex;
  flex-direction: column;
  border: 1px solid var(--color-border);
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: var(--shadow-sm);
  transition: box-shadow 0.2s, transform 0.2s;
}

.blog-card:hover {
  box-shadow: var(--shadow-md);
  transform: translateY(-2px);
}

.blog-card-image {
  font-size: 2.75rem;
  text-align: center;
  padding: 28px 16px;
  background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
}

.blog-card-body {
  padding: 18px 20px 22px;
  display: flex;
  flex-direction: column;
  flex: 1;
  gap: 12px;
}

.blog-meta {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #64748b;
}

.blog-meta-sep {
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: #cbd5e1;
}

.blog-card-title {
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0;
  line-height: 1.35;
  color: #0f172a;
}

.blog-card-excerpt {
  margin: 0;
  flex: 1;
  font-size: 14px;
  line-height: 1.55;
  color: #475569;
}

.btn-read {
  align-self: flex-start;
  margin-top: 4px;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  color: #2e7d32;
  background: #e8f5e9;
  text-decoration: none;
  transition: background 0.15s, color 0.15s;
}

.btn-read:hover {
  background: #51ae45;
  color: #fff;
}

.blog-pagination {
  margin-top: 32px;
}

@media (max-width: 900px) {
  .blog-layout {
    grid-template-columns: 1fr;
  }

  .blog-sidebar {
    position: static;
    order: 2;
  }

  .blog-content {
    order: 1;
  }
}

@media (max-width: 600px) {
  .blog-cat-nav {
    flex-direction: row;
    flex-wrap: wrap;
  }

  .cat-link {
    width: auto;
    flex: 1 1 auto;
    min-width: calc(50% - 6px);
    text-align: center;
    font-size: 13px;
    padding: 8px 10px;
  }

  .blog-grid {
    grid-template-columns: 1fr;
  }
}
</style>