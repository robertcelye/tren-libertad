import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Vite compila el CSS y el JS de la capa Vista (resources/) hacia public/build.
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // Escucha en 127.0.0.1 (IPv4), igual que php artisan serve, para que el navegador encuentre los archivos.
        host: '127.0.0.1',
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
