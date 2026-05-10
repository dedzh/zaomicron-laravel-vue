const ABSOLUTE_URL_RE = /^(?:https?:)?\/\//i

const trimTrailingSlash = (value) => value.replace(/\/+$/, '')

export const getBackendOrigin = () => {
  const configured =
    (import.meta.env.VITE_BACKEND_URL || import.meta.env.VITE_API_BASE_URL || '').trim()

  if (configured) return trimTrailingSlash(configured)

  if (typeof window === 'undefined') return ''

  // Local dev frontend usually runs on 5173, backend on 8000.
  if (window.location.hostname === 'localhost' && window.location.port === '5173') {
    return 'http://localhost:8000'
  }

  return window.location.origin
}

export const resolveMediaUrl = (rawPath, fallback = '') => {
  if (!rawPath || typeof rawPath !== 'string') return fallback

  const normalized = rawPath.trim().replace(/\\\//g, '/')
  if (!normalized) return fallback

  if (ABSOLUTE_URL_RE.test(normalized) || normalized.startsWith('data:')) {
    return normalized
  }

  const backendOrigin = getBackendOrigin()
  const cleanPath = normalized.replace(/^\/+/, '')
  return backendOrigin ? `${backendOrigin}/${cleanPath}` : `/${cleanPath}`
}
