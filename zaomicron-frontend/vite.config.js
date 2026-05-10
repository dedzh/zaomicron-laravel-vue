import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/api': 'http://localhost:8000',
      '/images': 'http://localhost:8000',
      '/_images': 'http://localhost:8000',
      '/storage': 'http://localhost:8000',
      '/img': 'http://localhost:8000'
    }
  }
})
