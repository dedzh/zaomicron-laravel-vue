<template>
  <div class="services-page">
    <div class="services-container">
      <!-- Hero-заголовок -->
      <div class="services-header">
        <h1 class="services-header__title">Поверка и калибровка средств измерений</h1>
        <p class="services-header__lead">
          Полный комплекс метрологических услуг для промышленных предприятий.
          Работаем с линейно-угловыми, механическими, теплотехническими и физико-химическими измерениями.
        </p>
        <router-link to="/contacts" class="btn btn-primary btn-large">
          <i class="fas fa-envelope"></i> Оставить заявку
        </router-link>
      </div>

      <!-- Карточки основных групп услуг -->
      <div class="services-grid">
        <div v-for="svc in mainServices" :key="svc.id" class="service-card">
          <div class="service-card__icon"><i :class="svc.icon" /></div>
          <h3 class="service-card__title">{{ svc.title }}</h3>
          <p class="service-card__desc">{{ svc.description }}</p>
          <a :href="'#category-' + svc.id" class="btn btn-outline" @click.prevent="scrollToCategory(svc.id)">Подробнее</a>
        </div>
      </div>

      <!-- Блок о партнёре и преимуществах -->
      <div class="partner-block">
        <div class="partner-block__image">
          <img src="@/assets/logo-rmc-kalibron.png" alt="РМЦ Калиброн" @error="imgFallback($event)" />
        </div>
        <div class="partner-block__info">
          <h2>Наш партнёр — РМЦ «Калиброн»</h2>
          <p>
            Метрологическая лаборатория РМЦ «Калиброн» уже более 15 лет оказывает услуги поверки и калибровки средств измерений.
            Аккредитация № РОСС RU.0001.310096. Штат из более чем 30 высококвалифицированных метрологов.
          </p>
          <div class="advantages-grid">
            <div class="advantage"><i class="fas fa-check-circle"></i> 17 лет опыта</div>
            <div class="advantage"><i class="fas fa-check-circle"></i> Собственный парк контрольных калибров</div>
            <div class="advantage"><i class="fas fa-check-circle"></i> Ежегодное расширение аккредитации</div>
            <div class="advantage"><i class="fas fa-check-circle"></i> Строгий отбор и высокая квалификация персонала</div>
          </div>
        </div>
      </div>

      <!-- Категории СИ -->
      <div class="categories-section">
        <h2 class="section-title">Области оказываемых услуг</h2>
        
        <div v-for="cat in serviceCategories" :key="cat.id" class="category-item" :id="'category-' + cat.id">
          <div class="category-item__header">
            <i :class="cat.icon"></i>
            <h3>{{ cat.title }}</h3>
          </div>
          <p>{{ cat.description }}</p>
          <ul v-if="cat.examples" class="examples-list">
            <li v-for="ex in cat.examples" :key="ex">{{ ex }}</li>
          </ul>
        </div>
      </div>

      <!-- Призыв к действию -->
      <div class="cta-block">
        <h2>Готовы начать?</h2>
        <p>Свяжитесь с нами для консультации или расчёта стоимости услуг</p>
        <div class="cta-block__buttons">
          <a href="https://calibronrmc.ru" target="_blank" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> Перейти на сайт партнёра
          </a>
          <router-link to="/contacts" class="btn btn-outline">
            <i class="fas fa-envelope"></i> Наши контакты
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const mainServices = [
  {
    id: 'linear',
    icon: 'fas fa-ruler-combined',
    title: 'Поверка и калибровка линейно-угловых СИ',
    description: 'Штангенциркули, микрометры, нутромеры, глубиномеры, угломеры, меры длины, линейки, координатные машины.'
  },
  {
    id: 'pressure',
    icon: 'fas fa-tachometer-alt',
    title: 'Поверка средств измерений давления и вакуума',
    description: 'Манометры, вакуумметры, датчики давления, барометры, напоромеры, тягонапоромеры.'
  },
  {
    id: 'temperature',
    icon: 'fas fa-thermometer-half',
    title: 'Поверка средств измерений температуры',
    description: 'Термометры, термопары, термопреобразователи, пирометры, тепловизоры, логгеры температуры.'
  },
  {
    id: 'frequency',
    icon: 'fas fa-clock',
    title: 'Поверка средств измерений времени и частоты',
    description: 'Частотомеры, секундомеры, таймеры, генераторы сигналов, осциллографы.'
  },
  {
    id: 'mass',
    icon: 'fas fa-weight-hanging',
    title: 'Поверка средств измерений веса и массы',
    description: 'Весы лабораторные, весы платформенные, гири, дозаторы, весовые терминалы.'
  },
  {
    id: 'chemical',
    icon: 'fas fa-flask',
    title: 'Поверка средств измерений химического состава',
    description: 'Анализаторы, pH-метры, кондуктометры, хроматографы, спектрофотометры, газоанализаторы.'
  }
]

