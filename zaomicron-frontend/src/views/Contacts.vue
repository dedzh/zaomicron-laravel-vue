<template>
  <div class="contacts-page">
    <div class="contacts-container">
      <div class="contacts-header">
        <h1>Контакты</h1>
        <p>Свяжитесь с нами удобным для вас способом</p>
      </div>

      <div class="contacts-grid">
        <!-- Блок с контактной информацией -->
        <div class="contacts-info">
          <h2>Наши контакты</h2>
          <div class="contacts-list">
            <div v-for="item in contacts" :key="item.label" class="contact-item">
              <div class="contact-icon" aria-hidden="true">
                <i :class="item.iconClass"></i>
              </div>
              <div class="contact-details">
                <h3>{{ item.label }}</h3>
                <p v-html="item.value"></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Блок с формой обратной связи -->
        <div class="contacts-form-wrapper">
          <h2>Форма обратной связи</h2>
          <form @submit.prevent="sendMessage" class="contacts-form">
            <div class="form-group">
              <label for="name" class="form-label">Ваше имя</label>
              <input
                id="name"
                type="text"
                v-model="form.name"
                class="form-control"
                required
                :disabled="isLoading"
              />
            </div>
            <div class="form-group">
              <label for="phone" class="form-label">Телефон</label>
              <input
                id="phone"
                type="tel"
                v-model="form.phone"
                class="form-control"
                required
                :disabled="isLoading"
              />
            </div>
            <div class="form-group">
              <label for="email" class="form-label">Email</label>
              <input
                id="email"
                type="email"
                v-model="form.email"
                class="form-control"
                required
                :disabled="isLoading"
              />
            </div>
            <div class="form-group">
              <label for="message" class="form-label">Сообщение</label>
              <textarea
                id="message"
                v-model="form.message"
                class="form-control"
                rows="4"
                :disabled="isLoading"
              ></textarea>
            </div>
            <button
              type="submit"
              class="btn btn-primary submit-btn"
              :disabled="isLoading"
            >
              <span v-if="isLoading" class="spinner"></span>
              <span v-else>Отправить</span>
            </button>
            <div v-if="formMessage" class="form-message" :class="formMessageType">
              {{ formMessage }}
            </div>
          </form>
        </div>
      </div>

      <!-- Карта проезда -->
      <div class="contacts-map">
        <h2 class="contacts-map-title">Схема проезда</h2>
        <div class="map-container">
          <iframe
            src="https://yandex.ru/map-widget/v1/?um=constructor%3A0f317ae28d099eda91a2b23e84cde217740a69b62dd106cccd1c09a2a17f9cb2&amp;source=constructor"
            width="100%"
            height="350"
            frameborder="0"
            allowfullscreen
            loading="lazy"
            title="Карта проезда к офису компании"
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Contacts',
  data() {
    return {
      contacts: [
        {
          iconClass: 'fas fa-map-marker-alt',
          label: 'Адрес',
          value: 'г. Москва, ул. Электродная, д. 2, стр. 12'
        },
        {
          iconClass: 'fas fa-phone-alt',
          label: 'Телефоны',
          value: '	+7 (495) 775-24-75 многоканальный <br>+7 (800) 505-21-87 (звонок по России бесплатный пн. - пт.: 9:00 – 17.30)'

        },
        {
          iconClass: 'fas fa-envelope',
          label: 'Email',
          value: 'micron@microntools.ru'
        },
        {
          iconClass: 'fas fa-clock',
          label: 'Режим работы',
          value: 'пн-чт: 8.45 – 17.30<br>пт: 8.45 – 16.00'
        }
      ],
      form: {
        name: '',
        phone: '',
        email: '',
        message: ''
      },
      isLoading: false,
      formMessage: '',
      formMessageType: '' // 'success' or 'error'
    };
  },
  methods: {
    async sendMessage() {
      this.formMessage = '';
      this.isLoading = true;

      try {
      
        await axios.post('/api/feedback', this.form);
        this.formMessage = 'Сообщение успешно отправлено!';
        this.formMessageType = 'success';
        this.form = { name: '', phone: '', email: '', message: '' };
      } catch (error) {
        console.error(error);
        this.formMessage = 'Ошибка отправки. Попробуйте позже.';
        this.formMessageType = 'error';
      } finally {
        this.isLoading = false;
        setTimeout(() => {
          this.formMessage = '';
      },7000);
      }
    }
  }
};
</script>

