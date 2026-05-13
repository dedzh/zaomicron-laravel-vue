<template>
  <div class="header-info-bar">
    <div class="container">
      <div class="header-info-content">
        <ul class="header-info-links">
          <li><a href="/certificate">Сертификаты</a></li>
          <li><a href="/contacts">Контакты</a></li>
          <li><a href="/partners">Партнерам</a></li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InfoBar'
};
</script>

<style scoped>
/* Металлический фон как в Footer */
.header-info-bar {
  position: relative;
  background: linear-gradient(145deg, #2a313a 0%, #59616b 40%, #232a33 72%, #3a434d 100%);
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);
  padding: 8px 0;
  font-family: var(--font-body);
  font-size: 14px;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
  overflow: hidden;
}

/* Блик */
.header-info-bar::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(110deg, transparent 15%, rgba(255, 255, 255, 0.12) 48%, transparent 82%);
  mix-blend-mode: screen;
  opacity: 0.5;
  pointer-events: none;
}

.header-info-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  z-index: 1;
}

.header-info-links {
  display: flex;
  list-style: none;
  gap: 20px;
  margin: 0;
  padding: 0;
}

.header-info-links a {
  color: rgba(232, 237, 242, 0.9);
  text-decoration: none;
  transition: color 0.2s;
}

.header-info-links a:hover {
  color: #35bc1d; 
}
</style>