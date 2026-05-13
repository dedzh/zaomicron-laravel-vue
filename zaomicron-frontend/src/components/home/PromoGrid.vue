<template>
  <div class="promo-grid-wrapper">
    <h2 class="section-title">Популярные категории</h2>
    <div class="promo-grid">
      <router-link
        v-for="item in promoItems"
        :key="item.id"
        :to="item.link"
        class="promo-grid-item"
      >
        <div class="grid-item-image">
          <img :src="item.image" :alt="item.title" loading="lazy" />
        </div>
        <div class="grid-item-content">
          <h3>{{ item.title }}</h3>
          <p>{{ item.description }}</p>
          <span class="grid-item-link">Смотреть каталог →</span>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script>
// Импорт локальных изображений
import izmerInstr from '@/assets/izmer_instr.jpg'
import kalibr from '@/assets/kalibr.jpg'
import pribor from '@/assets/pribor.jpg'
import freza from '@/assets/freza.jpg'


export default {
  name: 'PromoGrid',
  data() {
    return {
      promoItems: [
        {
          id: 1,
          title: 'Измерительный инструмент',
          description: 'Штангенциркули, микрометры, нутромеры, глубиномеры, угломеры, меры длины',
          link: '/catalog/296',
          image: izmerInstr
        },
        {
          id: 2,
          title: 'Наборы',
          description: 'Наборы инструментов',
          link: '/catalog/766',
          image: kalibr
        },
        {
          id: 3,
          title: 'Приборы',
          description: 'Биениемеры, квадранты, микроскопы, оптические линейки',
          link: '/catalog/10',
          image: pribor
        },
        {
          id: 4,
          title: 'Металлорежущий инструмент',
          description: 'Фрезы, сверла, резцы, плашки, клуппы, метчики',
          link: '/catalog/212',
          image: freza
        }
      ]
    };
  }
};
</script>

<style scoped>
.promo-grid-wrapper {
  margin: 40px 0;
}
.section-title {
  font-family: 'Mulish', sans-serif;
  font-size: 28px;
  font-weight: 800;
  color: #1a1a1a;
  text-align: center;
  margin-bottom: 30px;
}
.promo-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 25px;
}
.promo-grid-item {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  border: 1px solid #eef2ee;
  overflow: hidden;
  text-decoration: none;
  color: inherit;
  transition: transform 0.25s, box-shadow 0.25s;
  display: flex;
  flex-direction: column;
}
.promo-grid-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}
.grid-item-image {
  height: 180px;
  background: #f8fcf8;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.grid-item-image img {
  max-width: 100%;
  max-height: 140px;
  object-fit: contain;
  transition: transform 0.4s ease;
}
.promo-grid-item:hover .grid-item-image img {
  transform: scale(1.06);
}
.grid-item-content {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
}
.grid-item-content h3 {
  font-family: 'Mulish', sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 10px;
}
.grid-item-content p {
  color: #4a4a4a;
  font-family: 'Arimo', sans-serif;
  font-size: 14px;
  line-height: 1.5;
  margin: 0 0 15px;
  flex: 1;
}
.grid-item-link {
  color: #51ae45;
  font-weight: 600;
  font-size: 14px;
  transition: letter-spacing 0.2s ease;
}
.promo-grid-item:hover .grid-item-link {
  letter-spacing: 0.3px;
}

@media (max-width: 1024px) {
  .promo-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
@media (max-width: 600px) {
  .promo-grid {
    grid-template-columns: 1fr;
  }
}
</style>