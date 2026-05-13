<template>
  <div class="cart">
    <h1 class="cart__title">Корзина</h1>

    <div v-if="cartItems.length === 0" class="cart__empty">
      <p>Ваша корзина пуста</p>
      <router-link to="/catalog" class="btn btn-primary">Перейти в каталог</router-link>
    </div>

    <template v-else>
      <ul class="cart__list">
        <li v-for="item in cartItems" :key="item.id" class="cart-item">
          <img :src="getImageUrl(item)" :alt="item.name" class="cart-item__img" />
          <div class="cart-item__info">
            <h3 class="cart-item__name">{{ item.name }}</h3>
            <div v-if="item.sku" class="cart-item__sku">Арт. {{ item.sku }}</div>
            <div class="cart-item__price">{{ unitPriceLabel(item) }}</div>
          </div>

          <div class="cart-item__qty">
            <button class="qty-btn" @click="changeQty(item.id, item.quantity - 1)">−</button>
            <span class="qty-value">{{ item.quantity }}</span>
            <button class="qty-btn" @click="changeQty(item.id, item.quantity + 1)">+</button>
          </div>

          <div class="cart-item__total">{{ lineTotalLabel(item) }}</div>
          <button class="cart-item__remove" @click="removeItem(item.id)">
            <i class="fas fa-trash-alt" />
          </button>
        </li>
      </ul>

      <div class="cart__summary">
        <span class="cart__summary-label">Итого:</span>
        <span class="cart__summary-price">{{ summaryTotalLabel }}</span>
        <button class="btn btn-primary btn-lg" @click="proceedToCheckout">Оформить заказ</button>
      </div>
    </template>

    <!-- Диалог оформления -->
    <v-dialog v-model="showCheckout" max-width="480">
      <v-card>
        <v-card-title class="text-h5 font-weight-bold">Оформление заказа</v-card-title>
        <v-card-text>
          <v-form ref="orderFormRef" v-model="formValid" @submit.prevent="submitOrder">
            <v-text-field
              v-model="orderForm.name"
              label="Имя *"
              :rules="[v => !!v || 'Обязательное поле']"
              required
            />
            <v-text-field
              v-model="orderForm.phone"
              label="Телефон *"
              :rules="[v => !!v || 'Обязательное поле']"
              required
            />
            <v-text-field
              v-model="orderForm.email"
              label="Email *"
              type="email"
              :rules="[v => !!v || 'Обязательное поле', v => /.+@.+\..+/.test(v) || 'Некорректный email']"
              required
            />
            <v-textarea
              v-model="orderForm.comment"
              label="Комментарий"
              rows="3"
            />
          </v-form>
        </v-card-text>
        <v-card-actions class="pa-4">
          <v-spacer />
          <v-btn variant="text" @click="showCheckout = false">Отмена</v-btn>
          <v-btn
            color="primary"
            :loading="submitting"
            :disabled="!formValid"
            @click="submitOrder"
          >
            Отправить
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import axios from 'axios'
import defaultImage from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'

const cartStore = useCartStore()
cartStore.loadFromLocalStorage()

const showCheckout = ref(false)
const submitting = ref(false)
const formValid = ref(false)
const orderForm = ref({ name: '', phone: '', email: '', comment: '' })
const orderFormRef = ref(null)

const cartItems = computed(() => cartStore.items)
const totalPrice = computed(() => cartStore.totalPrice)

const nf = new Intl.NumberFormat('ru-RU')

function unitPriceLabel(item) {
  const n = Number(item.price)
  if (Number.isFinite(n) && n === 0) return 'по запросу'
  return `${nf.format(n)} ₽ / шт.`
}

function lineTotalLabel(item) {
  const n = Number(item.price)
  const q = Number(item.quantity || 1)
  if (Number.isFinite(n) && n === 0) return 'по запросу'
  if (!Number.isFinite(n) || !Number.isFinite(q)) return '—'
  return `${nf.format(n * q)} ₽`
}

