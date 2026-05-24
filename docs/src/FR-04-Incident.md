# FR-04: Incident Management
**Module:** Operational Safety
**Related Schemas:** incidents, incident_people, incident_investigations, incident_rca, incident_timelines
## 1. Module Overview
Modul ini menangani siklus hidup pelaporan insiden, mulai dari penangkapan data awal di lapangan menggunakan suara (*Voice Note*) atau teks, penataan struktur laporan oleh AI (5W1H), investigasi, analisa akar masalah (RCA), hingga penutupan kasus.
## 2. Actors & Roles
 * **Safetyman/Field Worker:** Membuat laporan awal (Draft) dari lapangan.
 * **HSE Officer:** Meninjau dan memvalidasi Draft, memulai proses investigasi.
 * **HSE Manager:** Melakukan penutupan (*Closure Approval*) setelah semua tindakan perbaikan selesai.
 * **AI Agent (Structuring):** Mengubah suara mentah menjadi JSON terstruktur.
 * **AI Agent (RCA Assistant):** Membantu Officer menyusun logika 5-Why.
## 3. Business Logic & Rules
 1. **Incident Creation (Mobile):**
   * Pengguna menekan tombol "Lapor Insiden".
   * Aplikasi otomatis merekam *Timestamp* (Waktu) dan *GPS Location* (jika diizinkan).
   * Pengguna dapat merekam suara maksimal 3 menit atau mengetik manual, serta melampirkan maksimal 5 foto bukti.
 2. **AI Processing Pipeline (Backend):**
   * Saat sinkronisasi selesai, sistem menjalankan *AI Incident Structuring Job*.
   * Status laporan adalah processing hingga AI selesai, kemudian berubah menjadi draft_ready.
 3. **Human Validation (Critical):**
   * Laporan AI *hanya* berstatus Draft. HSE Officer atau Safetyman harus membaca, mengoreksi, dan menekan tombol "Submit Report".
 4. **State Machine (Workflow Status):**
   * draft -> submitted -> under_review -> investigating -> closed
 5. **Timeline Auditing:**
   * Setiap perubahan *workflow_status* wajib memasukkan log ke tabel incident_timelines.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter)
 * **Smart Capture Screen:** UI minimalis. Tombol *Microphone* raksasa di tengah. Tombol *Camera* di bawah. Tidak ada form panjang untuk diisi secara manual kecuali AI gagal.
 * **Draft Review Screen:** Menampilkan *form* 5W1H yang sudah terisi. Field yang diragukan oleh AI (Confidence < 70%) disorot dengan warna kuning agar pengguna lebih teliti membacanya.
### 4.2 Web Flow (Next.js)
 * **Incident Board:** Tampilan Kanban atau Tabel yang menunjukkan tiket insiden yang perlu ditindaklanjuti.
 * **RCA Workspace:** Halaman khusus untuk Officer memasukkan kronologi dan meminta saran 5-Why dari AI (RCA Assistant).
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/sync/incidents (Pembuatan).
 * **Endpoint:** PUT /api/v1/incidents/{id} (Validasi Draft/Update).
 * **Endpoint:** POST /api/v1/incidents/{id}/rca/generate (Memicu AI untuk membantu menyusun RCA).
## 6. AI Agent Engineering Directives
 * **Backend:** Pastikan *prompt* AI secara tegas memisahkan "Tipe Insiden" (Property Damage, LTI, dll) dari teks. Jika AI tidak yakin, atur kolom incident_type_id menjadi *null* dan biarkan manusia yang memilihnya di UI.
