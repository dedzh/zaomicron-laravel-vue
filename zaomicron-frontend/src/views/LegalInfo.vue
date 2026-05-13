<template>
  <div class="legal-page">
    <div class="legal-container">
      <div class="legal-header">
        <h1>Правовая информация</h1>
      </div>
      <div class="legal-content">
        <p>
          Вся информация, представленная на веб-сайте <strong>www.zaomicron.ru</strong>, включая информацию о ценах,
          наличии товаров и их характеристиках, носит ознакомительный характер и
          <strong>не является публичной офертой</strong>, определяемой положениями статьи 437 ГК РФ.
        </p>
        <p>
          Для получения подробной информации о наличии и стоимости указанных товаров и (или) услуг, пожалуйста,
          обращайтесь к менеджеру ООО «MICRON» с помощью <a href="/contacts" class="green-link">формы обратной связи</a>
          или по телефону: <a href="tel:+74951234567" class="green-link">+7 (495) 123-45-67</a>.
        </p>
        <p>
          ООО «MICRON» оставляет за собой право в любой момент вносить изменения в веб-сайт, менять его внешний вид
          и конфигурацию, а также редактировать любые размещенные на страницах веб-сайта тексты без предварительного
          уведомления пользователей.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LegalInfo'
};
</script>

<style scoped>
.legal-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --color-text-light: #666;
  --color-primary: #51ae45;
  --color-primary-dark: #2e7d32;
}

.legal-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.legal-page {
  padding: 40px 0;
}

.legal-header {
  text-align: center;
  margin-bottom: 40px;
}

.legal-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.legal-content {
  background: #fff;
  border: 1px solid var(--color-border);
  border-radius: 16px;
  padding: 30px 40px;
  box-shadow: var(--shadow-sm);
  line-height: 1.7;
  color: #334155;
  font-size: 16px;
}

.legal-content p {
  margin-bottom: 20px;
}

.legal-content p:last-child {
  margin-bottom: 0;
}

.green-link {
  color: var(--color-primary);
  text-decoration: none;
}

.green-link:hover {
  color: var(--color-primary-dark);
  text-decoration: underline;
}

@media (max-width: 768px) {
  .legal-header h1 {
    font-size: 28px;
  }
  .legal-content {
    padding: 20px;
  }
}
</style>