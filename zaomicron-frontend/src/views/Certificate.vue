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
              <!-- Всегда отображаем img: для PDF – data‑URI иконки, для картинок – само изображение -->
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
// Импорт изображений (превью и они же открываются по клику)
import img1 from '@/assets/docs/img_1.jpg'
import img2 from '@/assets/docs/img_2.jpg'
import img3 from '@/assets/docs/img_3.jpg'
import img4 from '@/assets/docs/img_4.jpg'
import img5 from '@/assets/docs/img_5.jpg'
import img6 from '@/assets/docs/img_6.jpg'
import img7 from '@/assets/docs/img_7.jpg'

// Импорт PDF‑файлов (список из вашего сообщения)
import pdf85174 from '@/assets/docs/85174-22.pdf'
import pdf89267 from '@/assets/docs/89267-23.pdf'
import pdf89737 from '@/assets/docs/89737-23.pdf'
import pdf90358 from '@/assets/docs/90358-23.pdf'
import pdf90362 from '@/assets/docs/90362-23.pdf'
import pdf90663 from '@/assets/docs/90663-23.pdf'
import pdf91149 from '@/assets/docs/91149-24.pdf'
import pdf91454 from '@/assets/docs/91454-24.pdf'
import pdf92447 from '@/assets/docs/92447-24.pdf'
import pdf93019 from '@/assets/docs/93019-24.pdf'
import pdf93114 from '@/assets/docs/93114-24.pdf'
import pdf93125 from '@/assets/docs/93125-24.pdf'
import pdf93689 from '@/assets/docs/93689-24.pdf'
import pdf93730 from '@/assets/docs/93730-24.pdf'
import pdf93960 from '@/assets/docs/93960-24.pdf'
import pdf94065 from '@/assets/docs/94065-24.pdf'

// data‑URI иконки PDF (серая, нейтральная, не зависит от внешних файлов)
const PDF_ICON = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 48 48\'%3E%3Cpath fill=\'%23E53935\' d=\'M40 45H8c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h22l12 12v28c0 1.1-.9 2-2 2z\'/%3E%3Cpath fill=\'%23FFCDD2\' d=\'M38.5 14H29c-1.1 0-2-.9-2-2V3.5L38.5 14z\'/%3E%3Ctext x=\'12\' y=\'34\' font-family=\'Arial, sans-serif\' font-size=\'10\' font-weight=\'bold\' fill=\'%23fff\'%3EPDF%3C/text%3E%3C/svg%3E'

export default {
  name: 'Certificates',
  data() {
    return {
      documents: [
        // 7 картинок (открываются сами себя)
        { id: 1, title: 'Свидетельство о регистрации',    thumbnail: img1, fileUrl: img1, isPdf: false },
        { id: 2, title: 'Сертификат соответствия',       thumbnail: img2, fileUrl: img2, isPdf: false },
        { id: 3, title: 'Лицензия',                      thumbnail: img3, fileUrl: img3, isPdf: false },
        { id: 4, title: 'Аттестат аккредитации',         thumbnail: img4, fileUrl: img4, isPdf: false },
        { id: 5, title: 'Сертификат ISO',                thumbnail: img5, fileUrl: img5, isPdf: false },
        { id: 6, title: 'Свидетельство поверителя',      thumbnail: img6, fileUrl: img6, isPdf: false },
        { id: 7, title: 'Декларация соответствия',       thumbnail: img7, fileUrl: img7, isPdf: false },

        // 16 PDF‑документов (эскиз – встроенная иконка)
        { id: 8,  title: 'Сертификат № 85174-22', thumbnail: PDF_ICON, fileUrl: pdf85174, isPdf: true },
        { id: 9,  title: 'Сертификат № 89267-23', thumbnail: PDF_ICON, fileUrl: pdf89267, isPdf: true },
        { id: 10, title: 'Сертификат № 89737-23', thumbnail: PDF_ICON, fileUrl: pdf89737, isPdf: true },
        { id: 11, title: 'Сертификат № 90358-23', thumbnail: PDF_ICON, fileUrl: pdf90358, isPdf: true },
        { id: 12, title: 'Сертификат № 90362-23', thumbnail: PDF_ICON, fileUrl: pdf90362, isPdf: true },
        { id: 13, title: 'Сертификат № 90663-23', thumbnail: PDF_ICON, fileUrl: pdf90663, isPdf: true },
        { id: 14, title: 'Сертификат № 91149-24', thumbnail: PDF_ICON, fileUrl: pdf91149, isPdf: true },
        { id: 15, title: 'Сертификат № 91454-24', thumbnail: PDF_ICON, fileUrl: pdf91454, isPdf: true },
        { id: 16, title: 'Сертификат № 92447-24', thumbnail: PDF_ICON, fileUrl: pdf92447, isPdf: true },
        { id: 17, title: 'Сертификат № 93019-24', thumbnail: PDF_ICON, fileUrl: pdf93019, isPdf: true },
        { id: 18, title: 'Сертификат № 93114-24', thumbnail: PDF_ICON, fileUrl: pdf93114, isPdf: true },
        { id: 19, title: 'Сертификат № 93125-24', thumbnail: PDF_ICON, fileUrl: pdf93125, isPdf: true },
        { id: 20, title: 'Сертификат № 93689-24', thumbnail: PDF_ICON, fileUrl: pdf93689, isPdf: true },
        { id: 21, title: 'Сертификат № 93730-24', thumbnail: PDF_ICON, fileUrl: pdf93730, isPdf: true },
        { id: 22, title: 'Сертификат № 93960-24', thumbnail: PDF_ICON, fileUrl: pdf93960, isPdf: true },
        { id: 23, title: 'Сертификат № 94065-24', thumbnail: PDF_ICON, fileUrl: pdf94065, isPdf: true }
      ]
    };
  }
};
</script>

<style scoped>
/* без изменений, как в вашем коде */
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