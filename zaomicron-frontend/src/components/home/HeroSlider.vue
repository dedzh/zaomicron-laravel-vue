<template>
  <section class="hero-slider">
    <div class="slider-container">
      <div
        v-for="(slide, index) in slides"
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
          <a :href="slide.link" class="slide-btn">
            <i :class="['animated-icon', slide.iconClass]"></i> {{ slide.btnText }}
          </a>
        </div>
      </div>

      <button class="slider-nav prev" @click="prevSlide">←</button>
      <button class="slider-nav next" @click="nextSlide">→</button>

      <div class="slider-indicators">
        <button
          v-for="(_, index) in slides"
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
import img2 from '@/assets/slider_2.jpg'
import img3 from '@/assets/slider_5.jpg'

export default {
  name: 'HeroSlider',
  data() {
    return {
      currentSlide: 0,
      slides: [
        {
          style: {
            backgroundImage: `linear-gradient(to right, rgba(0, 8, 20, 0.9) 0%, rgba(0, 8, 20, 0.7) 30%, rgba(0, 8, 20, 0.4) 60%, rgba(0, 8, 20, 0) 100%), url(${img1})`
          },
          line1: 'ПОСТАВКА ВЫСОКОКАЧЕСТВЕННОГО ИНСТРУМЕНТА',
          line2: 'на промышленные мероприятия от производителя',
          btnText: 'Каталог',
          iconClass: 'fas fa-box',
          link: '/catalog'
        },
        {
          style: {
            backgroundImage: `linear-gradient(to right, rgba(0, 8, 20, 0.9) 0%, rgba(0, 8, 20, 0.7) 30%, rgba(0, 8, 20, 0.4) 60%, rgba(0, 8, 20, 0) 100%), url(${img2})`
          },
          line1: 'ПОСТАВЩИК ЭЛЕКТРОННЫХ',
          line2: 'компонентов в России',
          btnText: 'Найти компоненты',
          iconClass: 'fas fa-cog',
          link: '/catalog'
        },
        {
          style: {
            backgroundImage: `linear-gradient(to right, rgba(0, 8, 20, 0.9) 0%, rgba(0, 8, 20, 0.7) 30%, rgba(0, 8, 20, 0.4) 60%, rgba(0, 8, 20, 0) 100%), url(${img3})`
          },
          line1: 'БОЛЕЕ 3.5 МЛН',
          line2: 'позиций в каталоге',
          btnText: 'Подобрать',
          iconClass: 'fas fa-search',
          link: '/catalog'
        }
      ]
    }
  },
  methods: {
    nextSlide() {
      this.currentSlide = (this.currentSlide + 1) % this.slides.length
    },
    prevSlide() {
      this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length
    },
    goToSlide(index) {
      this.currentSlide = index
    }
  },
  mounted() {
    this.interval = setInterval(this.nextSlide, 5000)
  },
  beforeUnmount() {
    clearInterval(this.interval)
  }
}
</script>

<style scoped>
/* стили оставьте без изменений, они уже правильные */
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
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.8s ease, visibility 0.8s;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.slide.active {
  opacity: 1;
  visibility: visible;
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

.slide-text {
  margin-bottom: 30px;
}

.slide-line {
  font-size: 48px;
  font-weight: 900;
  line-height: 1.2;
  text-transform: uppercase;
  margin-bottom: 10px;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.slide-linemin {
  font-size: 24px;
  font-weight: 500;
  opacity: 0.9;
  text-transform: uppercase;
  letter-spacing: 2px;
  text-shadow: 0 2px 5px rgba(0,0,0,0.3);
}

.slide-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: #51AE45;
  color: white;
  padding: 15px 30px;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 700;
  font-size: 18px;
  width: fit-content;
  transition: background 0.3s;
  border: none;
  cursor: pointer;
}

.slide-btn:hover {
  background: #3e8c34;
}

.animated-icon {
  font-size: 24px;
}

.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  background: rgba(0,0,0,0.5);
  color: white;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 24px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.3s;
}

.slider-nav:hover {
  background: rgba(81, 174, 69, 0.8);
}

.slider-nav.prev {
  left: 20px;
}

.slider-nav.next {
  right: 20px;
}

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
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  border: none;
  cursor: pointer;
  transition: background 0.3s;
}

.indicator.active {
  background: #51AE45;
}

@media (max-width: 1024px) {
  .hero-slider {
    height: 400px;
  }
  .slide-line {
    font-size: 36px;
  }
  .slide-linemin {
    font-size: 18px;
  }
}

@media (max-width: 768px) {
  .hero-slider {
    height: 350px;
  }
  .slide-line {
    font-size: 28px;
  }
  .slide-linemin {
    font-size: 16px;
  }
  .slide-btn {
    padding: 12px 24px;
    font-size: 16px;
  }
  .slider-nav {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }
}

@media (max-width: 480px) {
  .hero-slider {
    height: 300px;
  }
  .slide-line {
    font-size: 22px;
  }
  .slide-linemin {
    font-size: 14px;
  }
  .slide-btn {
    padding: 10px 20px;
    font-size: 14px;
  }
}
</style>