const serviceCategories = ref([
  {
    id: 'linear',
    icon: 'fas fa-ruler-combined',
    title: 'Линейно-угловые средства измерений',
    description: 'Поверка и калибровка штангенциркулей, микрометров, нутромеров, глубиномеров, угломеров, мер длины, линеек, координатных машин, калибров, щупов и шаблонов.',
    examples: ['Штангенциркули', 'Микрометры', 'Нутромеры', 'Глубиномеры', 'Угломеры', 'Меры длины']
  },
  {
    id: 'pressure',
    icon: 'fas fa-tachometer-alt',
    title: 'Средства измерений давления и вакуума',
    description: 'Поверка и калибровка манометров, вакуумметров, датчиков давления, барометров, напоромеров, тягонапоромеров.',
    examples: ['Манометры', 'Вакуумметры', 'Датчики давления', 'Барометры']
  },
  {
    id: 'temperature',
    icon: 'fas fa-thermometer-half',
    title: 'Средства измерений температуры',
    description: 'Поверка и калибровка термометров, термопар, термопреобразователей, пирометров, тепловизоров, логгеров температуры.',
    examples: ['Термометры', 'Термопары', 'Пирометры', 'Тепловизоры']
  },
  {
    id: 'frequency',
    icon: 'fas fa-clock',
    title: 'Средства измерений времени и частоты',
    description: 'Поверка и калибровка частотомеров, секундомеров, таймеров, генераторов сигналов, осциллографов.',
    examples: ['Частотомеры', 'Секундомеры', 'Таймеры', 'Генераторы сигналов']
  },
  {
    id: 'mass',
    icon: 'fas fa-weight-hanging',
    title: 'Средства измерений веса и массы',
    description: 'Поверка и калибровка весов лабораторных, весов платформенных, гирь, дозаторов, весовых терминалов.',
    examples: ['Весы лабораторные', 'Весы платформенные', 'Гири', 'Дозаторы']
  },
  {
    id: 'chemical',
    icon: 'fas fa-flask',
    title: 'Средства измерений химического состава веществ',
    description: 'Поверка и калибровка анализаторов, pH-метров, кондуктометров, хроматографов, спектрофотометров, газоанализаторов.',
    examples: ['Анализаторы', 'pH-метры', 'Хроматографы', 'Спектрофотометры']
  }
])

const scrollToCategory = (id) => {
  const el = document.getElementById('category-' + id)
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

const imgFallback = (event) => {
  if (event?.target) {
    event.target.style.display = 'none'
  }
}
</script>

<style scoped>
/* ---- ПЕРЕМЕННЫЕ (защита от отсутствия глобальных) ---- */
.services-page {
  --color-primary: #51ae45;
  --color-primary-dark: #156135;
  --color-text: #1a1a1a;
  --color-text-light: #4a4a4a;
  --color-bg: #f8fcf8;
  --radius-sm: 8px;
  --radius-lg: 16px;
  --shadow-sm: 0 2px 8px rgba(0,0,0,0.04);
  --shadow-hover: 0 14px 32px rgba(0,0,0,0.09);
}
.services-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}
.services-page {
  padding: 40px 0 60px;
  font-family: 'Arimo', sans-serif;
}

