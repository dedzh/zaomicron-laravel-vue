<template>
  <transition name="cookie-fade">
    <div v-if="visible" class="cookie-consent">
      <div class="cookie-consent__content">
        <i class="cookie-consent__icon fas fa-cookie-bite" aria-hidden="true"></i>
        <p class="cookie-consent__text">
          Сайт использует файлы cookies и передаёт данные службам веб-аналитики.
          <router-link to="/privacy" class="cookie-consent__link">Подробнее</router-link>
        </p>
        <div class="cookie-consent__actions">
          <button class="cookie-consent__btn btn btn-primary" @click="accept">
            Принять
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'CookieConsent',
  data() {
    return {
      visible: false
    };
  },
  mounted() {
    // Проверяем, было ли уже дано согласие
    const consent = localStorage.getItem('cookie_consent');
    if (!consent) {
      // Показываем плашку с небольшой задержкой для плавного появления
      setTimeout(() => {
        this.visible = true;
      }, 500);
    }
  },
  methods: {
    accept() {
      localStorage.setItem('cookie_consent', 'true');
      this.visible = false;
    }
  }
};
</script>

<style scoped>
.cookie-consent {
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  z-index: 10000;
  width: 90%;
  max-width: 720px;
  background: #ffffff;
  border: 1px solid var(--color-border, #e5e7eb);
  border-radius: var(--radius-lg, 16px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
  padding: 20px 24px;
  font-family: var(--font-body, 'Arimo', sans-serif);
}

.cookie-consent__content {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.cookie-consent__icon {
  font-size: 28px;
  color: #d4a853;
  flex-shrink: 0;
}

.cookie-consent__text {
  flex: 1;
  min-width: 200px;
  font-size: 15px;
  line-height: 1.5;
  color: var(--color-text, #1a1a1a);
  margin: 0;
}

.cookie-consent__link {
  color: var(--color-primary, #51ae45);
  text-decoration: underline;
  margin-left: 4px;
  white-space: nowrap;
}

.cookie-consent__link:hover {
  color: var(--color-primary-dark, #156135);
}

.cookie-consent__actions {
  flex-shrink: 0;
  display: flex;
  gap: 12px;
  align-items: center;
}

.cookie-consent__btn {
  padding: 10px 26px;
  font-size: 15px;
  border-radius: var(--radius-sm, 8px);
  background: var(--color-primary, #51ae45);
  color: white;
  border: none;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
  font-weight: 600;
  font-family: var(--font-body, 'Arimo', sans-serif);
}

.cookie-consent__btn:hover {
  background: var(--color-primary-dark, #156135);
  transform: scale(1.02);
}

/* Анимация */
.cookie-fade-enter-active {
  animation: cookie-in 0.4s ease-out;
}

.cookie-fade-leave-active {
  animation: cookie-out 0.3s ease-in forwards;
}

@keyframes cookie-in {
  from {
    opacity: 0;
    transform: translateX(-50%) translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
}

@keyframes cookie-out {
  from {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
  to {
    opacity: 0;
    transform: translateX(-50%) translateY(30px);
  }
}

@media (max-width: 600px) {
  .cookie-consent__content {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  .cookie-consent__actions {
    align-self: flex-end;
  }
}
</style>