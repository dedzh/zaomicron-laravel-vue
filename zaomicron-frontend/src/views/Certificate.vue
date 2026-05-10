<template>
  <div class="certificates-page">
    <div class="certificates-container">
      <div class="certificates-header">
        <h1>Документы и сертификаты</h1>
        <p class="certificates-lead">
          Лицензии, свидетельства и сертификаты соответствия, подтверждающие качество нашей продукции
        </p>
      </div>

      <div class="certificates-grid">
        <div v-for="doc in documents" :key="doc.id" class="certificate-card">
          <a :href="doc.fileUrl" target="_blank" rel="noopener noreferrer" class="certificate-link">
            <div class="certificate-preview">
              <img :src="doc.thumbnail" :alt="doc.title" loading="lazy" />
              <div class="certificate-overlay">
                <i class="fas fa-search-plus" aria-hidden="true"></i>
                <span>Открыть</span>
              </div>
            </div>
            <div class="certificate-info">
              <h3 class="certificate-title">{{ doc.title }}</h3>
              <span class="certificate-type">
                <i class="fas" :class="doc.isPdf ? 'fa-file-pdf' : 'fa-file-image'"></i>
                {{ doc.isPdf ? 'PDF документ' : 'Изображение' }}
              </span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Certificates',
  data() {
    return {
      documents: [
        // Изображения (сертификаты, свидетельства)
        { id: 1, title: 'Свидетельство о регистрации', thumbnail: '@/assets/small/img_7.png', fileUrl: '@/assets/smallbig/img_7.jpg', isPdf: false },
        { id: 2, title: 'Сертификат соответствия', thumbnail: '@/assets/small/img_8.png', fileUrl: '@/assets/smallbig/img_8.jpg', isPdf: false },
        { id: 3, title: 'Лицензия', thumbnail: '@/assets/small/img_10.png', fileUrl: '@/assets/smallbig/img_10.jpg', isPdf: false },
        { id: 4, title: 'Аттестат аккредитации', thumbnail: '@/assets/small/img_16.png', fileUrl: '@/assets/smallbig/img_16.jpg', isPdf: false },
        { id: 5, title: 'Сертификат ISO', thumbnail: '@/assets/small/img_12.png', fileUrl: '@/assets/smallbig/img_12.jpg', isPdf: false },
        { id: 6, title: 'Свидетельство поверителя', thumbnail: '@/assets/small/img_15.png', fileUrl: '@/assets/smallbig/img_15.jpg', isPdf: false },
        { id: 7, title: 'Декларация соответствия', thumbnail: '@/assets/small/img_17.png', fileUrl: '@/assets/smallbig/img_17.jpg', isPdf: false },
        // PDF-сертификаты (образцы)
        { id: 8, title: 'Сертификат № 77991-20', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/77991-20.pdf', isPdf: true },
        { id: 9, title: 'Сертификат № 82404-21', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/82404-21.pdf', isPdf: true },
        { id: 10, title: 'Сертификат № 85174-22', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/85174-22.pdf', isPdf: true },
        { id: 11, title: 'Сертификат № 89267-23', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/89267-23.pdf', isPdf: true },
        { id: 12, title: 'Сертификат № 89737-23', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/89737-23.pdf', isPdf: true },
        { id: 13, title: 'Сертификат № 90358-23', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/90358-23.pdf', isPdf: true },
        { id: 14, title: 'Сертификат № 90362-23', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/90362-23.pdf', isPdf: true },
        { id: 15, title: 'Сертификат № 90663-23', thumbnail: '@/assets/small/sertificat.png', fileUrl: '@/assets/smallbig/90663-23.pdf', isPdf: true },
        { id: 16, title: 'Сертификат № 91149-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/91149-24.pdf', isPdf: true },
        { id: 17, title: 'Сертификат № 91454-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/91454-24.pdf', isPdf: true },
        { id: 18, title: 'Сертификат № 92447-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/92447-24.pdf', isPdf: true },
        { id: 19, title: 'Сертификат № 93019-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/93019-24.pdf', isPdf: true },
        { id: 20, title: 'Сертификат № 93114-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/93114-24.pdf', isPdf: true },
        { id: 21, title: 'Сертификат № 93125-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/93125-24.pdf', isPdf: true },
        { id: 22, title: 'Сертификат № 93689-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/93689-24.pdf', isPdf: true },
        { id: 23, title: 'Сертификат № 93730-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/93730-24.pdf', isPdf: true },
        { id: 24, title: 'Сертификат № 93960-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/93960-24.pdf', isPdf: true },
        { id: 25, title: 'Сертификат № 94065-24', thumbnail: '@/assets/smallsmall/sertificat.png', fileUrl: '@/assets/smallbig/94065-24.pdf', isPdf: true }
      ]
    };
  }
};
</script>

<style scoped>
.certificates-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
  --color-primary: #51ae45;
  --color-primary-dark: #2e7d32;
}

.certificates-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.certificates-page {
  padding: 40px 0;
}

.certificates-header {
  text-align: center;
  margin-bottom: 50px;
}

.certificates-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.certificates-lead {
  color: var(--color-text-light);
  font-size: 18px;
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.6;
}

.certificates-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 30px;
}

.certificate-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: transform 0.25s, box-shadow 0.25s;
}

.certificate-card:hover {
  transform: translateY(-6px);
  box-shadow: var(--shadow-md);
}

.certificate-link {
  text-decoration: none;
  display: block;
  color: inherit;
}

.certificate-preview {
  position: relative;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background: #f5f7fa;
}

.certificate-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.certificate-card:hover .certificate-preview img {
  transform: scale(1.05);
}

.certificate-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  opacity: 0;
  transition: opacity 0.25s;
  color: white;
  font-size: 18px;
}

.certificate-overlay i {
  font-size: 32px;
}

.certificate-card:hover .certificate-overlay {
  opacity: 1;
}

.certificate-info {
  padding: 16px;
  text-align: center;
}

.certificate-title {
  font-size: 16px;
  font-weight: 600;
  margin: 0 0 8px;
  color: #0f172a;
}

.certificate-type {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: var(--color-primary-dark);
  background: #e8f5e9;
  padding: 4px 10px;
  border-radius: 20px;
}

@media (max-width: 768px) {
  .certificates-header h1 {
    font-size: 28px;
  }
  .certificates-lead {
    font-size: 16px;
  }
  .certificates-grid {
    gap: 20px;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  }
  .certificate-title {
    font-size: 14px;
  }
}
</style>