import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
    build: {
        // Optimizaciones para producción
        rollupOptions: {
            output: {
                // Solo crear chunks manuales cuando realmente tengamos dependencias grandes
                manualChunks: undefined
            }
        },
        // Asegurar que los assets se incluyan
        copyPublicDir: true,
        // Optimizar para producción
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        }
    }
});
