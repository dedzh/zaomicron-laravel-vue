<template>
  <div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 30px;">
      <div>
        <h2>Наши контакты</h2>
        <div v-for="item in contacts" :key="item.label" style="display: flex; gap: 15px; margin-bottom: 25px;">
          <div style="font-size: 24px;">{{ item.icon }}</div>
          <div><h3>{{ item.label }}</h3><p v-html="item.value"></p></div>
        </div>
      </div>
      <div>
        <h2>Форма обратной связи</h2>
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
            <textarea v-model="form.message" class="form-control" rows="4"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%;">Отправить</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      contacts: [
        { icon: '📍', label: 'Адрес', value: 'Москва, ул. Примерная, 15<br>Бизнес-центр "Метрология", офис 304' },
        { icon: '📞', label: 'Телефоны', value: '+7 (495) 123-45-67<br>+7 (800) 123-45-68' },
        { icon: '✉️', label: 'Email', value: 'info@zaomicron.ru<br>sales@zaomicron.ru' },
        { icon: '⏰', label: 'Режим работы', value: 'Пн-Пт: 9:00-18:00' }
      ],
      form: { name: '', phone: '', email: '', message: '' }
    };
  },
  methods: {
    async sendMessage() {
      try {
        await axios.post('/api/feedback', this.form);
        alert('Сообщение отправлено!');
        this.form = { name: '', phone: '', email: '', message: '' };
      } catch (error) {
        console.error(error);
        alert('Ошибка отправки');
      }
    }
  }
};
</script>