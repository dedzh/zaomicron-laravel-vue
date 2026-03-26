<template>
  <div class="marquee-container">
    <div class="marquee-content">
      <a
        v-for="(item, index) in newsItems"
        :key="index"
        :href="item.link"
      >{{ item.text }}</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Marquee',
  data() {
    return {
      newsItems: [
        { link: '/news/24.html', text: 'АО ТД «Завод «Микрон» присвоен статус «Успешный поставщик»' },
        { link: '/news/20.html', text: 'АО ТД «Завод «Микрон» - лауреат конкурса «Активный поставщик»' },
        { link: '/news/23.html', text: 'Специализированная выставка. «Станкостроение. Обработка металлов»' },
        { link: '/news/22.html', text: 'Микрометры электронные МКЦ PRO' }
      ]
    };
  }
};
</script>

<style scoped>

</style>