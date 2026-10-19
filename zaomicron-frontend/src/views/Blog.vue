<template>
  <div class="blog-page">
    <div class="container">
      <header class="blog-header">
        <h1 class="blog-title">Блог и новости</h1>
        <p class="blog-lead">Актуальные статьи, новинки оборудования и полезные советы</p>
      </header>

      <div v-if="loading" class="loading-spinner">
        <i class="fas fa-spinner fa-pulse"></i> Загрузка статей...
      </div>
      <div v-else-if="error" class="error-message">{{ error }}</div>
      <div v-else-if="articles.length === 0" class="empty-message">
        Пока нет ни одной статьи.
      </div>

      <div v-else class="blog-grid">
        <article
          v-for="article in articles"
          :key="article.id"
          class="blog-card card animated-card"
        >
          <div class="blog-card-image">
            <img
              v-if="article.image"
              :src="getArticleImage(article)"
              :alt="article.title"
              class="blog-card-img"
              @error="onImageError(article, $event)"
            />
            <span v-else class="blog-card-emoji">{{ article.emoji || '📰' }}</span>
          </div>
          <div class="blog-card-body">
            <div class="blog-meta">
              <time :datetime="article.date_iso">{{ article.date }}</time>
            </div>
            <h3 class="blog-card-title">{{ article.title }}</h3>
            <p class="blog-card-excerpt">{{ article.excerpt }}</p>
            <router-link :to="'/blog/' + article.slug" class="btn-read">Читать далее</router-link>
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
</template>

<script>
import axios from 'axios';
import Pagination from '@/components/ui/Pagination.vue';
import { resolveMediaUrl } from '@/utils/media';   // <-- добавлен импорт

export default {
  name: 'Blog',
  components: { Pagination },
  data() {
    return {
      currentPage: 1,
      loading: false,
      error: null,
      articles: [],
      lastPage: 1,
    };
  },
  computed: {
    totalPages() {
      return this.lastPage;
    },
  },
  watch: {
    currentPage() {
      this.fetchArticles();
    },
  },
  mounted() {
    this.fetchArticles();
  },
  methods: {
    async fetchArticles() {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await axios.get('/api/articles', {
          params: {
            page: this.currentPage,
            per_page: 6,
          },
        });
        this.articles = data.data || [];
        this.lastPage = data.meta.last_page || 1;
      } catch (e) {
        this.error = 'Ошибка загрузки статей';
        console.error(e);
      } finally {
        this.loading = false;
      }
    },
    changePage(page) {
      if (page < 1 || page > this.lastPage) return;
      this.currentPage = page;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    getArticleImage(article) {
      // Используем resolveMediaUrl для получения полного URL
      return resolveMediaUrl(article.image, null);
    },
    onImageError(article, event) {
      const img = event.target;
      img.style.display = 'none';
      const parent = img.parentNode;
      if (!parent.querySelector('.blog-card-emoji')) {
        const emoji = document.createElement('span');
        emoji.className = 'blog-card-emoji';
        emoji.textContent = article.emoji || '📰';
        parent.appendChild(emoji);
      }
    },
  },
};
</script>

<style scoped>

.animated-card {
  animation: cardFadeIn 0.6s ease both;
}
@keyframes cardFadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.blog-card-image {
  height: 200px;
  overflow: hidden;
  background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.blog-card-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.blog-card:hover .blog-card-img {
  transform: scale(1.05);
}
.blog-card-emoji {
  font-size: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}

.blog-page { padding: 28px 0 48px; }
.blog-header { margin-bottom: 28px; text-align: center; }
.blog-title {
  font-family: var(--font-heading);
  font-size: 2rem;
  margin: 0 0 12px;
}
.blog-lead { color: var(--color-text-light); max-width: 52ch; margin: 0 auto; }

.blog-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
  margin-top: 20px;
}
.blog-card { display: flex; flex-direction: column; }
.blog-card-body { padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1; }
.blog-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: var(--color-muted);
  font-family: var(--font-body);
}
.blog-card-title {
  font-family: var(--font-heading);
  font-size: 1.1rem;
  margin: 0;
}
.blog-card-excerpt {
  font-family: var(--font-body);
  font-size: 14px;
  color: var(--color-text-light);
  line-height: 1.55;
  flex: 1;
}
.btn-read {
  align-self: flex-start;
  padding: 8px 16px;
  border-radius: var(--radius-sm);
  background: var(--color-primary-light);
  color: var(--color-primary-dark);
  font-family: var(--font-body);
  font-weight: 600;
  text-decoration: none;
  transition: background var(--transition), color var(--transition);
}
.btn-read:hover { background: var(--color-primary); color: #fff; }
.blog-pagination { margin-top: 32px; }

@media (max-width: 600px) {
  .blog-grid { grid-template-columns: 1fr; }
}
</style>