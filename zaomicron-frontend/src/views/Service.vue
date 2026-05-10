<template>
  <div class="services-page">
    <div class="services-container">
      <div class="services-header">
        <h1>Профессиональные услуги</h1>
        <p>Полный комплекс метрологических услуг для промышленных предприятий. Поверка, калибровка, ремонт и техническое обслуживание контрольно-измерительных приборов.</p>
      </div>

      <div class="services-grid">
        <div v-for="service in serviceCards" :key="service.id" class="service-card">
          <div class="service-card-icon"><i :class="service.icon" aria-hidden="true"></i></div>
          <h3>{{ service.title }}</h3>
          <p>{{ service.description }}</p>
          <a :href="'#' + service.id" class="btn btn-primary" @click.prevent="goToTab(service.id)" :aria-label="`Подробнее об услуге ${service.title}`">Подробнее</a>
        </div>
      </div>

      <div class="services-tabs" role="tablist" aria-label="Услуги">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          class="service-tab"
          :class="{ active: activeTab === tab.id }"
          :id="`tab-${tab.id}`"
          role="tab"
          :aria-selected="activeTab === tab.id"
          :aria-controls="`panel-${tab.id}`"
          @click="activeTab = tab.id"
        >
          <span class="service-tab-icon" aria-hidden="true"><i :class="tab.icon"></i></span>
          {{ tab.name }}
        </button>
      </div>

      <div class="services-content">
        <!-- Поверка -->
        <div
          v-show="activeTab === 'verification'"
          class="service-content"
          id="panel-verification"
          role="tabpanel"
          :aria-labelledby="`tab-verification`"
        >
          <div class="service-detail">
            <h2 class="service-title">
              <span class="service-icon-large" aria-hidden="true"><i class="fas fa-sync-alt"></i></span> Поверка контрольно-измерительных приборов
            </h2>
            <div class="service-description">
              <p>Мы проводим официальную поверку всех типов контрольно-измерительных приборов в собственной аккредитованной лаборатории. Все работы выполняются в соответствии с требованиями ГОСТ и нормативных документов.</p>
              <p>После успешной поверки вы получаете свидетельство о поверке установленного образца, которое подтверждает соответствие прибора метрологическим требованиям.</p>
            </div>
            <div class="service-features">
              <div class="service-feature">
                <h4>Аккредитованная лаборатория</h4>
                <p>Лаборатория аккредитована в области обеспечения единства измерений</p>
              </div>
              <div class="service-feature">
                <h4>Собственное эталонное оборудование</h4>
                <p>Используем только сертифицированные эталоны высокого класса точности</p>
              </div>
              <div class="service-feature">
                <h4>Оперативность</h4>
                <p>Средний срок выполнения поверки – 2 рабочих дня</p>
              </div>
            </div>
            <div class="service-process">
              <h3>Процесс поверки</h3>
              <div class="process-steps">
                <div v-for="step in processSteps" :key="step.number" class="process-step">
                  <div class="process-step-number">{{ step.number }}</div>
                  <h4>{{ step.title }}</h4>
                  <p>{{ step.description }}</p>
                </div>
              </div>
            </div>
            <div class="service-pricing">
              <h3>Стоимость услуг</h3>
              <div class="pricing-table">
                <table>
                  <thead>
                    <tr><th>Тип прибора</th><th>Срок</th><th>Стоимость</th></tr>
                  </thead>
                  <tbody>
                    <tr v-for="price in pricing" :key="price.type">
                      <td>{{ price.type }}</td>
                      <td>{{ price.term }}</td>
                      <td class="price-amount">{{ price.price }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Калибровка -->
        <div
          v-show="activeTab === 'calibration'"
          class="service-content"
          id="panel-calibration"
          role="tabpanel"
          :aria-labelledby="`tab-calibration`"
        >
          <div class="service-detail">
            <h2 class="service-title">
              <span class="service-icon-large" aria-hidden="true"><i class="fas fa-balance-scale"></i></span> Калибровка оборудования
            </h2>
            <div class="service-description">
              <p>Калибровка средств измерений – важнейшая процедура для поддержания точности и надёжности вашего оборудования. Мы выполняем калибровку в собственной лаборатории или на территории заказчика.</p>
              <p>По итогам работ выдаём протоколы калибровки, которые могут быть использованы для внутреннего контроля качества и требований ISO.</p>
            </div>
            <div class="service-features">
              <div class="service-feature">
                <h4>Выездная калибровка</h4>
                <p>Калибруем оборудование на месте эксплуатации без остановки производства</p>
              </div>
              <div class="service-feature">
                <h4>Международное признание</h4>
                <p>Наши протоколы калибровки соответствуют требованиям ISO/IEC 17025</p>
              </div>
            </div>
            <div class="service-pricing">
              <h3>Стоимость калибровки</h3>
              <div class="pricing-table">
                <table>
                  <thead>
                    <tr><th>Тип прибора</th><th>Срок</th><th>Стоимость</th></tr>
                  </thead>
                  <tbody>
                    <tr><td>Манометры</td><td>1-2 дня</td><td class="price-amount">от 1 000 ₽</td></tr>
                    <tr><td>Термометры</td><td>1-2 дня</td><td class="price-amount">от 1 200 ₽</td></tr>
                    <tr><td>Расходомеры</td><td>2-3 дня</td><td class="price-amount">от 3 500 ₽</td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Ремонт -->
        <div
          v-show="activeTab === 'repair'"
          class="service-content"
          id="panel-repair"
          role="tabpanel"
          :aria-labelledby="`tab-repair`"
        >
          <div class="service-detail">
            <h2 class="service-title">
              <span class="service-icon-large" aria-hidden="true"><i class="fas fa-wrench"></i></span> Ремонт оборудования
            </h2>
            <div class="service-description">
              <p>Выполняем ремонт любых типов контрольно-измерительных приборов: манометров, термометров, расходомеров, анализаторов и другого оборудования. Восстанавливаем работоспособность, заменяем изношенные детали и проводим послеремонтную поверку.</p>
              <p>На все работы предоставляем гарантию до 12 месяцев.</p>
            </div>
            <div class="service-features">
              <div class="service-feature">
                <h4>Склад запчастей</h4>
                <p>Оригинальные комплектующие в наличии для большинства моделей</p>
              </div>
              <div class="service-feature">
                <h4>Срочный ремонт</h4>
                <p>Выполняем ремонт в течение 1–3 дней по согласованию</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Консультации -->
        <div
          v-show="activeTab === 'consulting'"
          class="service-content"
          id="panel-consulting"
          role="tabpanel"
          :aria-labelledby="`tab-consulting`"
        >
          <div class="service-detail">
            <h2 class="service-title">
              <span class="service-icon-large" aria-hidden="true"><i class="fas fa-lightbulb"></i></span> Инженерные консультации
            </h2>
            <div class="service-description">
              <p>Наши инженеры помогают подобрать оптимальное оборудование, разработать методики измерений, провести анализ погрешностей и оптимизировать процессы контроля качества.</p>
              <p>Консультации проводятся как в офисе компании, так и выездом на производственную площадку заказчика.</p>
            </div>
          </div>
        </div>

        <!-- Монтаж -->
        <div
          v-show="activeTab === 'installation'"
          class="service-content"
          id="panel-installation"
          role="tabpanel"
          :aria-labelledby="`tab-installation`"
        >
          <div class="service-detail">
            <h2 class="service-title">
              <span class="service-icon-large" aria-hidden="true"><i class="fas fa-plug"></i></span> Монтаж и пусконаладка
            </h2>
            <div class="service-description">
              <p>Профессиональный монтаж контрольно-измерительных приборов, подключение к системам автоматизации, настройка параметров и пусконаладка оборудования.</p>
              <p>Работы выполняются сертифицированными специалистами с соблюдением всех технических регламентов.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="certificates-section">
        <h2 style="text-align: center; margin-bottom: 30px;">Наши сертификаты и допуски</h2>
        <div class="certificates-grid">
          <div v-for="cert in certificates" :key="cert.title" class="certificate-item">
            <div class="certificate-icon" aria-hidden="true"><i :class="cert.icon"></i></div>
            <h4>{{ cert.title }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Service',
  data() {
    return {
      activeTab: 'verification',
      serviceCards: [
        { id: 'verification', icon: 'fas fa-sync-alt', title: 'Поверка приборов', description: 'Официальная поверка контрольно-измерительных приборов с выдачей свидетельств установленного образца.' },
        { id: 'calibration', icon: 'fas fa-balance-scale', title: 'Калибровка', description: 'Калибровка оборудования в собственной лаборатории с применением эталонных средств измерений.' },
        { id: 'repair', icon: 'fas fa-wrench', title: 'Ремонт оборудования', description: 'Качественный ремонт любых контрольно-измерительных приборов с гарантией на выполненные работы.' }
      ],

      processSteps: [
        { number: 1, title: 'Заявка', description: 'Вы оставляете заявку на сайте или по телефону' },
        { number: 2, title: 'Доставка', description: 'Передаете приборы нам курьером или привозите сами' },
        { number: 3, title: 'Поверка', description: 'Проводим поверку в нашей лаборатории' },
        { number: 4, title: 'Документы', description: 'Выдаем свидетельства и возвращаем приборы' }
      ],
      pricing: [
        { type: 'Манометры аналоговые', term: '1-2 дня', price: 'от 800 ₽' },
        { type: 'Манометры цифровые', term: '2-3 дня', price: 'от 1 500 ₽' },
        { type: 'Термометры', term: '1-2 дня', price: 'от 900 ₽' },
        { type: 'Расходомеры', term: '2-4 дня', price: 'от 3 000 ₽' }
      ],
      certificates: [
        { icon: 'fas fa-file-alt', title: 'Свидетельство об аккредитации' },
        { icon: 'fas fa-building', title: 'Лицензия МЧС' },
        { icon: 'fas fa-balance-scale', title: 'Свидетельство поверителя' },
        { icon: 'fas fa-microscope', title: 'Аттестат лаборатории' },
        { icon: 'fas fa-handshake', title: 'Партнерские сертификаты' }
      ]
    };
  },
  methods: {
    goToTab(tabId) {
      this.activeTab = tabId;
      // Плавный скролл к вкладке (опционально)
      const tabElement = document.getElementById(`tab-${tabId}`);
      if (tabElement) {
        tabElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }
  }
};
</script>

<style scoped>
/* Усиленные fallback для CSS-переменных */
.services-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
}

.services-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.services-page {
  padding: 40px 0;
}

.services-header {
  text-align: center;
  margin-bottom: 50px;
}

.services-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.services-header p {
  color: #666;
  font-size: 18px;
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.6;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
  margin-top: 40px;
  margin-bottom: 50px;
}

.service-card {
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 30px;
  text-align: center;
  transition: all 0.3s;
  height: 100%;
}

.service-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-md);
  border-color: #51AE45;
}

