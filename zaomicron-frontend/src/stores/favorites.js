import { defineStore } from 'pinia';

export const useFavoritesStore = defineStore('favorites', {
  state: () => ({
    items: [], // массив id избранных товаров
  }),
  getters: {
    isFavorite: (state) => (id) => state.items.includes(id),
    favoritesCount: (state) => state.items.length,
  },
  actions: {
    toggleFavorite(productId) {
      const index = this.items.indexOf(productId);
      if (index === -1) {
        this.items.push(productId);
      } else {
        this.items.splice(index, 1);
      }
      this.saveToLocalStorage();
    },
    addFavorite(productId) {
      if (!this.items.includes(productId)) {
        this.items.push(productId);
        this.saveToLocalStorage();
      }
    },
    removeFavorite(productId) {
      const index = this.items.indexOf(productId);
      if (index !== -1) {
        this.items.splice(index, 1);
        this.saveToLocalStorage();
      }
    },
    saveToLocalStorage() {
      localStorage.setItem('favorites', JSON.stringify(this.items));
    },
    loadFromLocalStorage() {
      const saved = localStorage.getItem('favorites');
      if (saved) {
        this.items = JSON.parse(saved);
      }
    },
  },
});