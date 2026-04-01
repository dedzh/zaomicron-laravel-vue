const STORAGE_KEY = 'zaomicron_cart_v1';

function normalizeId(id) {
  return id == null ? '' : String(id);
}

export function loadCart() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    const parsed = raw ? JSON.parse(raw) : [];
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
}

export function saveCart(cart) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
}

export function getCartItems() {
  return loadCart();
}

export function addToCart(product, quantity = 1) {
  const id = normalizeId(product?.id);
  if (!id) return;

  const cart = loadCart();
  const emoji = product?.emoji ?? '🛒';

  const index = cart.findIndex(x => normalizeId(x.id) === id);
  if (index >= 0) {
    cart[index].quantity = Math.max(1, Number(cart[index].quantity || 1) + Number(quantity || 1));
    cart[index].name = product?.name ?? cart[index].name;
    cart[index].sku = product?.sku ?? cart[index].sku;
    cart[index].price = product?.price ?? cart[index].price;
    cart[index].emoji = emoji;
  } else {
    cart.push({
      id,
      name: product?.name ?? '',
      sku: product?.sku ?? '',
      price: Number(product?.price ?? 0),
      quantity: Math.max(1, Number(quantity || 1)),
      emoji
    });
  }

  saveCart(cart);
}

export function updateQuantity(id, delta) {
  const cart = loadCart();
  const key = normalizeId(id);
  const index = cart.findIndex(x => normalizeId(x.id) === key);
  if (index < 0) return;

  cart[index].quantity = Math.max(1, Number(cart[index].quantity || 1) + Number(delta || 0));
  saveCart(cart);
}

export function removeFromCart(id) {
  const cart = loadCart();
  const key = normalizeId(id);
  const next = cart.filter(x => normalizeId(x.id) !== key);
  saveCart(next);
}

export function clearCart() {
  saveCart([]);
}

