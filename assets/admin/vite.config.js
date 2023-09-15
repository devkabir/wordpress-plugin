import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import * as path from "path";

const root = path.resolve(__dirname, "./src");
const outDir = path.resolve(__dirname, "./dist");
// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: {
      "@": path.resolve(__dirname, "./src"),
    },
  },

  plugins: [vue()],
  css: root,
  root,
  build: {
    outDir,
    emptyOutDir: true,
    rollupOptions: {
      input: {
        index: path.resolve(root, "index.html"),
      },
      output: {
        entryFileNames: "[name].js",
        chunkFileNames: "[name].js",
        assetFileNames: "[name][extname]",
      },
    },
  },
  server: {
    port: 5000,
    hmr: {
      port: 5000,
      host: "localhost",
      protocol: "ws"
    }
  }
});
