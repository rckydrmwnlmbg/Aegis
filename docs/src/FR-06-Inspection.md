# FR-06: Safety Patrol & Inspection Management
**Module:** Operational Safety
**Related Schemas:** inspection_templates, inspection_template_items, inspections, inspection_findings
## 1. Module Overview
Mendigitalkan proses patroli rutin dan inspeksi alat (misal: Inspeksi Perancah/Scaffolding, Inspeksi APAR, Inspeksi Alat Berat) menggunakan format *checklist* dinamis yang dikonfigurasi di tingkat *Enterprise*.
## 2. Actors & Roles
 * **Tenant Admin / HSE Manager:** Membuat dan mempublikasikan inspection_templates (Formulir Checklist).
 * **Inspector / Safetyman:** Menjalankan inspeksi di lapangan (Aplikasi Mobile).
 * **HSE Officer:** Meninjau inspection_findings (Temuan/Defisiensi) yang gagal dan menerbitkannya menjadi CAPA.
## 3. Business Logic & Rules
 1. **Dynamic Templating:**
   * *Checklist* tidak di- *hardcode*. Formulir dibangun secara dinamis dari tabel inspection_template_items.
   * Tipe input mencakup: *Pass/Fail/NA*, *Text Input*, dan *Mandatory Photo*.
 2. **Offline Execution (Crucial):**
   * Inspektur harus mengunduh *Template* inspeksi ke memori lokal HP sebelum berangkat patroli.
   * Eksekusi ratusan *item checklist* harus berjalan mulus secara *offline* dan menyimpannya di SQLite *array* sebelum di- *sync*.
 3. **Finding Generation:**
   * Setiap *item checklist* yang dijawab "Fail/Gagal" **wajib** disertai foto bukti dan keterangan.
   * Sistem akan otomatis membuat data di tabel inspection_findings untuk setiap butir yang gagal.
 4. **Scoring System:**
   * Setiap inspeksi menghasilkan skor kalkulasi (Misal: 85/100) berdasarkan bobot (*weight*) setiap pertanyaan.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter)
 * **Checklist Runner:** Antarmuka geser (*swipe-based*) atau *list* memanjang. Mengingat *checklist* bisa berisi 50+ pertanyaan, UI harus menyimpan progres secara otomatis setiap pergantian soal (*autosave locally*).
 * **Interruption Recovery:** Jika HP mati atau aplikasi tertutup saat inspeksi baru selesai 50%, pengguna dapat melanjutkannya dari titik terakhir tanpa kehilangan data.
### 4.2 Web Flow (Next.js)
 * **Template Builder:** UI *Drag-and-Drop* untuk admin membuat form inspeksi baru (Menambahkan bagian, mengubah tipe pertanyaan).
 * **Inspection Report View:** Halaman yang menampilkan hasil skor inspeksi dan daftar foto temuan yang gagal.
## 5. API & Integration Requirements
 * **Endpoint:** GET /api/v1/inspections/templates (Unduh daftar *form*).
 * **Endpoint:** POST /api/v1/sync/inspections (Kirim hasil inspeksi massal beserta lampiran fotonya).
## 6. AI Agent Engineering Directives
 * **Data Structure:** Dalam kontrak API, pastikan *payload* inspeksi yang dikirim dari *mobile* adalah sebuah *JSON array* yang berisi jawaban untuk setiap template_item_id.
 * **Database:** Gunakan transaksi database (DB::beginTransaction()) saat menyimpan hasil inspeksi untuk memastikan *Header* dan seluruh *Item Findings* tersimpan utuh, mencegah data setengah masuk.
