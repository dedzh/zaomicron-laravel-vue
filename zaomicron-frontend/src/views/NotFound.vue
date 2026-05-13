<template>
  <div class="not-found">
    <h1 class="not-found__code">404</h1>
    <p class="not-found__text">Страница не найдена</p>
    <router-link to="/" class="btn btn-primary">На главную</router-link>
  </div>
</template>

<script setup>
</script>

<style scoped>
.not-found {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 60vh;
  text-align: center;
  padding: 32px;
}

.not-found__code {
  font-family: 'Mulish', sans-serif;
  font-size: 120px;
  font-weight: 800;
  color: #51ae45;
  line-height: 1;
  margin-bottom: 16px;
}

.not-found__text {
  font-family: 'Arimo', sans-serif;
  font-size: 20px;
  color: #555;
  margin-bottom: 40px;
}

.btn {
  display: inline-flex;
  align-items: center;
  padding: 14px 32px;
  background: #51ae45;
  color: #fff;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 700;
  transition: background 0.2s;
}
.btn:hover {
  background: #156135;
}
</style>