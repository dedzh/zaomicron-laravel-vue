<template>
  <div>
    <h1>Контакты</h1>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 30px;">
      <div>
        <h2 style="margin-bottom: 20px;">Наши контакты</h2>
        <div style="display: flex; flex-direction: column; gap: 25px;">
          <div v-for="item in contacts" :key="item.label" style="display: flex; gap: 15px;">
            <div style="font-size: 24px;">{{ item.icon }}</div>
            <div>
              <h3>{{ item.label }}</h3>
              <p v-html="item.value"></p>
            </div>
          </div>
        </div>
      </div>

      <div>
        <h2 style="margin-bottom: 20px;">Форма обратной связи</h2>
        <p style="margin-bottom: 20px;">Заполните форму и наш специалист свяжется с вами в течение 30 минут</p>
        <form @submit.prevent="sendMessage">
          <div class="form-group">
            <label class="form-label">Ваше имя</label>
            <input type="text" v-model="form.name" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Телефон</label>
            <input type="tel" v-model="form.phone" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" v-model="form.email" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Сообщение</label>
            <textarea v-model="form.message" class="form-control" rows="4" placeholder="Опишите ваш вопрос..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%;">Отправить сообщение</button>
        </form>
      </div>
    </div>

    <div class="contact-map" style="margin-top: 50px; background: #f5f7fa; padding: 40px; border-radius: 8px; text-align: center;">
      <div style="font-size: 48px;">🗺️</div>
      <h3 style="margin: 20px 0 10px;">Карта проезда</h3>
      <p>Здесь будет интерактивная карта</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Contacts',
  data() {
    return {
      contacts: [
        { icon: '📍', label: 'Адрес', value: 'Москва, ул. Примерная, 15<br>Бизнес-центр "Метрология", офис 304' },
        { icon: '📞', label: 'Телефоны', value: '+7 (495) 123-45-67<br>+7 (800) 123-45-68 (бесплатно по России)' },
        { icon: '✉️', label: 'Email', value: 'info@zaomicron.ru<br>sales@zaomicron.ru<br>support@zaomicron.ru' },
        { icon: '⏰', label: 'Режим работы', value: 'Пн-Пт: 9:00-18:00<br>Сб-Вс: выходные' }
      ],
      form: {
        name: '',
        phone: '',
        email: '',
        message: ''
      }
    };
  },
  methods: {
    sendMessage() {
      console.log('Form submitted', this.form);
    }
  }
};
</script>