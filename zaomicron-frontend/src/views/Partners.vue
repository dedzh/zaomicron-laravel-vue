<template>
  <div class="partners-page">
    <div class="partners-container">
      <div class="partners-header">
        <h1>Партнерам</h1>
        <p class="partners-lead">
          Станьте нашим региональным представителем и получите выгодные условия сотрудничества
        </p>
      </div>

      <div class="partners-intro">
        <p>
          Если Ваша организация имеет желание стать нашим региональным представителем с соответствующими полномочиями и
          преференциями, имеет опыт реализации инструмента – приглашаем к обсуждению сотрудничества.
        </p>
        <p>
          Чтобы стать нашим партнером, необходимо направить письмо на электронную почту
          <a href="mailto:info@zaomicron.ru" class="green-link">info@zaomicron.ru</a>, мы обязательно свяжемся с вами для
          обсуждения деталей сотрудничества с учётом удовлетворения взаимных интересов.
        </p>
        <p>Подробности по телефону: <a href="tel:+74951234567" class="green-link">+7 (495) 123-45-67</a>.</p>
      </div>

      <div class="partners-advantages">
        <h2>Преимущества сотрудничества</h2>
        <div class="advantages-grid">
          <div v-for="item in advantages" :key="item.title" class="advantage-card">
            <div class="advantage-icon" aria-hidden="true">
              <i :class="item.icon"></i>
            </div>
            <h3 class="advantage-title">{{ item.title }}</h3>
            <p class="advantage-desc">{{ item.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Partners',
  data() {
    return {
      advantages: [
        {
          icon: 'fas fa-chart-line',
          title: 'ПОСТОЯННЫЙ РОСТ',
          description: 'Постоянное продвижение бренда на рынке'
        },
        {
          icon: 'fas fa-boxes',
          title: 'ОГРОМНЫЙ АССОРТИМЕНТ',
          description: 'Множество видов продукции под любые цели. Складское наличие товара, в т.ч. редких позиций'
        },
        {
          icon: 'fas fa-calendar-alt',
          title: 'ОТСРОЧКА ОПЛАТЫ',
          description: 'Отсрочка платежа до 60 дней.'
        },
        {
          icon: 'fas fa-headset',
          title: 'ИНФОРМАЦИОННАЯ ПОДДЕРЖКА',
          description: 'Предоставление актуальной информации о товарах'
        },
        {
          icon: 'fas fa-percent',
          title: 'ДИЛЕРСКАЯ СКИДКА',
          description: 'Выгодные условия закупки товара'
        }
      ]
    };
  }
};
</script>

<style scoped>
/* Fallback для CSS-переменных */
.partners-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
  --color-primary: #51ae45;
  --color-primary-dark: #2e7d32;
}

.partners-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.partners-page {
  padding: 40px 0;
}

.partners-header {
  text-align: center;
  margin-bottom: 40px;
}

.partners-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.partners-lead {
  color: var(--color-text-light);
  font-size: 18px;
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.6;
}

.partners-intro {
  max-width: 900px;
  margin: 0 auto 50px;
  background: #f8fafc;
  padding: 30px;
  border-radius: 16px;
  border-left: 4px solid var(--color-primary);
  line-height: 1.7;
  color: #334155;
}

.partners-intro p {
  margin-bottom: 16px;
}

.partners-intro p:last-child {
  margin-bottom: 0;
}

.green-link {
  color: var(--color-primary);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.15s;
}

.green-link:hover {
  color: var(--color-primary-dark);
  text-decoration: underline;
}

.partners-advantages h2 {
  text-align: center;
  font-size: 28px;
  margin-bottom: 40px;
  color: #1e293b;
  position: relative;
  display: inline-block;
  left: 50%;
  transform: translateX(-50%);
  white-space: nowrap;
}

.partners-advantages h2::after {
  content: '';
  position: absolute;
  bottom: -12px;
  left: 25%;
  width: 50%;
  height: 3px;
  background: var(--color-primary);
  border-radius: 2px;
}

.advantages-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 30px;
  margin-top: 20px;
}

.advantage-card {
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 16px;
  padding: 28px 20px;
  text-align: center;
  transition: all 0.25s ease;
  box-shadow: var(--shadow-sm);
}

.advantage-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-md);
  border-color: var(--color-primary);
}

.advantage-icon {
  font-size: 48px;
  color: var(--color-primary);
  margin-bottom: 20px;
}

.advantage-title {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 12px;
  color: #0f172a;
  letter-spacing: 0.5px;
}

.advantage-desc {
  font-size: 14px;
  line-height: 1.5;
  color: var(--color-text-light);
}

@media (max-width: 768px) {
  .partners-header h1 {
    font-size: 28px;
  }
  .partners-lead {
    font-size: 16px;
  }
  .partners-intro {
    padding: 20px;
  }
  .partners-advantages h2 {
    font-size: 24px;
    white-space: normal;
  }
  .advantages-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}
</style>