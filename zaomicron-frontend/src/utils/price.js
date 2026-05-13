/**
 * Цена для отображения в каталоге / карточке: 0 → «по запросу», иначе число + ₽.
 * @param {unknown} value
 * @param {{ suffix?: string }} [opts]
 * @returns {string}
 */
export function formatProductPriceRub(value, opts = {}) {
  const suffix = opts.suffix !== undefined ? opts.suffix : ' ₽';
  const num = Number(value);
  if (!Number.isFinite(num)) {
    return value === null || value === undefined ? '' : String(value);
  }
  if (num === 0) {
    return 'по запросу';
  }
  return new Intl.NumberFormat('ru-RU').format(num) + suffix;
}

/**
 * Для полей фильтра: пусто / NaN → null, иначе число.
 * @param {unknown} v
 * @returns {number|null}
 */
export function sanitizeFilterPrice(v) {
  if (v === '' || v === null || v === undefined) return null;
  const n = Number(v);
  return Number.isFinite(n) ? n : null;
}
