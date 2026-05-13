<template>
  <div class="catalog-pdf-page">
    <div class="catalog-pdf-container">
      <div class="catalog-pdf-header">
        <h1>Каталоги PDF</h1>
        <p class="catalog-pdf-lead">
          Скачайте актуальные каталоги продукции в формате PDF. Подробные описания, технические характеристики и иллюстрации.
        </p>
      </div>

      <div class="catalog-pdf-grid">
        <div v-for="item in catalogs" :key="item.id" class="catalog-card">
          <div class="catalog-card-cover">
            <img :src="item.coverImage" :alt="item.title" loading="lazy" />
            <div class="catalog-card-overlay">
              <i class="fas fa-search-plus"></i>
            </div>
          </div>
          <div class="catalog-card-body">
            <h3 class="catalog-card-title">{{ item.title }}</h3>
            <p class="catalog-card-desc">{{ item.description }}</p>
            <a
              :href="item.pdfUrl"
              class="btn-download"
              target="_blank"
              rel="noopener noreferrer"
              download
            >
              <i class="fas fa-file-pdf" aria-hidden="true"></i>
              Скачать PDF
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// Импорт PDF-файлов
import catIzmerPdf from '@/assets/files/cat_izmer.pdf'
import catalogMicronPdf from '@/assets/files/catalog_micron.pdf'
import coverMicron from '@/assets/files/cat_izmer_pic.png'
import coverCatalog from '@/assets/files/cat_micron.png'


export default {
  name: 'CatalogPDF',
  data() {
    return {
      catalogs: [
        {
          id: 1,
          title: 'Каталог Micron',
          description: 'Полный каталог измерительного инструмента, калибров, слесарного и металлорежущего инструмента.',
          pdfUrl: catIzmerPdf,
          coverImage: coverMicron
        },
        {
          id: 2,
          title: 'Общий каталог инструмента',
          description: 'Широкий ассортимент: от штангенциркулей до микрометров, индикаторов и нутромеров.',
          pdfUrl: catalogMicronPdf,
          coverImage: coverCatalog
        },
       /* {
          id: 3,
          title: 'Каталог Guanglu',
          description: 'Электроизмерительные приборы, мультиметры, токоизмерительные клещи и тестеры.',
          pdfUrl: catalogGuangluPdf,  // замени переменную, если файл отличается
          coverImage: coverGuanglu
        },
        {
          id: 4,
          title: 'Каталог цен',
          description: 'Электроизмерительные приборы, мультиметры, токоизмерительные клещи и тестеры.',
          pdfUrl: catalogGuangluPdf,  // или другой файл
          coverImage: coverGuanglu
        }*/
      ]
    };
  }
};
</script>

<style scoped>
/* Fallback CSS-переменных */
.catalog-pdf-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
  --color-primary: #51ae45;
  --color-primary-dark: #2e7d32;
}

.catalog-pdf-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.catalog-pdf-page {
  padding: 40px 0;
}

.catalog-pdf-header {
  text-align: center;
  margin-bottom: 50px;
}

.catalog-pdf-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.catalog-pdf-lead {
  color: var(--color-text-light);
  font-size: 18px;
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.6;
}

.catalog-pdf-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
  margin-top: 20px;
}

.catalog-card {
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 16px;
  overflow: hidden;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  box-shadow: var(--shadow-sm);
}

.catalog-card:hover {
  transform: translateY(-6px);
  box-shadow: var(--shadow-md);
}

.catalog-card-cover {
  position: relative;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background: #f5f7fa;
}

.catalog-card-cover img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.catalog-card:hover .catalog-card-cover img {
  transform: scale(1.05);
}

.catalog-card-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.25s ease;
  color: white;
  font-size: 32px;
}

.catalog-card:hover .catalog-card-overlay {
  opacity: 1;
}

.catalog-card-body {
  padding: 20px 20px 24px;
}

.catalog-card-title {
  font-size: 20px;
  font-weight: 700;
  margin: 0 0 12px;
  color: #0f172a;
}

.catalog-card-desc {
  font-size: 14px;
  line-height: 1.5;
  color: var(--color-text-light);
  margin-bottom: 20px;
}

.btn-download {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 8px 18px;
  background: #e8f5e9;
  color: var(--color-primary-dark);
  border-radius: 40px;
  font-weight: 600;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-download i {
  font-size: 16px;
}

.btn-download:hover {
  background: var(--color-primary);
  color: white;
}

@media (max-width: 768px) {
  .catalog-pdf-header h1 {
    font-size: 28px;
  }
  .catalog-pdf-lead {
    font-size: 16px;
  }
  .catalog-pdf-grid {
    gap: 20px;
  }
  .catalog-card-body {
    padding: 16px;
  }
  .catalog-card-title {
    font-size: 18px;
  }
}
</style>