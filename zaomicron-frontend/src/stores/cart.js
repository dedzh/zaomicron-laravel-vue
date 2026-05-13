import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('cart') || '[]')
  }),
  getters: {
    totalQuantity: (state) => state.items.reduce((sum, i) => sum + i.quantity, 0),
    totalPrice: (state) => state.items.reduce((sum, i) => sum + (i.price || 0) * (i.quantity || 1), 0)
  },
  actions: {
    addToCart(product, qty = 1) {
      const existing = this.items.find(i => i.id === product.id);
      if (existing) {
        existing.quantity += qty;
      } else {
        this.items.push({ ...product, quantity: qty });
      }
      this.save();
    },
    removeFromCart(id) {
      this.items = this.items.filter(i => i.id !== id);
      this.save();
    },
    updateQuantity(id, qty) {
      const item = this.items.find(i => i.id === id);
      if (item) item.quantity = Math.max(1, qty);
      this.save();
    },
    clearCart() {
      this.items = [];
      this.save();
    },
    loadFromLocalStorage() {
      // Перечитываем state из localStorage
      this.items = JSON.parse(localStorage.getItem('cart') || '[]');
    },
    save() {
      localStorage.setItem('cart', JSON.stringify(this.items));
    }
  }
});