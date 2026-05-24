# DevOps, Infrastructure, & Deployment Specs
**Project:** Aegis AI EHS Platform
## 1. Infrastructure Architecture
Sistem harus siap di-*deploy* ke lingkungan *Cloud* (AWS/DigitalOcean/GCP) menggunakan pendekatan berbasis kontainer (*Containerized*).
### 1.1 Server Components (Dockerized)
 * **App Server:** Nginx + PHP 8.2 FPM (Laravel)
 * **Web Frontend Server:** Node.js (Next.js)
 * **Database Server:** PostgreSQL 15 (Managed Database sangat disarankan untuk produksi demi keamanan *backup* harian otomatis).
 * **In-Memory Cache & Queue:** Redis 7.x
 * **Storage:** Amazon S3 (atau MinIO untuk *on-premise deployment*).
## 2. Queue & Background Workers (Crucial for AI)
AI memproses data secara asinkron. Jika *worker* mati, sistem laporan akan macet.
 1. **Supervisor / Laravel Horizon:** Wajib diinstal di *server backend*. Konfigurasikan untuk menjalankan php artisan queue:work secara permanen.
 2. **Worker Scaling:** Siapkan minimal 3 proses *worker* yang berdedikasi khusus untuk *queue* ai_processing, agar 1 rekaman suara yang panjang tidak memblokir antrean laporan pengguna lain.
 3. **Timeout Rules:** Atur timeout pada Supervisor minimal 120 detik karena panggilan API LLM (OpenAI) rentan terhadap *delay* jaringan.
## 3. CI/CD Pipeline (GitHub Actions)
Langkah otomasi saat kamu melakukan git push ke cabang main:
 1. **Linting & Code Style:** Jalankan PHP_CodeSniffer & Flutter Analyze. Tolak rilis jika ada peringatan kritis.
 2. **Automated Testing:** Jalankan seluruh koleksi pengujian (*Pest/PHPUnit*). Jika ada 1 tes gagal, batalkan rilis (*abort deployment*).
 3. **Build & Deploy:** - Laravel: Tarik (*pull*) kode terbaru, jalankan composer install, php artisan migrate --force, dan php artisan queue:restart (agar worker memuat kode AI terbaru).
   * Web: Jalankan npm run build dan *restart daemon* (PM2/Docker).
   * Mobile: Kompilasi APK (Android) dan otomatis unggah ke Firebase App Distribution untuk dicoba oleh tim QA internal.
## 4. Monitoring & Logging
 * Jangan simpan *error log* hanya di file laravel.log.
 * Integrasikan dengan alat pelacak *error* (seperti Sentry atau Bugsnag).
 * Jika API OpenAI mengembalikan *Error 500* (Server OpenAI *down*), sistem harus merekamnya dan memicu *alert* ke Telegram atau Discord *Developer*.
