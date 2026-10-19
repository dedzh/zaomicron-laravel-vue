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
          <router-link :to="'/product/' + item.id" class="cart-item__img-link">
            <img
              :src="getImageUrl(item)"
              :alt="item.name"
              class="cart-item__img"
              @error="handleImageError"
            />
          </router-link>

          <div class="cart-item__info">
            <router-link :to="'/product/' + item.id" class="cart-item__name-link">
              <h3 class="cart-item__name">{{ item.name }}</h3>
            </router-link>
            <div v-if="item.sku" class="cart-item__sku">Арт. {{ item.sku }}</div>
            <div class="cart-item__price">{{ unitPriceLabel(item) }}</div>
          </div>

          <div class="cart-item__qty">
            <button class="qty-btn" @click="changeQty(item.id, item.quantity - 1)">−</button>
            <input
              type="number"
              class="qty-input"
              :value="item.quantity"
              min="1"
              :max="getMaxStock(item)"
              @change="updateQtyFromInput(item.id, $event)"
              @blur="updateQtyFromInput(item.id, $event)"
            />
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

    <!-- Диалог оформления  -->
    <v-dialog v-model="showCheckout" max-width="520">
      <v-card class="checkout-card">
        <v-card-title class="checkout-title">Оформление заказа</v-card-title>
        <v-card-text>
          <form @submit.prevent="submitOrder" class="checkout-form">
            <div class="form-group">
              <label for="order-name" class="form-label">Имя *</label>
              <input
                id="order-name"
                v-model="orderForm.name"
                type="text"
                class="form-control"
                required
                :disabled="submitting"
              />
            </div>
            <div class="form-group">
              <label for="order-phone" class="form-label">Телефон *</label>
              <input
                id="order-phone"
                v-model="orderForm.phone"
                type="tel"
                class="form-control"
                required
                :disabled="submitting"
              />
            </div>
            <div class="form-group">
              <label for="order-email" class="form-label">Email *</label>
              <input
                id="order-email"
                v-model="orderForm.email"
                type="email"
                class="form-control"
                required
                :disabled="submitting"
              />
            </div>
            <div class="form-group">
              <label for="order-comment" class="form-label">Комментарий</label>
              <textarea
                id="order-comment"
                v-model="orderForm.comment"
                class="form-control"
                rows="3"
                :disabled="submitting"
              ></textarea>
            </div>

            <label class="privacy-check">
              <input
                v-model="orderForm.consent"
                type="checkbox"
                class="privacy-checkbox"
                :disabled="submitting"
              />
              <span>
                Я согласен на
                <router-link to="/privacy" target="_blank" @click.stop>обработку персональных данных</router-link>
              </span>
            </label>

            <button
              type="submit"
              class="btn btn-primary submit-btn"
              :disabled="submitting || !orderForm.consent"
            >
              <span v-if="submitting" class="spinner"></span>
              <span v-else>Отправить</span>
            </button>
            <div v-if="formMessage" class="form-message" :class="formMessageType">
              {{ formMessage }}
            </div>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import axios from 'axios'
import defaultImage from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'

const cartStore = useCartStore()
cartStore.loadFromLocalStorage()

const showCheckout = ref(false)
const submitting = ref(false)
const formMessage = ref('')
const formMessageType = ref('success')

const orderForm = reactive({
  name: '',
  phone: '',
  email: '',
  comment: '',
  consent: false
})

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

const getImageUrl = (item) => {
  return resolveMediaUrl(item.image || item.image_url || item.image_path, defaultImage)
}

function getMaxStock(item) {
  const max = Number(item.stock)
  if (!isNaN(max) && max > 0 && max < 9999) return max
  return 9999
}

const changeQty = (id, next) => {
  const item = cartItems.value.find(i => i.id === id)
  if (!item) return
  const max = getMaxStock(item)
  if (next > max) next = max
  cartStore.updateQuantity(id, Math.max(1, next))
}

