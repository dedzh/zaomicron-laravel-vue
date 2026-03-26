<template>
  <div class="main-products-grid">
    <div v-for="product in products" :key="product.id" class="main-product-item">
      <a :href="product.link">
        <img :src="product.image" :alt="product.name">
      </a>
      <a :href="product.link" class="product-name">{{ product.name }}</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductGrid',
  data() {
    return {
      products: [
        { id: 1, link: '/online/76259.html', image: '/img/main/1/micrometri.png', name: 'Микрометры' },
        { id: 2, link: '/online/76255.html', image: '/img/main/1/indicatori.png', name: 'Индикаторы' },
        { id: 3, link: '/online/76488.html', image: '/img/main/1/kalibri.png', name: 'Калибры промышленные' },
        { id: 4, link: '/online/76278.html', image: '/img/main/1/shtangencirkuli.png', name: 'Штангенинструмент' },
        { id: 5, link: '/online/76269.html', image: '/img/main/1/skobi.png', name: 'Скобы' },
        { id: 6, link: '/online/76273.html', image: '/img/main/1/ugolniki.png', name: 'Угольники' },
        { id: 7, link: '/online/76275.html', image: '/img/main/1/urovni.png', name: 'Уровни' },
        { id: 8, link: '/online/76542.html', image: '/img/main/1/kalibri1.png', name: 'Калибры нефтяные' },
        { id: 9, link: '/online/76265.html', image: '/img/main/1/nutromeri.png', name: 'Нутромеры' },
        { id: 10, link: '/online/76901.html', image: '/img/main/1/obrazci-sherohovatosti.png', name: 'Образцы шероховатости' },
        { id: 11, link: '/online/76257.html', image: '/img/main/1/lineiki.png', name: 'Линейки' },
        { id: 12, link: '/online/76585.html', image: '/img/main/1/uglomeri.png', name: 'Угломеры' },
        { id: 13, link: '/online/77331.html', image: '/img/main/1/stanki.png', name: 'Оснастка станочная' },
        { id: 14, link: '/online/76317.html', image: '/img/main/1/slesarnyi_instrument.png', name: 'Слесарный инструмент' },
        { id: 15, link: '/online/76299.html', image: '/img/main/1/rezci.png', name: 'Резцы' },
        { id: 16, link: '/online/129613.html', image: '/img/main/1/manometri.png', name: 'Манометры' },
        { id: 17, link: '/online/76508.html', image: '/img/main/1/meri_dliny.png', name: 'Меры длины' }
      ]
    };
  }
};
</script>

<style >
@import '@/style.css'; 
</style>