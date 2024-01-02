import { fileURLToPath, URL } from 'node:url'
import * as path from 'path'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    emptyOutDir: true,
    minify: true,
    rollupOptions: {
      input: {
        index: path.resolve(__dirname, 'index.html')
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name][extname]'
      }
    }
  },
  server: {
    port: 5000,
    hmr: {
      port: 5000,
      host: 'localhost',
      protocol: 'ws'
    }
  }
})
