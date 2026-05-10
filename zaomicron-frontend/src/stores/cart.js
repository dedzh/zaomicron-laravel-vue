import { defineStore } from 'pinia';

export const STORAGE_KEY = 'zaomicron_cart_v1';

function normalizeId(id) {
    return id == null ? '' : String(id);
}

function normalizeItem(raw) {
    const id = normalizeId(raw?.id);
    if (!id) {
        return null;
    }
    return {
        id: raw.id,
        name: String(raw?.name ?? ''),
        sku: String(raw?.sku ?? ''),
        price: Number(raw?.price ?? 0),
        quantity: Math.max(1, Number(raw?.quantity ?? 1)),
        image: raw?.image ? String(raw.image) : null,
        emoji: raw?.emoji ?? null,
    };
}

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
    }),
    getters: {
        totalQuantity: (state) => state.items.reduce((sum, item) => sum + Number(item.quantity || 0), 0),
        totalPrice: (state) =>
            state.items.reduce((sum, item) => sum + Number(item.price || 0) * Number(item.quantity || 0), 0),
    },
    actions: {
        addToCart(product, quantity = 1) {
            const id = normalizeId(product?.id);
            if (!id) {
                return;
            }
            const qty = Math.max(1, Number(quantity) || 1);
            const existing = this.items.find((item) => normalizeId(item.id) === id);
            if (existing) {
                existing.quantity = Number(existing.quantity || 0) + qty;
                existing.name = product?.name ?? existing.name;
                existing.sku = product?.sku ?? existing.sku;
                existing.price = Number(product?.price ?? existing.price);
                if (product?.image) {
                    existing.image = product.image;
                }
            } else {
                const row = normalizeItem({ ...product, quantity: qty });
                if (row) {
                    this.items.push(row);
                }
            }
            this.saveToLocalStorage();
        },
        removeFromCart(productId) {
            const key = normalizeId(productId);
            this.items = this.items.filter((item) => normalizeId(item.id) !== key);
            this.saveToLocalStorage();
        },
        updateQuantity(productId, quantity) {
            const key = normalizeId(productId);
            const item = this.items.find((i) => normalizeId(i.id) === key);
            if (item) {
                item.quantity = Math.max(1, Number(quantity));
                this.saveToLocalStorage();
            }
        },
        clearCart() {
            this.items = [];
            this.saveToLocalStorage();
        },
        saveToLocalStorage() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items));
            try {
                localStorage.removeItem('cart');
            } catch {
                //
            }
        },
        loadFromLocalStorage() {
            try {
                let raw = localStorage.getItem(STORAGE_KEY);
                if (!raw) {
                    const legacy = localStorage.getItem('cart');
                    if (legacy) {
                        raw = legacy;
                        localStorage.setItem(STORAGE_KEY, legacy);
                        localStorage.removeItem('cart');
                    }
                }
                if (!raw) {
                    this.items = [];
                    return;
                }
                const parsed = JSON.parse(raw);
                if (!Array.isArray(parsed)) {
                    this.items = [];
                    return;
                }
                this.items = parsed.map(normalizeItem).filter(Boolean);
                this.saveToLocalStorage();
            } catch {
                this.items = [];
            }
        },
    },
});
