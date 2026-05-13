import { defineStore } from 'pinia';

export const useFavoritesStore = defineStore('favorites', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('favorites') || '[]')
  }),
  actions: {
    isFavorite(id) {
      return this.items.includes(id);
    },
    toggleFavorite(id) {
      const index = this.items.indexOf(id);
      if (index === -1) {
        this.items.push(id);
      } else {
        this.items.splice(index, 1);
      }
      localStorage.setItem('favorites', JSON.stringify(this.items));
    }
  }
});