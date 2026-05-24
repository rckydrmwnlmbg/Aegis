# FR-12: Document Control & Toolbox Talks
**Module:** Workforce Controls & Compliance
**Related Schemas:** documents, document_versions, toolbox_talks, toolbox_attendances
## 1. Module Overview
Menyatukan manajemen dokumen (SOP, Kebijakan K3, Manual) dengan eksekusi sosialisasi K3 harian di lapangan (*Toolbox Talks* / *Safety Briefing*). Memastikan bahwa setiap kebijakan baru tersosialisasi dan daftar hadir terdokumentasi secara digital.
## 2. Actors & Roles
 * **Document Controller (HSE Admin):** Mengunggah versi SOP terbaru.
 * **Supervisor / Foreman:** Bertindak sebagai *Presenter* yang memberikan *Toolbox Talk* (TBT) setiap pagi.
 * **Workers:** Peserta TBT yang harus membubuhkan bukti kehadiran.
## 3. Business Logic & Rules
 1. **Document Versioning:**
   * File SOP tidak boleh di- *overwrite* secara fisik (ditimpa).
   * Saat revisi baru diunggah, document_versions baru tercipta dan versi lama ditandai sebagai obsolete.
 2. **Toolbox Talk Execution (Offline-Ready):**
   * *Supervisor* di lapangan membuat sesi TBT baru di aplikasi *mobile*.
   * *Supervisor* memilih Topik (Bisa diambil dari rangkuman Insiden kemarin).
 3. **Digital Attendance Verification:**
   * Untuk mencegah pemalsuan daftar hadir (tanda tangan "titipan"), sistem memerlukan 2 bukti:
     1. Foto grup seluruh peserta *briefing* (Tersimpan sebagai attachments terkait TBT).
     2. Tanda tangan digital (*Signature Pad*) per pekerja di layar HP Supervisor, atau absensi *Checklist* nama dari *database* workers.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter - Daily Use)
 * **TBT Session Runner:** Antarmuka untuk Supervisor. Langkah 1: Pilih Topik. Langkah 2: Ambil Foto Kerumunan Pekerja. Langkah 3: Berikan HP bergantian ke pekerja untuk tanda tangan digital di layar, ATAU *scan* ID Card mereka satu per satu.
### 4.2 Web Flow (Next.js)
 * **Document Repository:** UI layaknya Google Drive untuk mencari dan mengunduh SOP terbaru. Terintegrasi dengan fitur pencarian.
 * **TBT Compliance Report:** Dasbor yang menunjukkan "Persentase Kehadiran TBT Harian per Subkontraktor".
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/sync/toolbox_talks (Kirim data TBT massal dari *offline* beserta foto grup dan larik/array daftar absensi).
 * **Endpoint:** GET /api/v1/documents/latest (Mengambil dokumen SOP versi aktif).
## 6. AI Agent Engineering Directives
 * **AI TBT Topic Generator:** Integrasikan dengan API LLM. Jika Supervisor menekan tombol "Generate Topic", AI (berdasarkan RAG) melihat data di incidents 7 hari terakhir dan meracik naskah *Safety Briefing* singkat 3 menit yang relevan (Misal: "Minggu ini banyak temuan perancah rusak, mari fokus membahas SOP bekerja di ketinggian").
 * **Backend Data Parsing:** Kontrak *Payload Sync TBT* harus menerima JSON Array bersarang (*Nested JSON*) yang berisi toolbox_talks (induk) dan toolbox_attendances (anak) untuk efisiensi sinkronisasi.
