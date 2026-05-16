import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    base: '', // <- ESTA LÍNEA HACE QUE LAS RUTAS SEAN RELATIVAS Y NO TRONEN EN PRODUCCIÓN
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
    }
});