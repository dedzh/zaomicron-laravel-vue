<template>
  <div>
    <h1>Формы</h1>
    <div style="display: flex; gap: 10px; margin: 30px 0; border-bottom: 1px solid #eee; padding-bottom: 10px;">
      <button
        v-for="form in formTypes"
        :key="form.id"
        :style="{ padding: '10px 20px', background: activeForm === form.id ? '#51AE45' : '#f5f7fa', color: activeForm === form.id ? 'white' : '#333', border: 'none', borderRadius: '4px', cursor: 'pointer' }"
        @click="activeForm = form.id"
      >
        {{ form.name }}
      </button>
    </div>

    <div style="max-width: 600px; margin: 0 auto;">
      <h2 style="margin-bottom: 20px;">{{ currentForm.name }}</h2>
      <p style="margin-bottom: 20px;">{{ currentForm.description }}</p>
      <form @submit.prevent="submitForm">
        <div v-for="field in currentForm.fields" :key="field.name" class="form-group">
          <label class="form-label">{{ field.label }}</label>
          <input
            v-if="field.type !== 'select' && field.type !== 'textarea'"
            :type="field.type"
            v-model="formData[field.name]"
            class="form-control"
            :required="field.required"
          >
          <select v-if="field.type === 'select'" v-model="formData[field.name]" class="form-control" :required="field.required">
            <option v-for="option in field.options" :key="option" :value="option">{{ option }}</option>
          </select>
          <textarea v-if="field.type === 'textarea'" v-model="formData[field.name]" class="form-control" :rows="field.rows || 4" :required="field.required"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Отправить</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Forms',
  data() {
    return {
      activeForm: 'feedback',
      formTypes: [
        { id: 'feedback', name: 'Обратная связь', description: 'Задайте вопрос или оставьте отзыв о нашей работе' },
        { id: 'calculate', name: 'Расчет стоимости', description: 'Оставьте заявку и мы рассчитаем стоимость оборудования' },
        { id: 'order', name: 'Заказ оборудования', description: 'Оформите заказ на необходимое оборудование' }
      ],
      forms: {
        feedback: {
          fields: [
            { name: 'name', label: 'Имя', type: 'text', required: true },
            { name: 'phone', label: 'Телефон', type: 'tel', required: true },
            { name: 'email', label: 'Email', type: 'email', required: true },
            { name: 'topic', label: 'Тема', type: 'select', required: true, options: ['Общий вопрос', 'Техническая консультация', 'Жалоба', 'Предложение'] },
            { name: 'message', label: 'Сообщение', type: 'textarea', required: true }
          ]
        },
        calculate: {
          fields: [
            { name: 'name', label: 'Имя', type: 'text', required: true },
            { name: 'phone', label: 'Телефон', type: 'tel', required: true },
            { name: 'equipment', label: 'Необходимое оборудование', type: 'textarea', required: true }
          ]
        },
        order: {
          fields: [
            { name: 'name', label: 'Имя', type: 'text', required: true },
            { name: 'phone', label: 'Телефон', type: 'tel', required: true },
            { name: 'product', label: 'Наименование товара', type: 'text', required: true },
            { name: 'quantity', label: 'Количество', type: 'number', required: true }
          ]
        }
      },
      formData: {}
    };
  },
  computed: {
    currentForm() {
      return {
        name: this.formTypes.find(f => f.id === this.activeForm).name,
        description: this.formTypes.find(f => f.id === this.activeForm).description,
        fields: this.forms[this.activeForm]?.fields || []
      };
    }
  },
  watch: {
    activeForm() {
      this.formData = {};
    }
  },
  methods: {
    submitForm() {
      console.log('Form submitted:', this.formData);
    }
  }
};
</script>