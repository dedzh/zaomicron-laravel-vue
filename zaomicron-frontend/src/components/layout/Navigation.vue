<template>
  <nav class="main-navigation">
    <div class="container">
      <ul class="nav-menu">
        <li class="nav-item">
          <router-link to="/about" class="nav-link" exact-active-class="active">О кампании</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/catalog" class="nav-link" exact-active-class="active">Каталог</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/service" class="nav-link" exact-active-class="active">Услуги</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/blog" class="nav-link" exact-active-class="active">Блог</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/partners" class="nav-link" exact-active-class="active">Партнерам</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/contacts" class="nav-link" exact-active-class="active">Контакты</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation'
};
</script>

<style scoped>
.main-navigation {
  background: #f8f9fa;
  border-top: 1px solid var(--color-border);
  border-bottom: 1px solid var(--color-border);
}

.nav-menu {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  gap: 30px;
  margin: 0;
  padding: 0;
}

.nav-item {
  position: relative;
}

.nav-link {
  display: block;
  padding: 15px 0;
  text-decoration: none;
  color: var(--color-text);
  font-family: var(--font-body);
  font-weight: 600;
  transition: color var(--transition);
  position: relative;
}

.nav-link:hover {
  color: var(--color-primary);
}

.nav-link.active {
  color: var(--color-primary);
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--color-primary);
  border-radius: 2px;
  animation: slideIn 0.3s ease;
}

@keyframes slideIn {
  from { transform: scaleX(0); }
  to { transform: scaleX(1); }
}

@media (max-width: 1024px) {
  .nav-menu { gap: 20px; }
  .nav-link { font-size: 14px; }
}
@media (max-width: 768px) {
  .main-navigation { overflow-x: auto; }
  .nav-menu { gap: 15px; row-gap: 5px; }
}
@media (max-width: 480px) {
  .nav-menu { gap: 12px; }
  .nav-link { font-size: 13px; padding: 12px 0; }
}
</style>