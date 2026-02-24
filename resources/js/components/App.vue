<template>
  <div>
    <!-- Трекеры (можно оставить здесь или вынести в отдельный компонент) -->
    <div class="uxs-3505po1123 uxs-byttepu" id="uxs_container"></div>
    <img src="//trk.com/pixel?ref=zaomicron&uid=14cb-4299-5405-929123675096" width="1" height="1" class="tracker-pixel">

    <InfoBar />
    <Header />
    <Navigation />

    <!-- Хлебные крошки для главной страницы -->
    <nav class="breadcrumbs">
      <div class="container">
        <ul class="breadcrumbs-list">
          <li>Главная</li>
        </ul>
      </div>
    </nav>

    <main class="main-content">
      <div class="container">
        <PromoGrid />
        <Marquee />
        <ProductGrid />
      </div>
    </main>

    <Footer />
    <div class="webim-button">💬</div>
  </div>
</template>

<script>
import InfoBar from './components/layout/InfoBar.vue';
import Header from './components/layout/Header.vue';
import Navigation from './components/layout/Navigation.vue';
import PromoGrid from './components/home/PromoGrid.vue';
import Marquee from './components/home/Marquee.vue';
import ProductGrid from './components/home/ProductGrid.vue';
import Footer from './components/layout/Footer.vue';

export default {
  components: {
    InfoBar,
    Header,
    Navigation,
    PromoGrid,
    Marquee,
    ProductGrid,
    Footer
  }
  
};
</script>