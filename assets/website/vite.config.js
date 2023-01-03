

import inject from '@rollup/plugin-inject'
import { resolve } from 'path'
import { fileURLToPath } from 'url'
import { defineConfig } from 'vite'

const rootPath = resolve(__dirname, './shortcodes')
// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
        }),
    ],
    root: rootPath,
    css: rootPath,    
    build: {
        outDir: resolve(__dirname, './dist'),
        emptyOutDir:true,
        minify: false,
        cssCodeSplit: false,
        sourcemap: false,
        rollupOptions: {
            input: {
                dolly: fileURLToPath(new URL('./shortcodes/dolly/index.html', import.meta.url)),
                hello: fileURLToPath(new URL('./shortcodes/hello/index.html', import.meta.url)),
            },
            output:{
                assetFileNames: '[name][extname]',
                entryFileNames: '[name].js'
            }
        },

    },
})