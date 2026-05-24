# FR-03: Offline Sync Engine & AI Queue Bridge
**Module:** Core Platform
**Related Schemas:** ai_runs (Backend), sync_queue (Mobile Local SQLite)
## 1. Module Overview
Ini adalah infrastruktur utama dari aplikasi lapangan. Modul ini bertanggung jawab menyimpan data operasi (teks, gambar, audio) ke penyimpanan lokal saat tidak ada koneksi internet, lalu mengunggahnya secara *background* (*chunking* & *retry*) ke API Laravel ketika koneksi kembali tersedia, dan meneruskannya ke antrean AI (Redis).
## 2. Actors & Roles
 * **System (Background Service):** Berjalan tanpa perlu campur tangan pengguna secara aktif.
## 3. Business Logic & Rules
 1. **Local Queue Priority (Mobile):**
   * Setiap kali *Safetyman* klik "Simpan" pada Laporan, data masuk ke tabel sync_queue lokal dengan status queued.
   * UUID dibuat di *Mobile* menggunakan library uuid (v4).
 2. **Sync Execution (Mobile):**
   * *Listener* mendeteksi koneksi internet stabil.
   * Proses *upload* berjalan berurutan (*FIFO*).
   * Pengiriman *file* media (*audio/image*) menggunakan format multipart/form-data.
 3. **Conflict & Idempotency Resolution (Backend):**
   * Laravel mengecek UUID yang dikirim. Jika UUID sudah ada di *database* (misal: *request* sebelumnya berhasil masuk namun respons terputus), Laravel harus mengabaikan *insert* dan langsung mengembalikan status 200 OK atau 202 Accepted.
 4. **Queue Hand-off (Backend):**
   * Setelah Laravel berhasil menyimpan *file* mentah ke *storage* (Local/S3) dan baris data awal ke DB, Laravel **WAJIB** membuat *record* di tabel ai_runs, melempar *Job* ke RabbitMQ/Redis, dan memberikan respons 202 Accepted ke *Mobile*. **Jangan memanggil API AI secara sinkronus**.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter)
 * **Sync Indicator:** Terdapat ikon "Awan" di pojok atas layar.
   * Awan dengan garis miring: *Offline*.
   * Awan dengan panah memutar: *Syncing*.
   * Awan dengan centang: *All Synced*.
 * **Sync Queue Page:** Halaman khusus di mana pengguna bisa melihat *file-file* yang tertunda/gagal diunggah dan tombol untuk memicu *Retry Manual*.
## 5. API & Integration Requirements
 * *(Ref to api-contracts.md)*: POST /api/v1/sync/incidents dan POST /api/v1/sync/hazards.
## 6. AI Agent Engineering Directives
 * **Mobile (Flutter):** Gunakan *package* seperti dio untuk *HTTP interceptors* dan workmanager atau *background fetch* untuk mengeksekusi antrean di balik layar.
 * **Backend (Laravel):** Gunakan Bus::dispatch() atau dispatch()->afterResponse() untuk mengisolasi proses AI agar waktu respons API (*latency*) tetap di bawah 500ms.
