<template>
  <div class="cart-toast-container">
    <transition-group name="toast" tag="div">
      <div
        v-for="notif in notifications"
        :key="notif.id"
        class="cart-toast"
      >
        <i class="fas fa-check-circle toast-icon" />
        {{ notif.message }}
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCartStore } from '@/stores/cart';

const store = useCartStore();
const notifications = computed(() => store.notifications);
</script>

<style scoped>
.cart-toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 10000;
  display: flex;
  flex-direction: column;
  gap: 8px;
  pointer-events: none;
}

.cart-toast {
  background: #51ae45;
  color: white;
  padding: 12px 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  font-family: 'Arimo', sans-serif;
  font-weight: 600;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
  pointer-events: auto;
  max-width: 320px;
  word-break: break-word;
}

.toast-icon { font-size: 18px; }
.toast-enter-active, .toast-leave-active { transition: all 0.3s ease; }
.toast-enter-from, .toast-leave-to { opacity: 0; transform: translateX(40px); }
</style>