<style scoped>
/* Fallback для CSS-переменных */
.contacts-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
  --color-primary: #51ae45;
  --color-primary-dark: #2e7d32;
}

.contacts-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.contacts-page {
  padding: 40px 0;
}

.contacts-header {
  text-align: center;
  margin-bottom: 50px;
}

.contacts-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.contacts-header p {
  color: var(--color-text-light);
  font-size: 18px;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}

.contacts-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  align-items: start;
  margin-bottom: 60px;
}

/* Блок с контактами */
.contacts-info {
  background: #fff;
  border: 1px solid var(--color-border);
  border-radius: 12px;
  padding: 28px 24px;
  box-shadow: var(--shadow-sm);
}

.contacts-info h2 {
  font-size: 24px;
  margin-top: 0;
  margin-bottom: 24px;
  color: #1e293b;
  border-left: 4px solid var(--color-primary);
  padding-left: 16px;
}

.contacts-list {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.contact-item {
  display: flex;
  gap: 18px;
  align-items: flex-start;
}

.contact-icon {
  flex-shrink: 0;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #e8f5e9;
  border-radius: 50%;
  font-size: 24px;
  color: var(--color-primary);
  transition: transform 0.2s;
}

.contact-item:hover .contact-icon {
  transform: scale(1.05);
}

.contact-details h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  font-weight: 600;
  color: #0f172a;
}

.contact-details p {
  margin: 0;
  color: var(--color-text-light);
  line-height: 1.5;
  font-size: 14px;
}

/* Блок с формой */
.contacts-form-wrapper {
  background: #fff;
  border: 1px solid var(--color-border);
  border-radius: 12px;
  padding: 28px 24px;
  box-shadow: var(--shadow-sm);
}

.contacts-form-wrapper h2 {
  font-size: 24px;
  margin-top: 0;
  margin-bottom: 24px;
  color: #1e293b;
  border-left: 4px solid var(--color-primary);
  padding-left: 16px;
}

.contacts-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-weight: 600;
  color: #334155;
  font-size: 14px;
}

.form-control {
  padding: 12px 14px;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.form-control:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(81, 174, 69, 0.2);
}

.form-control:disabled {
  background: #f8fafc;
  cursor: not-allowed;
}

.submit-btn {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  font-weight: 600;
  background: var(--color-primary);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.15s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.submit-btn:hover:not(:disabled) {
  background: var(--color-primary-dark);
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.spinner {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.form-message {
  margin-top: 12px;
  padding: 10px;
  border-radius: 6px;
  font-size: 14px;
  text-align: center;
}

.form-message.success {
  background: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #a5d6a7;
}

.form-message.error {
  background: #ffebee;
  color: #c62828;
  border: 1px solid #ef9a9a;
}

/* Карта */
.contacts-map {
  margin-top: 20px;
}

.contacts-map-title {
  font-size: 24px;
  margin-bottom: 20px;
  color: #1e293b;
  border-left: 4px solid var(--color-primary);
  padding-left: 16px;
}

.map-container {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  background: #f8fafc;
  line-height: 0;
}

.map-container iframe {
  display: block;
  width: 100%;
  height: 350px;
  border: 0;
}

/* Адаптивность */
@media (max-width: 900px) {
  .contacts-grid {
    grid-template-columns: 1fr;
    gap: 30px;
    margin-bottom: 40px;
  }
}

@media (max-width: 600px) {
  .contacts-header h1 {
    font-size: 28px;
  }
  .contacts-header p {
    font-size: 16px;
  }
  .contacts-info,
  .contacts-form-wrapper {
    padding: 20px;
  }
  .contact-item {
    gap: 12px;
  }
  .contact-icon {
    width: 40px;
    height: 40px;
    font-size: 20px;
  }
  .map-container iframe {
    height: 250px;
  }
}
</style>