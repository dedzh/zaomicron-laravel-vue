const ABSOLUTE_URL_RE = /^(?:https?:)?\/\//i

const trimTrailingSlash = (value = '') => value.replace(/\/+$/, '')

export const getBackendOrigin = () => {
  const configured =
    (import.meta.env.VITE_BACKEND_URL || import.meta.env.VITE_API_BASE_URL || '').trim()

  if (configured) return trimTrailingSlash(configured)

  if (typeof window === 'undefined') return ''

  return window.location.origin
}

export function resolveMediaUrl(url, fallback) {
  if (!url) return fallback

  if (ABSOLUTE_URL_RE.test(url)) {
    return url
  }

  const clean = String(url)
    .replace(/\\+/g, '/')
    .replace(/\/{2,}/g, '/')

  if (clean.startsWith('/')) {
    return clean
  }

  return `/${clean.replace(/^\/+/, '')}`
}