.service-card-icon {
  font-size: 48px;
  color: #51AE45;
  margin-bottom: 20px;
}

.service-card h3 {
  font-size: 20px;
  margin-bottom: 15px;
  color: #333;
}

.service-card p {
  color: var(--color-text-light);
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 20px;
}

.service-card .btn {
  margin-top: 10px;
}

.services-tabs {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 40px;
  border-bottom: 1px solid var(--color-border);
  padding-bottom: 10px;
  flex-wrap: wrap;
}

.service-tab {
  padding: 12px 25px;
  background: #f5f7fa;
  border: 1px solid var(--color-border);
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
  transition: all 0.3s;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-family: inherit;
}

.service-tab.active,
.service-tab:hover {
  background: #51AE45;
  color: white;
  border-color: #51AE45;
}

.service-tab-icon {
  font-size: 18px;
}

.services-content {
  position: relative;
}

.service-content {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.service-detail {
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 40px;
  margin-bottom: 40px;
}

.service-title {
  font-size: 28px;
  color: #333;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
}

.service-icon-large {
  font-size: 40px;
  color: #51AE45;
}

.service-description {
  color: var(--color-text-light);
  line-height: 1.7;
  margin-bottom: 30px;
  font-size: 16px;
}

.service-features {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.service-feature {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 6px;
  border-left: 4px solid #51AE45;
}

.service-feature h4 {
  color: #333;
  margin-bottom: 10px;
  font-size: 16px;
}

.service-feature p {
  color: var(--color-text-light);
  font-size: 14px;
  line-height: 1.5;
}

.service-process {
  margin-bottom: 40px;
}

.process-steps {
  display: flex;
  gap: 20px;
  overflow-x: auto;
  padding: 20px 0;
}

.process-step {
  flex: 0 0 200px;
  text-align: center;
  padding: 20px;
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  position: relative;
}

.process-step-number {
  width: 40px;
  height: 40px;
  background: #51AE45;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  margin: 0 auto 15px;
}

.process-step h4 {
  margin-bottom: 10px;
  color: #333;
}

.process-step p {
  color: var(--color-text-light);
  font-size: 14px;
}

.service-pricing {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 30px;
  border-radius: 8px;
  margin-bottom: 40px;
}

.pricing-table {
  overflow-x: auto;
  margin-top: 20px;
}

.pricing-table table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 4px;
  overflow: hidden;
}

.pricing-table th {
  background: #51AE45;
  color: white;
  padding: 15px;
  text-align: left;
  font-weight: 600;
}

.pricing-table td {
  padding: 15px;
  border-bottom: 1px solid var(--color-border);
}

.pricing-table tr:hover {
  background: #f8f9fa;
}

.price-amount {
  color: #51AE45;
  font-weight: 700;
  font-size: 18px;
}

.certificates-section {
  margin-top: 60px;
  padding-top: 40px;
  border-top: 1px solid var(--color-border);
}

.certificates-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.certificate-item {
  background: white;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  transition: all 0.3s;
}

.certificate-item:hover {
  border-color: #51AE45;
  box-shadow: var(--shadow-md);
}

.certificate-icon {
  font-size: 40px;
  color: #51AE45;
  margin-bottom: 15px;
}

.certificate-item h4 {
  font-size: 14px;
  color: #333;
}

@media (max-width: 1024px) {
  .services-tabs {
    justify-content: flex-start;
    overflow-x: auto;
    padding-bottom: 10px;
  }
  .service-tab {
    flex-shrink: 0;
  }
}

@media (max-width: 768px) {
  .services-header h1 {
    font-size: 28px;
  }
  .services-header p {
    font-size: 16px;
  }
  .service-detail {
    padding: 20px;
  }
  .service-title {
    font-size: 24px;
  }
  .process-steps {
    flex-direction: column;
  }
  .process-step {
    flex: 1;
  }
  .services-grid {
    grid-template-columns: 1fr;
  }
}
</style>