/* ---- HERO ---- */
.services-header {
  text-align: center;
  margin-bottom: 60px;
  padding: 40px 20px;
  background: linear-gradient(135deg, #f8fcf8 0%, #e8f5e9 100%);
  border-radius: var(--radius-lg);
}
.services-header__title {
  font-family: 'Mulish', sans-serif;
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 800;
  color: var(--color-text);
  margin-bottom: 15px;
}
.services-header__lead {
  font-size: 1.1rem;
  color: var(--color-text-light);
  max-width: 700px;
  margin: 0 auto 24px;
  line-height: 1.6;
}

/* ---- ОБЩИЕ КНОПКИ ---- */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 24px;
  border-radius: var(--radius-sm);
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s;
  border: 2px solid transparent;
  font-family: 'Mulish', sans-serif;
}
.btn-primary {
  background: var(--color-primary);
  color: white;
  border-color: var(--color-primary);
}
.btn-primary:hover {
  background: var(--color-primary-dark);
  border-color: var(--color-primary-dark);
  transform: translateY(-1px);
}
.btn-outline {
  background: transparent;
  color: var(--color-primary);
  border-color: var(--color-primary);
}
.btn-outline:hover {
  background: var(--color-primary);
  color: white;
}
.btn-large {
  padding: 16px 36px;
  font-size: 1.1rem;
}

/* ---- КАРТОЧКИ ОСНОВНЫХ УСЛУГ ---- */
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 24px;
  margin-bottom: 60px;
}
.service-card {
  background: white;
  border: 1px solid #eef2ee;
  border-radius: var(--radius-lg);
  padding: 28px;
  text-align: center;
  transition: transform 0.25s, box-shadow 0.25s;
  box-shadow: var(--shadow-sm);
}
.service-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-hover);
}
.service-card__icon {
  font-size: 2.8rem;
  color: var(--color-primary);
  margin-bottom: 16px;
}
.service-card__title {
  font-family: 'Mulish', sans-serif;
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 10px;
  line-height: 1.4;
}
.service-card__desc {
  color: var(--color-text-light);
  margin-bottom: 18px;
  line-height: 1.5;
  font-size: 0.95rem;
}

/* ---- БЛОК ПАРТНЁРА ---- */
.partner-block {
  display: grid;
  grid-template-columns: 200px 1fr;
  gap: 32px;
  align-items: center;
  background: white;
  border: 1px solid #eef2ee;
  border-radius: var(--radius-lg);
  padding: 32px;
  margin-bottom: 60px;
  box-shadow: var(--shadow-sm);
}
.partner-block__image img {
  width: 100%;
  height: auto;
}
.advantages-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-top: 16px;
}
.advantage {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.95rem;
  color: var(--color-text);
}
.advantage i {
  color: var(--color-primary);
}

/* ---- КАТЕГОРИИ УСЛУГ ---- */
.categories-section {
  margin-bottom: 60px;
}
.section-title {
  font-family: 'Mulish', sans-serif;
  font-size: 2rem;
  font-weight: 800;
  text-align: center;
  margin-bottom: 40px;
}
.category-item {
  background: white;
  border: 1px solid #eef2ee;
  border-radius: var(--radius-lg);
  padding: 24px;
  margin-bottom: 20px;
  transition: box-shadow 0.25s;
  box-shadow: var(--shadow-sm);
}
.category-item:hover {
  box-shadow: var(--shadow-hover);
}
.category-item__header {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.4rem;
  font-weight: 700;
  font-family: 'Mulish', sans-serif;
  margin-bottom: 8px;
}
.category-item__header i {
  color: var(--color-primary);
}
.examples-list {
  columns: 2;
  list-style: none;
  padding: 0;
  margin: 12px 0 0;
}
.examples-list li::before {
  content: "• ";
  color: var(--color-primary);
  font-weight: bold;
}

/* ---- CTA ---- */
.cta-block {
  background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
  color: white;
  text-align: center;
  padding: 48px 20px;
  border-radius: var(--radius-lg);
}
.cta-block h2 {
  font-family: 'Mulish', sans-serif;
  font-size: 2rem;
  margin-bottom: 12px;
}
.cta-block__buttons {
  display: flex;
  justify-content: center;
  gap: 16px;
  flex-wrap: wrap;
  margin-top: 24px;
}
.cta-block__buttons .btn-outline {
  border-color: white;
  color: white;
}
.cta-block__buttons .btn-outline:hover {
  background: white;
  color: var(--color-primary-dark);
}

/* ---- АДАПТИВ ---- */
@media (max-width: 768px) {
  .partner-block {
    grid-template-columns: 1fr;
  }
  .advantages-grid {
    grid-template-columns: 1fr;
  }
  .examples-list {
    columns: 1;
  }
}
</style>