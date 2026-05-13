<template>
  <section class="hero-slider">
    <div class="slider-container">
      <div
        v-for="(slide, index) in activeSlides"
        :key="index"
        class="slide"
        :class="{ active: currentSlide === index }"
        :style="slide.style"
      >
        <div v-if="slide.video" class="slide-overlay" :style="slide.overlayStyle"></div>
        <div class="slide-content">
          <div class="slide-text">
            <div class="slide-line">{{ slide.line1 }}</div>
            <div class="slide-linemin">{{ slide.line2 }}</div>
          </div>
          <a :href="slide.link" class="slide-btn btn btn-primary">
            <i :class="['animated-icon', slide.iconClass]"></i> {{ slide.btnText }}
          </a>
        </div>
      </div>

      <button class="slider-nav prev" @click="prevSlide">←</button>
      <button class="slider-nav next" @click="nextSlide">→</button>

      <div class="slider-indicators">
        <button
          v-for="(_, index) in activeSlides"
          :key="index"
          class="indicator"
          :class="{ active: currentSlide === index }"
          @click="goToSlide(index)"
        ></button>
      </div>
    </div>
  </section>
</template>

<script>
import img1 from '@/assets/slider_1.jpg'

import img3 from '@/assets/slider_5.jpg'

export default {
  name: 'HeroSlider',
  props: {
    slides: { type: Array, default: () => [] }
  },
  data() {
    return {
      currentSlide: 0,
      internalSlides: [
        {
          style: { backgroundImage: `linear-gradient(to right, rgba(0, 8, 20, 0.9) 0%, rgba(0, 8, 20, 0.7) 30%, rgba(0, 8, 20, 0.4) 60%, rgba(0, 8, 20, 0) 100%), url(${img1})` },
          line1: 'ПОСТАВКА ВЫСОКОКАЧЕСТВЕННОГО ИНСТРУМЕНТА',
          line2: 'на промышленные мероприятия от производителя',
          btnText: 'Каталог',
          iconClass: 'fas fa-box',
          link: '/catalog'
        },
        /*{
          style: { backgroundImage: `linear-gradient(to right, rgba(0, 8, 20, 0.9) 0%, rgba(0, 8, 20, 0.7) 30%, rgba(0, 8, 20, 0.4) 60%, rgba(0, 8, 20, 0) 100%), url(${img2})` },
          line1: 'АО ТД «Завод «Микрон» присвоен статус «Успешный поставщик»',
          line2: '«Успешный поставщик»',
          /*btnText: '',
          iconClass: 'fas fa-cog',
          link: '/catalog
        },*/
        {
          style: { backgroundImage: `linear-gradient(to right, rgba(0, 8, 20, 0.9) 0%, rgba(0, 8, 20, 0.7) 30%, rgba(0, 8, 20, 0.4) 60%, rgba(0, 8, 20, 0) 100%), url(${img3})` },
          line1: '',
          line2: '',
          btnText: 'Подобрать',
          iconClass: 'fas fa-search',
          link: '/catalog'
        }
      ]
    }
  },
  computed: {
    activeSlides() {
      return this.slides.length ? this.slides : this.internalSlides
    }
  },
  methods: {
    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.activeSlides.length
    },
    prevSlide() {
      this.currentSlide = (this.currentSlide - 1 + this.activeSlides.length) % this.activeSlides.length
    },
    goToSlide(index) { this.currentSlide = index }
  },
  mounted() {
    this.interval = setInterval(this.nextSlide, 10000)
  },
  beforeUnmount() {
    clearInterval(this.interval)
  }
}
</script>

<style scoped>
.hero-slider {
  position: relative;
  width: 100%;
  height: 500px;
  overflow: hidden;
}
.slider-container {
  position: relative;
  width: 100%;
  height: 100%;
}
.slide {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.9s ease, visibility 0.9s, transform 1.5s ease;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  transform: scale(1.04);
}
.slide::before {
  content: "";
  position: absolute;
  inset: -20% -30%;
  background:
    linear-gradient(120deg, transparent 20%, rgba(255, 255, 255, 0.25) 46%, rgba(255, 255, 255, 0.07) 55%, transparent 76%);
  opacity: 0;
  transform: translateX(-120%) skewX(-12deg);
  pointer-events: none;
}
.slide.active {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}
.slide.active::before {
  opacity: 1;
  animation: metalShine 2.6s ease-in-out both;
}
.slide-content {
  position: relative;
  z-index: 5;
  max-width: 1240px;
  margin: 0 auto;
  padding: 0 20px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  color: white;
}
.slide-text { margin-bottom: 30px; }
.slide-line {
  font-family: var(--font-heading);
  font-size: 48px;
  font-weight: 900;
  line-height: 1.2;
  text-transform: uppercase;
  margin-bottom: 10px;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3), 0 0 18px rgba(236, 244, 255, 0.15);
}
.slide-linemin {
  font-family: var(--font-body);
  font-size: 24px;
  font-weight: 500;
  opacity: 0.9;
  text-transform: uppercase;
  letter-spacing: 2px;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3), 0 0 16px rgba(216, 228, 241, 0.18);
}
.slide-btn {
  font-size: 18px;
  padding: 15px 30px;
  border-radius: var(--radius-sm);
  width: fit-content;
}
.animated-icon { font-size: 24px; }
.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  background: rgba(0,0,0,0.5);
  color: white;
  border: none;
  width: 50px; height: 50px;
  border-radius: 50%;
  font-size: 24px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background var(--transition);
}
.slider-nav:hover { background: rgba(81, 174, 69, 0.8); }
.slider-nav.prev { left: 20px; }
.slider-nav.next { right: 20px; }
.slider-indicators {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10;
  display: flex;
  gap: 10px;
}
.indicator {
  width: 12px; height: 12px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  border: none;
  cursor: pointer;
  transition: background var(--transition);
}
.indicator.active { background: var(--color-primary); }

@keyframes metalShine {
  0% {
    transform: translateX(-120%) skewX(-12deg);
    opacity: 0;
  }
  18% {
    opacity: 0.35;
  }
  55% {
    opacity: 0.65;
  }
  100% {
    transform: translateX(120%) skewX(-12deg);
    opacity: 0;
  }
}

@media (max-width: 1024px) {
  .hero-slider { height: 400px; }
  .slide-line { font-size: 36px; }
  .slide-linemin { font-size: 18px; }
}
@media (max-width: 768px) {
  .hero-slider { height: 350px; }
  .slide-line { font-size: 28px; }
  .slide-linemin { font-size: 16px; }
  .slide-btn { padding: 12px 24px; font-size: 16px; }
  .slider-nav { width: 40px; height: 40px; font-size: 18px; }
}
@media (max-width: 480px) {
  .hero-slider { height: 300px; }
  .slide-line { font-size: 22px; }
  .slide-linemin { font-size: 14px; }
  .slide-btn { padding: 10px 20px; font-size: 14px; }
}
</style>