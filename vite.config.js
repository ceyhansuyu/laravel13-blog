import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // Eklentinin import edildiğinden emin ol

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(), // Eklentinin burada çağrıldığından emin ol
    ],
    // Herd ile kararlı ve hızlı çalışması için eklenen sunucu ayarları
    server: {
        detectTls: 'blog.test', // Herd SSL sertifikasını tanıması için buraya aldım
        cors: true,
        strictPort: true,
        hmr: {
            host: 'blog.test', // Herd HTTPS kullandığı için burayı da local domainle eşitledim
        },
        watch: {
            // Herd'ün izlediği yüzlerce gereksiz php/log dosyasını Vite'ın taramasını engelliyoruz
            ignored: ['**/storage/**', '**/bootstrap/cache/**', '**/vendor/**'],
        },
    },
});