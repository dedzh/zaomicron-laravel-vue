<template>
  <div class="about-page">
    <div class="about-container">
      <div class="about-header">
        <h1>О компании</h1>
        <p class="about-lead">
          Производство и поставки измерительного инструмента, приборов и оснастки для промышленных предприятий России
        </p>
      </div>

      <div class="about-content">
        <div class="about-description">
          <p>
            Компания «MICRON» объединяет 3 производства и осуществляет поставки инструмента, приборов, станочной оснастки
            для обеспечения бесперебойной работы фабрик и заводов в России.
            Наша компания растет и развивается: производимый заводами измерительный инструмент внесен в Госреестр средств
            измерений РФ, вся поставляемая продукция соответствует высоким российским (ГОСТ, ТУ) и мировым стандартам.
          </p>
          <p><strong>Специализацией компании «MICRON» является следующая продукция:</strong></p>
          <ul class="products-list">
            <li><i class="fas fa-ruler" aria-hidden="true"></i> <strong>Измерительный инструмент:</strong> штангенциркули, микрометры, глубиномеры, нутромеры, нормалемеры и меры длины</li>
            <li><i class="fas fa-circle-notch" aria-hidden="true"></i> <strong>Калибры</strong> общепромышленного и нефтяного сортамента</li>
            <li><i class="fas fa-microscope" aria-hidden="true"></i> <strong>Приборы:</strong> биениемеры, квадранты, микроскопы и пр.</li>
            <li><i class="fas fa-tools" aria-hidden="true"></i> <strong>Слесарно-монтажный инструмент:</strong> ключи, плоскогубцы, кусачки, ножницы, домкраты, ножовки, напильники</li>
            <li><i class="fas fa-cut" aria-hidden="true"></i> <strong>Металлорежущий инструмент:</strong> фрезы, сверла, резцы, плашки и клуппы</li>
            <li><i class="fas fa-cogs" aria-hidden="true"></i> <strong>Станочная оснастка</strong></li>
            <li><i class="fas fa-cog" aria-hidden="true"></i> <strong>Шарико-винтовые передачи (ШВП):</strong> в наличии и по чертежам</li>
          </ul>
          <p>
            Это тысячи наименований продукции от российских и зарубежных производителей, благодаря которой функционируют
            машиностроительные, металлообрабатывающие предприятия, предприятия атомной отрасли, предприятия из сферы нефтедобычи.
          </p>
          <p>
            Наша компания предлагает широкий ассортимент продукции таких известных брендов как
            <strong>Micron, ЧИЗ, Ситомо, Shan, Shahe, Калиброн, Эталон.</strong>
            Огромное складское наличие позволяет нам точно в минимальные сроки производить отгрузку инструмента в любую точку России.
            <br><br>Мы обеспечиваем точность!
          </p>
        </div>

        <div class="about-stats">
          <div class="stat-card">
            <div class="stat-number">20</div>
            <div class="stat-label">лет на рынке</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">3</div>
            <div class="stat-label">производства</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">35</div>
            <div class="stat-label">товарных категорий</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'About'
};
</script>

<style scoped>
.about-page {
  --color-border: #e0e0e0;
  --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
  --color-text-light: #666;
  --color-primary: #51ae45;
  --color-primary-dark: #2e7d32;
}

.about-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

.about-page {
  padding: 40px 0;
}

.about-header {
  text-align: center;
  margin-bottom: 40px;
}

.about-header h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 15px;
}

.about-lead {
  color: var(--color-text-light);
  font-size: 18px;
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.6;
}

.about-description {
  background: #fff;
  border: 1px solid var(--color-border);
  border-radius: 20px;
  padding: 30px 35px;
  margin-bottom: 40px;
  line-height: 1.7;
  color: #334155;
  font-size: 16px;
  box-shadow: var(--shadow-sm);
}

.about-description p {
  margin-bottom: 18px;
}

.products-list {
  list-style: none;
  padding-left: 0;
  margin: 20px 0;
}

.products-list li {
  margin-bottom: 12px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.products-list li i {
  color: var(--color-primary);
  font-size: 20px;
  margin-top: 3px;
  flex-shrink: 0;
}

.about-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 30px;
  margin-bottom: 50px;
}

.stat-card {
  background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
  border-radius: 24px;
  padding: 30px 20px;
  text-align: center;
  border: 1px solid var(--color-border);
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-md);
}

.stat-number {
  font-size: 48px;
  font-weight: 800;
  color: var(--color-primary);
  margin-bottom: 12px;
  line-height: 1;
}

.stat-label {
  font-size: 18px;
  font-weight: 600;
  color: #1e293b;
}

.about-video h2 {
  font-size: 28px;
  margin-bottom: 24px;
  text-align: center;
  color: #1e293b;
  position: relative;
  display: inline-block;
  left: 50%;
  transform: translateX(-50%);
}

.about-video h2::after {
  content: '';
  position: absolute;
  bottom: -12px;
  left: 25%;
  width: 50%;
  height: 3px;
  background: var(--color-primary);
  border-radius: 2px;
}

.video-wrapper {
  position: relative;
  padding-bottom: 56.25%; /* 16:9 */
  height: 0;
  overflow: hidden;
  border-radius: 20px;
  box-shadow: var(--shadow-md);
  background: #000;
}

.video-wrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}

@media (max-width: 768px) {
  .about-header h1 {
    font-size: 28px;
  }
  .about-lead {
    font-size: 16px;
  }
  .about-description {
    padding: 20px;
  }
  .stat-number {
    font-size: 36px;
  }
  .stat-label {
    font-size: 16px;
  }
  .about-video h2 {
    font-size: 24px;
  }
}
</style>