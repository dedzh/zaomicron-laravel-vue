import logoFallback from '@/assets/logo.png'
import { resolveMediaUrl } from '@/utils/media'

export function getImage(src) {
  return resolveMediaUrl(src, logoFallback)
}

export function handleImageError(event) {
  if (event?.target) {
    event.target.src = logoFallback
  }
}