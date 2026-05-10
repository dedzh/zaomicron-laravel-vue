import { getActivePinia } from 'pinia';
import { useCartStore } from '@/stores/cart';

function store() {
    if (!getActivePinia()) {
        return null;
    }
    return useCartStore();
}

export function addToCart(product, quantity = 1) {
    const s = store();
    if (s) {
        s.addToCart(product, quantity);
    }
}

export function updateQuantity(id, delta) {
    const s = store();
    if (!s) {
        return;
    }
    const key = id == null ? '' : String(id);
    const item = s.items.find((x) => String(x.id) === key);
    if (!item) {
        return;
    }
    const next = Math.max(1, Number(item.quantity || 1) + Number(delta || 0));
    s.updateQuantity(item.id, next);
}

export function removeFromCart(id) {
    store()?.removeFromCart(id);
}

export function clearCart() {
    store()?.clearCart();
}
