# FR-07: Permit To Work (PTW) System
**Module:** Work Governance
**Related Schemas:** permit_types, permits, permit_approvals, permit_controls
## 1. Module Overview
Mengelola siklus hidup Izin Kerja Berbahaya (seperti *Hot Work*, *Confined Space*, dll.) mulai dari pengajuan, persetujuan berjenjang, aktivasi, penangguhan (suspension), hingga penutupan (closure) yang terdokumentasi dengan *Digital Signature* untuk keperluan audit kepatuhan.
## 2. Actors & Roles
 * **Requester (Supervisor/Contractor):** Membuat draf PTW dan mendefinisikan lokasi serta ruang lingkup kerja.
 * **PTW Issuer / HSE Officer:** Menyetujui PTW setelah memverifikasi pengendalian bahaya (Controls).
 * **Safety Watcher:** Memantau selama pekerjaan berlangsung.
## 3. Business Logic & Rules
 1. **Dynamic Approval Chain:**
   * Izin kerja biasa mungkin hanya butuh 1 persetujuan (HSE Officer). Izin kerja berisiko ekstrim (misal: *Confined Space* + *Hot Work*) bisa membutuhkan 3 tahap (HSE Officer -> Facility Manager -> Site Manager).
   * Sistem harus mencatat ID penyetuju, waktu, dan jenis keputusan (Setuju/Tolak) di tabel permit_approvals.
 2. **Time Expiration Validation:**
   * Setiap PTW memiliki valid_from dan valid_until.
   * Pekerjaan **tidak boleh** berstatus aktif jika sudah melewati valid_until. Sistem harus otomatis mengirim *Notification* peringatan 1 jam sebelum izin kadaluarsa.
 3. **Suspension Logic (Stop Work Authority):**
   * Jika terjadi insiden atau bahaya di lokasi PTW, PTW tersebut bisa langsung diubah statusnya menjadi suspended oleh Safetyman/HSE, sehingga pekerjaan harus segera dihentikan.
 4. **Mandatory JSA Linkage:**
   * Beberapa jenis PTW berisiko tinggi wajib menautkan dokumen JSA (jsa_documents.id) yang sudah berstatus approved sebelum PTW tersebut bisa diajukan.
## 4. UI/UX Flows
### 4.1 Mobile Flow
 * **Approval Request Notification:** Manager/Officer yang berwenang menerima *Push Notification* dan bisa memberikan *Digital Signature* langsung di layar HP (menggunakan *signature pad widget*) untuk menyetujui izin dari lapangan.
 * **Active PTW Board:** Menampilkan daftar izin yang sedang aktif hari ini di *site* tersebut.
### 4.2 Web Flow
 * **Digital Permit Board:** Menggantikan papan perizinan fisik di dinding kantor *site*. Halaman ini memvisualisasikan PTW aktif berdasarkan lokasi area di proyek (Filterable).
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/permits (Pembuatan).
 * **Endpoint:** POST /api/v1/permits/{id}/approve (Persetujuan dengan payload Tanda Tangan Digital).
 * **Endpoint:** PATCH /api/v1/permits/{id}/status (Untuk memicu status *Suspended* atau *Closed*).
## 6. AI Agent Engineering Directives
 * **AI Permit Assistant:** Tambahkan fitur tombol "Tanya AI" saat Requester mengisi Controls. AI (via RAG) harus merujuk pada standar perusahaan (misal: "Pengelasan di tangki butuh Gas Test LEL").
 * **Security:** Terapkan validasi Gate atau Policy di Laravel untuk memastikan bahwa ID pengguna yang mencoba menekan *endpoint* /approve adalah benar-benar orang yang memiliki hak persetujuan (*Role* yang tepat), bukan *bypass*.
