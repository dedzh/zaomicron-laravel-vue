import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('cart') || '[]'),
    notifications: []
  }),
  getters: {
    totalQuantity: (state) => state.items.reduce((sum, i) => sum + i.quantity, 0),
    totalPrice: (state) => state.items.reduce((sum, i) => sum + (i.price || 0) * (i.quantity || 1), 0)
  },
  actions: {
    addToCart(product, qty = 1) {
      const max = Number(product.stock);
      const existing = this.items.find(i => i.id === product.id);
      let newQty = qty;

      if (existing) {
        newQty = existing.quantity + qty;
      }

      if (!isNaN(max) && max > 0 && max < 9999) {
        if (newQty > max) {
          newQty = max;
          this.addNotification(`Доступно только ${max} шт. товара «${product.name}»`);
        }
      }

      if (existing) {
        existing.quantity = newQty;
      } else {
        this.items.push({ ...product, quantity: newQty });
      }
      this.save();
    },
    removeFromCart(id) {
      this.items = this.items.filter(i => i.id !== id);
      this.save();
    },
    updateQuantity(id, qty) {
      const item = this.items.find(i => i.id === id);
      if (item) {
        const max = Number(item.stock);
        let newQty = Math.max(1, qty);
        if (!isNaN(max) && max > 0 && max < 9999 && newQty > max) {
          newQty = max;
          this.addNotification(`Доступно только ${max} шт. товара «${item.name}»`);
        }
        item.quantity = newQty;
        this.save();
      }
    },
    clearCart() {
      this.items = [];
      this.save();
    },
    loadFromLocalStorage() {
      this.items = JSON.parse(localStorage.getItem('cart') || '[]');
    },
    save() {
      localStorage.setItem('cart', JSON.stringify(this.items));
    },
    addNotification(message) {
      const id = Date.now();
      this.notifications.push({ id, message });
      setTimeout(() => {
        this.notifications = this.notifications.filter(n => n.id !== id);
      }, 2500);
    }
  }
});