import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    proxy: {
      '/api': 'http://localhost:8000',
      '/images': 'http://localhost:8000',
      '/_images': 'http://localhost:8000',
      '/uploads': 'http://localhost:8000',
      '/storage': 'http://localhost:8000',
      '/img': 'http://localhost:8000',
    },
  },
})
