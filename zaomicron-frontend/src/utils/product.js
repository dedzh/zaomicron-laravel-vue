export function getProductStock(product) {
  const stock = Number(product?.stock ?? 0)
  return Number.isFinite(stock) && stock > 0 ? stock : 0
}

export function isProductInStock(product) {
  return getProductStock(product) > 0
}

export function getProductImageCandidates(product) {
  const images = Array.isArray(product?.images) ? product.images.filter(Boolean) : []
  if (images.length) return images

  const direct = product?.image || product?.image_url
  if (direct) return [direct]

  const sku = product?.sku || product?.id
  if (sku) return [`/images/foto/${sku}/1.jpg`]

  return []
}