const summaryTotalLabel = computed(() => {
  const t = Number(totalPrice.value)
  if (Number.isFinite(t) && t === 0) return 'по запросу'
  if (!Number.isFinite(t)) return '—'
  return `${nf.format(t)} ₽`
})
const changeQty = (id, next) => cartStore.updateQuantity(id, Math.max(1, next))
const removeItem = (id) => cartStore.removeFromCart(id)

const proceedToCheckout = () => {
  showCheckout.value = true
}
const getImageUrl = (item) => {
  return resolveMediaUrl(item.image || item.image_url, defaultImage)
}

const submitOrder = async () => {
  const valid = await orderFormRef.value?.validate()
  if (!valid) return
  submitting.value = true
  try {
    const orderData = {
      ...orderForm.value,
      items: cartItems.value.map(item => ({
        id: item.id,
        name: item.name,
        sku: item.sku,
        price: item.price,
        quantity: item.quantity
      })),
      total: totalPrice.value
    }
    await axios.post('/api/order', orderData)
    alert('Заказ отправлен!')
    showCheckout.value = false
    orderForm.value = { name: '', phone: '', email: '', comment: '' }
    cartStore.clearCart()
  } catch (e) {
    console.error(e)
    alert('Ошибка отправки заказа')
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.cart {
  max-width: 960px;
  margin: 32px auto;
  padding: 0 16px;
}

.cart__title {
  font-family: 'Mulish', sans-serif;
  font-size: 36px;
  font-weight: 800;
  margin-bottom: 32px;
  color: #111;
}

.cart__empty {
  text-align: center;
  padding: 60px;
  color: #666;
}

.cart__list {
  list-style: none;
  padding: 0;
}

.cart-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
  border: 1px solid #eef2ee;
  border-radius: 14px;
  background: #fff;
  margin-bottom: 16px;
  transition: box-shadow 0.2s;
}
.cart-item:hover {
  box-shadow: 0 6px 20px rgba(0,0,0,0.05);
}

.cart-item__img {
  width: 90px;
  height: 90px;
  object-fit: contain;
  background: #f8fcf8;
  border-radius: 10px;
  padding: 8px;
}

.cart-item__info {
  flex: 1;
}

.cart-item__name {
  font-family: 'Mulish', sans-serif;
  font-weight: 700;
  font-size: 17px;
  margin: 0 0 4px;
}

.cart-item__sku {
  font-size: 13px;
  color: #888;
  margin-bottom: 6px;
}

.cart-item__price {
  font-weight: 700;
  color: #156135;
}

.cart-item__qty {
  display: flex;
  align-items: center;
  gap: 0;
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
}

.qty-btn {
  width: 36px;
  height: 36px;
  border: none;
  background: #f5f5f5;
  font-size: 18px;
  cursor: pointer;
  color: #333;
}
.qty-btn:hover {
  background: #e0e0e0;
}

.qty-value {
  width: 44px;
  text-align: center;
  font-weight: 700;
}

.cart-item__total {
  font-weight: 700;
  font-size: 18px;
  min-width: 90px;
  text-align: right;
}

.cart-item__remove {
  background: none;
  border: none;
  color: #aaa;
  font-size: 18px;
  cursor: pointer;
  transition: color 0.2s;
}
.cart-item__remove:hover {
  color: #e53935;
}

.cart__summary {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 24px;
  padding: 24px 0;
  border-top: 2px solid #eee;
  margin-top: 24px;
}

.cart__summary-label {
  font-size: 22px;
  font-weight: 700;
}

.cart__summary-price {
  font-size: 28px;
  font-weight: 800;
  color: #156135;
}

@media (max-width: 768px) {
  .cart-item {
    flex-wrap: wrap;
  }
  .cart-item__img {
    width: 70px;
    height: 70px;
  }
  .cart__summary {
    flex-direction: column;
    align-items: flex-end;
  }
}
</style>