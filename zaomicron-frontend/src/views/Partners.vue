<template>
  <div>
    <div style="text-align: center; padding: 40px 0;">
      <h1>Партнерская программа ZAOMICRON</h1>
      <p style="font-size: 1.2rem; margin-top: 20px;">Станьте официальным партнером и получите эксклюзивные условия сотрудничества</p>
    </div>

    <div>
      <h2 style="text-align: center; margin-bottom: 40px;">Преимущества для партнеров</h2>
      <div class="benefits-grid">
        <div v-for="benefit in benefits" :key="benefit.title" class="benefit-card">
          <div class="benefit-icon">{{ benefit.icon }}</div>
          <h3 class="benefit-title">{{ benefit.title }}</h3>
          <p>{{ benefit.description }}</p>
        </div>
      </div>
    </div>

    <div style="margin-top: 60px; max-width: 800px; margin-left: auto; margin-right: auto;">
      <h2 style="text-align: center;">Стать партнером</h2>
      <p style="text-align: center; margin-bottom: 30px;">Заполните заявку и наш менеджер свяжется с вами</p>
      <form @submit.prevent="submitPartner">
        <div class="form-group">
          <label class="form-label">Название компании</label>
          <input type="text" v-model="form.company" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Сфера деятельности</label>
          <input type="text" v-model="form.activity" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Контактное лицо</label>
          <input type="text" v-model="form.contactPerson" class="form-control" required>
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
          <label class="form-label">Комментарий</label>
          <textarea v-model="form.comment" class="form-control" rows="4" placeholder="Опишите ваши потребности..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Отправить заявку</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Partners',
  data() {
    return {
      benefits: [
        { icon: '💰', title: 'Выгодные цены', description: 'Специальные партнерские цены и оптовые скидки до 30%' },
        { icon: '🚚', title: 'Быстрая поставка', description: 'Приоритетная отгрузка и резерв товара на складе' },
        { icon: '📚', title: 'Обучение', description: 'Техническое обучение и маркетинговая поддержка' },
        { icon: '🛡️', title: 'Гарантии', description: 'Расширенная гарантия и сервисное обслуживание' }
      ],
      form: {
        company: '',
        activity: '',
        contactPerson: '',
        phone: '',
        email: '',
        comment: ''
      }
    };
  },
  methods: {
    submitPartner() {
      console.log('Partner form', this.form);
    }
  }
};
</script>