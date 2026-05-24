# FR-10: Audit & Compliance Tracking
**Module:** Governance & Compliance
**Related Schemas:** audits, audit_findings, compliance_obligations, non_conformances
## 1. Module Overview
Modul ini mendigitalkan pelaksanaan Audit K3 (Internal maupun Eksternal) dan pelacakan Kewajiban Kepatuhan (*Compliance Obligations*). Bertujuan untuk memastikan bahwa setiap temuan ketidaksesuaian (*Non-Conformance*) dicatat, dilacak, dan diselesaikan sebelum audit pengawasan berikutnya.
## 2. Actors & Roles
 * **Lead Auditor:** Merencanakan jadwal audit, mengeksekusi *checklist* audit, dan menerbitkan laporan temuan.
 * **Auditee (Manager/Dept Head):** Pihak yang diaudit, bertanggung jawab merespons temuan dan membuat rencana perbaikan (CAPA).
 * **HSE Manager:** Memantau status kepatuhan (*Compliance Health*) secara keseluruhan.
## 3. Business Logic & Rules
 1. **Audit Lifecycle:**
   * planned (dijadwalkan) -> active (sedang dieksekusi) -> review (penyusunan laporan) -> closed (semua temuan ditutup) -> archived.
 2. **Finding Generation & NCR:**
   * Selama audit berjalan (active), Auditor dapat mencatat audit_findings.
   * Temuan yang bersifat fatal/melanggar hukum akan dikategorikan sebagai *Non-Conformance Report* (NCR) dan otomatis masuk ke tabel non_conformances.
 3. **CAPA Linkage (Mandatory):**
   * Sebuah Audit TIDAK BOLEH berstatus closed jika masih ada NCR atau *Finding* (berstatus *Major*) yang belum memiliki *Corrective Action* (CAPA) yang selesai (closed).
 4. **Compliance Obligations Registry:**
   * Entitas compliance_obligations bertindak sebagai pengingat kalender hukum (Misal: "Perpanjang Izin Lingkungan Paling Lambat Desember 2026"). Sistem akan memberikan notifikasi otomatis H-90, H-30, dan H-7.
## 4. UI/UX Flows
### 4.1 Web Flow (Next.js - Dominan)
 * **Audit Planner Board:** Tampilan kalender (Gantt Chart/Kalender Bulanan) untuk melihat jadwal audit tahunan.
 * **Audit Execution Workspace:** Halaman layar penuh bagi Auditor untuk mengisi nilai (*scoring*), mengetik temuan, dan melampirkan foto bukti dokumen.
### 4.2 Mobile Flow (Flutter - Eksekusi Lapangan)
 * **Offline Audit Checklist:** Sama seperti modul Inspeksi (FR-06), Auditor dapat mengunduh format audit ke memori lokal HP saat melakukan tinjauan fisik di area pabrik/proyek.
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/audits (Pembuatan jadwal audit).
 * **Endpoint:** POST /api/v1/audits/{id}/findings (Mencatat temuan ketidaksesuaian).
 * **Endpoint:** GET /api/v1/compliance/expiring (Mengambil daftar kewajiban hukum yang akan jatuh tempo).
## 6. AI Agent Engineering Directives
 * **AI Audit Summarizer:** Tambahkan *endpoint* khusus yang memanggil LLM untuk merangkum puluhan audit_findings menjadi satu paragraf "Executive Summary" untuk laporan akhir Audit.
 * **Backend Constraints:** Saat mengubah status Audit menjadi closed, *Controller* wajib memverifikasi (menggunakan where *query*) bahwa tidak ada relasi CAPA yang masih open atau overdue. Jika ada, tolak dengan status 422 Unprocessable Entity.
