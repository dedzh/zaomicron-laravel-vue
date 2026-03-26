<template>
  <div>
    <h1>Корзина</h1>
    <div class="cart-layout">
      <div class="cart-items">
        <div v-for="item in cartItems" :key="item.id" class="cart-item">
          <div class="cart-item-image">{{ item.emoji }}</div>
          <div class="cart-item-info">
            <h3>{{ item.name }}</h3>
            <div style="color: #666; margin: 5px 0;">Арт. {{ item.sku }}</div>
            <div style="font-weight: 700; color: #51AE45;">{{ item.price }} ₽</div>
          </div>
          <div class="cart-item-quantity">
            <div class="quantity-control">
              <button class="quantity-btn" @click="updateQuantity(item, -1)">-</button>
              <input type="number" v-model.number="item.quantity" class="quantity-input" min="1">
              <button class="quantity-btn" @click="updateQuantity(item, 1)">+</button>
            </div>
            <button class="btn btn-secondary" style="margin-top: 10px;" @click="removeItem(item)">Удалить</button>
          </div>
        </div>
      </div>

      <div class="cart-summary">
        <h2 style="margin-bottom: 20px;">Итого</h2>
        <div class="summary-row">
          <span>Товары ({{ totalItems }} шт.)</span>
          <span>{{ subtotal }} ₽</span>
        </div>
        <div class="summary-row">
          <span>Доставка</span>
          <span>1 500 ₽</span>
        </div>
        <div class="summary-row" style="font-size: 18px; font-weight: 700; color: #51AE45;">
          <span>К оплате</span>
          <span>{{ total }} ₽</span>
        </div>
        <button class="btn btn-primary" style="width: 100%; margin-top: 20px; padding: 15px;">Перейти к оформлению</button>
        <router-link to="/catalog" class="btn btn-secondary" style="width: 100%; margin-top: 10px; text-align: center; display: block;">Продолжить покупки</router-link>
        <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px;">
          <h4>Способы оплаты</h4>
          <div style="display: flex; gap: 10px; margin-top: 10px;">
            <span>💳</span><span>💴</span><span>🏦</span><span>📱</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Cart',
  data() {
    return {
      cartItems: [
        { id: 1, name: 'Цифровой манометр DM-100', sku: 'DM-100-2024', price: 15800, quantity: 1, emoji: '📊' },
        { id: 2, name: 'Термометр ТП-202', sku: 'ТП-202-PRO', price: 8900, quantity: 2, emoji: '🌡️' },
        { id: 3, name: 'Расходомер RF-400', sku: 'RF-400-ULT', price: 45200, quantity: 1, emoji: '💧' }
      ]
    };
  },
  computed: {
    totalItems() {
      return this.cartItems.reduce((sum, item) => sum + item.quantity, 0);
    },
    subtotal() {
      return this.cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
    },
    total() {
      return this.subtotal + 1500;
    }
  },
  methods: {
    updateQuantity(item, delta) {
      item.quantity = Math.max(1, item.quantity + delta);
    },
    removeItem(item) {
      const index = this.cartItems.indexOf(item);
      if (index > -1) this.cartItems.splice(index, 1);
    }
  }
};
</script>