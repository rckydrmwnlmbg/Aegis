# FR-11: Contractor & Workforce Governance
**Module:** Workforce Controls
**Related Schemas:** contractor_companies, workers, certifications, training_records
## 1. Module Overview
Mengelola data perusahaan sub-kontraktor beserta seluruh pekerjanya. Modul ini memastikan bahwa tidak ada pekerja tidak terlatih atau alat yang belum disertifikasi beroperasi di area proyek (Misal: Memastikan operator *Crane* memiliki SIO/Surat Izin Operator yang masih berlaku).
## 2. Actors & Roles
 * **Contractor Admin (External):** Perwakilan vendor/subkon yang diberi akses terbatas ke sistem untuk mengunggah data pekerja dan sertifikat mereka.
 * **HSE Admin (Internal):** Memverifikasi dokumen yang diunggah subkon.
 * **Safetyman / Security Gate:** Mengecek status pekerja di lapangan.
## 3. Business Logic & Rules
 1. **Contractor Onboarding:**
   * Subkon baru (contractor_companies) harus berstatus approved dan asuransinya valid sebelum mereka bisa mendaftarkan pekerjanya.
 2. **Certification Expiry Tracking (Critical):**
   * Sistem setiap tengah malam mengecek tabel certifications dan training_records.
   * Jika SIO/Sertifikat pekerja akan habis (H-30), sistem mengirim peringatan ke *Contractor Admin*.
   * Jika sudah kedaluwarsa, worker_status diubah otomatis menjadi restricted (tidak boleh bekerja).
 3. **Penalty / Violation Scorecard:**
   * Jika pekerja dari Kontraktor A melakukan pelanggaran (ditemukan lewat Modul *Hazard* atau *Incident*), skor kepatuhan (*Risk Score*) perusahaan kontraktor tersebut akan dikurangi secara sistemik.
## 4. UI/UX Flows
### 4.1 Web Flow (Next.js)
 * **Contractor Portal:** Dasbor khusus eksternal (*Restricted View*) bagi vendor untuk memantau status persetujuan dokumen pekerjanya.
 * **Compliance Scorecard:** Tabel peringkat (Leaderboard) performa K3 antar kontraktor bagi Manajemen Utama.
### 4.2 Mobile Flow (Flutter)
 * **Worker Verification (Scanner):** Fitur untuk *Safetyman* atau Sekuriti menggunakan kamera HP memindai *QR Code* di ID Card pekerja. Aplikasi akan merespons warna **Hijau** (Sertifikat Valid) atau **Merah** (Sertifikat Kedaluwarsa/Restricted).
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/contractors/workers/{id}/certifications (Upload dokumen sertifikat baru).
 * **Endpoint:** GET /api/v1/workers/scan/{qr_code} (Pengecekan status pekerja *real-time* via *Mobile*).
## 6. AI Agent Engineering Directives
 * **Future Vision (OCR AI):** Siapkan struktur *database* untuk *Confidence Score* ekstraksi. Di masa depan, AI *Vision* akan membaca foto KTP/SIO yang diunggah dan mengisi *form* otomatis tanpa ketikan manual.
 * **Database:** Terapkan Laravel Event/Listener (misal: CertificateExpiredEvent) untuk menangani logika perubahan status pekerja menjadi restricted agar logika *Controller* tidak menumpuk.