function updateQtyFromInput(id, event) {
  const value = parseInt(event.target.value, 10)
  const item = cartItems.value.find(i => i.id === id)
  if (!item) return
  let newVal = value
  const max = getMaxStock(item)
  if (isNaN(newVal) || newVal < 1) newVal = 1
  if (newVal > max) newVal = max
  cartStore.updateQuantity(id, newVal)
  event.target.value = newVal
}

const removeItem = (id) => cartStore.removeFromCart(id)

const proceedToCheckout = () => {
  showCheckout.value = true
  formMessage.value = ''
}

const handleImageError = (e) => {
  if (e?.target) e.target.src = defaultImage
}

const submitOrder = async () => {
  if (!orderForm.name || !orderForm.phone || !orderForm.email) {
    formMessage.value = 'Заполните обязательные поля'
    formMessageType.value = 'error'
    return
  }
  if (!orderForm.consent) {
    formMessage.value = 'Необходимо согласие на обработку данных'
    formMessageType.value = 'error'
    return
  }

  submitting.value = true
  formMessage.value = ''
  try {
    const orderData = {
      name: orderForm.name,
      phone: orderForm.phone,
      email: orderForm.email,
      comment: orderForm.comment,
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
    formMessage.value = 'Заказ отправлен!'
    formMessageType.value = 'success'
    showCheckout.value = false
    orderForm.name = ''
    orderForm.phone = ''
    orderForm.email = ''
    orderForm.comment = ''
    orderForm.consent = false
    cartStore.clearCart()
  } catch (e) {
    console.error(e)
    formMessage.value = 'Ошибка отправки. Попробуйте позже.'
    formMessageType.value = 'error'
  } finally {
    submitting.value = false
    setTimeout(() => {
      formMessage.value = ''
    }, 5000)
  }
}
</script>

<style scoped>
/* ===== существующие стили корзины ===== */
.cart {
  max-width: 960px;
  margin: 32px auto;
  padding: 0 16px;
}

.cart-item__name-link {
  text-decoration: none;
  color: inherit;
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

.qty-input {
  width: 60px;
  text-align: center;
  border: none;
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
  font-size: 16px;
  padding: 0;
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

/* ===== стили формы (как в Contacts.vue) ===== */
.checkout-card {
  background: #fff;
  border-radius: 12px;
  padding: 8px 0;
}

.checkout-title {
  font-size: 24px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 8px;
  padding: 0 24px;
}

.checkout-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
  padding: 0 24px 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-label {
  font-weight: 600;
  color: #334155;
  font-size: 14px;
}

.form-control {
  padding: 12px 14px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.15s, box-shadow 0.15s;
}

.form-control:focus {
  outline: none;
  border-color: #51ae45;
  box-shadow: 0 0 0 3px rgba(81, 174, 69, 0.2);
}

.form-control:disabled {
  background: #f8fafc;
  cursor: not-allowed;
}

.privacy-check {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #444;
  cursor: pointer;
  user-select: none;
  margin-top: 2px;
}

.privacy-check a {
  color: #51ae45;
  text-decoration: underline;
}

.privacy-checkbox {
  width: 18px;
  height: 18px;
  accent-color: #51ae45;
  cursor: pointer;
  flex-shrink: 0;
}

.submit-btn {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  font-weight: 600;
  background: #51ae45;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.15s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.submit-btn:hover:not(:disabled) {
  background: #2e7d32;
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.spinner {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.form-message {
  margin-top: 8px;
  padding: 10px;
  border-radius: 6px;
  font-size: 14px;
  text-align: center;
}

.form-message.success {
  background: #e8f5e9;
  color: #2e7d32;
  border: 1px solid #a5d6a7;
}

.form-message.error {
  background: #ffebee;
  color: #c62828;
  border: 1px solid #ef9a9a;
}

/* ===== адаптив ===== */
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