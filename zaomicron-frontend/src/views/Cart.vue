<template>
  <div class="cart-page container">
    <h1>Корзина</h1>
    <div v-if="cartItems.length === 0" class="empty-cart">
      <p>Ваша корзина пуста</p>
      <router-link to="/catalog" class="btn btn-primary">Перейти в каталог</router-link>
    </div>
    <div v-else>
      <div class="cart-items">
        <div v-for="item in cartItems" :key="item.id" class="cart-item">
          <img :src="item.image || defaultImage" :alt="item.name" class="cart-item-image">
          <div class="cart-item-info">
            <h3>{{ item.name }}</h3>
            <div class="cart-item-sku">Арт. {{ item.sku }}</div>
            <div class="cart-item-price">{{ item.price }} ₽</div>
          </div>
          <div class="cart-item-quantity">
            <button @click="updateQuantity(item.id, item.quantity - 1)" :disabled="item.quantity <= 1">-</button>
            <span>{{ item.quantity }}</span>
            <button @click="updateQuantity(item.id, item.quantity + 1)">+</button>
          </div>
          <div class="cart-item-total">{{ (item.price * item.quantity).toFixed(2) }} ₽</div>
          <button class="btn btn-danger" @click="removeItem(item.id)">Удалить</button>
        </div>
      </div>
      <div class="cart-summary">
        <div class="cart-total">Итого: {{ totalPrice.toFixed(2) }} ₽</div>
        <button class="btn btn-primary" @click="proceedToCheckout">Оформить заказ</button>
      </div>
    </div>

    <!-- Модальное окно для оформления заказа -->
    <div v-if="showCheckoutModal" class="modal-overlay" @click.self="showCheckoutModal = false">
      <div class="modal">
        <h2>Оформление заказа</h2>
        <form @submit.prevent="submitOrder">
          <div class="form-group">
            <label>Имя *</label>
            <input type="text" v-model="orderForm.name" required>
          </div>
          <div class="form-group">
            <label>Телефон *</label>
            <input type="tel" v-model="orderForm.phone" required>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" v-model="orderForm.email" required>
          </div>
          <div class="form-group">
            <label>Комментарий</label>
            <textarea v-model="orderForm.comment"></textarea>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" @click="showCheckoutModal = false">Отмена</button>
            <button type="submit" class="btn btn-primary" :disabled="submitting">Отправить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { useCartStore } from '@/stores/cart';
import { mapState, mapActions } from 'pinia';
import axios from 'axios';

export default {
  name: 'Cart',
  data() {
    return {
      defaultImage: '/img/placeholder.jpg',
      showCheckoutModal: false,
      orderForm: {
        name: '',
        phone: '',
        email: '',
        comment: ''
      },
      submitting: false
    };
  },
  computed: {
    ...mapState(useCartStore, ['items', 'totalPrice']),
    cartItems() {
      return this.items;
    }
  },
  methods: {
    ...mapActions(useCartStore, ['removeFromCart', 'updateQuantity']),
    proceedToCheckout() {
      this.showCheckoutModal = true;
    },
    async submitOrder() {
      this.submitting = true;
      try {
        const orderData = {
          ...this.orderForm,
          items: this.cartItems.map(item => ({
            id: item.id,
            name: item.name,
            sku: item.sku,
            price: item.price,
            quantity: item.quantity
          })),
          total: this.totalPrice
        };
        await axios.post('/api/order', orderData);
        alert('Заказ отправлен! Мы свяжемся с вами в ближайшее время.');
        this.showCheckoutModal = false;
        this.orderForm = { name: '', phone: '', email: '', comment: '' };
        // Очищаем корзину
        const cartStore = useCartStore();
        cartStore.clearCart();
      } catch (err) {
        console.error(err);
        alert('Ошибка отправки заказа. Попробуйте позже.');
      } finally {
        this.submitting = false;
      }
    }
  }
};
</script>

<style scoped>
.cart-page {
  padding: 20px 0;
}
.empty-cart {
  text-align: center;
  padding: 50px;
}
.cart-items {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 30px;
}
.cart-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 15px;
  border: 1px solid var(--color-border);
  border-radius: var(--border-radius);
  flex-wrap: wrap;
}
.cart-item-image {
  width: 80px;
  height: 80px;
  object-fit: contain;
}
.cart-item-info {
  flex: 2;
  min-width: 150px;
}
.cart-item-info h3 {
  font-size: 16px;
  margin-bottom: 5px;
}
.cart-item-sku {
  color: #999;
  font-size: 12px;
}
.cart-item-price {
  font-weight: 600;
  color: var(--color-primary);
}
.cart-item-quantity {
  display: flex;
  align-items: center;
  gap: 10px;
}
.cart-item-quantity button {
  width: 30px;
  height: 30px;
  border: 1px solid var(--color-border);
  background: white;
  cursor: pointer;
}
.cart-item-total {
  font-weight: 700;
  min-width: 100px;
  text-align: right;
}
.cart-summary {
  text-align: right;
  border-top: 1px solid var(--color-border);
  padding-top: 20px;
}
.cart-total {
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 15px;
}

/* Модальное окно */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}
.modal {
  background: white;
  padding: 30px;
  border-radius: var(--border-radius);
  max-width: 500px;
  width: 90%;
}
.form-group {
  margin-bottom: 15px;
}
.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
}
.form-group input, .form-group textarea {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid var(--color-border);
  border-radius: 4px;
}
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
}
</style>