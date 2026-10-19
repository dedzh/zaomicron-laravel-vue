<template>
  <div class="blog-article-page">
    <div class="container">
      <div v-if="loading" class="loading-spinner">
        <i class="fas fa-spinner fa-pulse"></i> Загрузка...
      </div>
      <div v-else-if="error" class="error-message">{{ error }}</div>
      <div v-else>
        <article class="article">
          <header class="article-header">
            <h1 class="article-title">{{ article.title }}</h1>
            <div class="article-meta">
              <time :datetime="article.date_iso">{{ article.date }}</time>
            </div>
          </header>

          <div v-if="articleImage && !imageFailed" class="article-image">
            <img
              :src="articleImage"
              :alt="article.title"
              loading="lazy"
              @error="onImageError"
            />
          </div>

          <div class="article-content" v-html="article.content"></div>

          <div class="article-footer">
            <router-link to="/blog" class="btn btn-back">
              <i class="fas fa-arrow-left"></i> Назад к списку
            </router-link>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { resolveMediaUrl } from '@/utils/media';

export default {
  name: 'BlogArticle',
  data() {
    return {
      article: {},
      loading: false,
      error: null,
      imageFailed: false,
    };
  },
  computed: {
    articleImage() {
      return resolveMediaUrl(this.article.image, null);
    },
  },
  watch: {
    '$route.params.slug': {
      immediate: true,
      handler(slug) {
        if (slug) this.fetchArticle(slug);
      },
    },
  },
  methods: {
    async fetchArticle(slug) {
      this.loading = true;
      this.error = null;
      this.imageFailed = false;
      try {
        const { data } = await axios.get(`/api/articles/${slug}`);
        this.article = data;
      } catch (e) {
        this.error = 'Статья не найдена';
        console.error(e);
      } finally {
        this.loading = false;
      }
    },
    onImageError() {
      this.imageFailed = true;
    },
  },
};
</script>

<style scoped>
.article-content img {
  max-width: 100%;
  height: auto;
  display: block;
  margin: 20px auto;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}
.article-content img:hover {
  transform: scale(1.02);
}
.blog-article-page {
  padding: 28px 0 48px;
  font-family: var(--font-body);
}
.article {
  max-width: 800px;
  margin: 0 auto;
}
.article-header {
  margin-bottom: 24px;
}
.article-title {
  font-family: var(--font-heading);
  font-size: 2rem;
  font-weight: 800;
  color: var(--color-text);
  margin-bottom: 8px;
}
.article-meta {
  color: var(--color-muted);
  font-size: 14px;
}
.article-image {
  margin-bottom: 24px;
  border-radius: var(--radius-md);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}
.article-image img {
  width: 100%;
  height: auto;
  display: block;
}
.article-content {
  line-height: 1.7;
  color: var(--color-text-light);
  margin-bottom: 40px;
}
.article-content h1,
.article-content h2,
.article-content h3 {
  font-family: var(--font-heading);
  color: var(--color-text);
}
.article-content img {
  max-width: 100%;
  height: auto;
  border-radius: var(--radius-sm);
  margin: 16px 0;
}
.article-footer {
  border-top: 1px solid var(--color-border);
  padding-top: 20px;
  text-align: center;
}
.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 22px;
  background: var(--color-primary-light);
  color: var(--color-primary-dark);
  border-radius: var(--radius-sm);
  text-decoration: none;
  font-weight: 600;
  transition: background var(--transition), color var(--transition);
}
.btn-back:hover {
  background: var(--color-primary);
  color: #fff;
}
</style>