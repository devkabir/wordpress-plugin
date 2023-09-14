import inject from '@rollup/plugin-inject'
import {resolve} from 'path'
import {fileURLToPath} from 'url'
import {defineConfig} from 'vite'

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
        emptyOutDir: true,
        minify: true,
        cssCodeSplit: false,
        sourcemap: true,
        rollupOptions: {
            external: ['jquery'],
            input: {
                form: fileURLToPath(new URL('./shortcodes/form/index.html', import.meta.url)),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
                globals: {
                    $: 'jquery'
                }

            }
        },

    }
})
