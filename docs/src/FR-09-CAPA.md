# FR-09: CAPA (Corrective and Preventive Action) Management
**Module:** Work Governance
**Related Schemas:** corrective_actions, corrective_action_updates
## 1. Module Overview
Modul "Tulang Punggung" dari perbaikan berkelanjutan K3. Mengelola tindakan perbaikan yang dihasilkan dari berbagai modul lain (Insiden, Audit, Hazard). Memastikan tindakan tersebut memiliki penanggung jawab (PIC), batas waktu (Tenggat/Due Date), dan bukti penutupan (Evidence).
## 2. Actors & Roles
 * **Creator (HSE Officer/Auditor):** Membuat CAPA dan menunjuk PIC.
 * **Assignee / PIC (Contractor/Dept Head):** Menerima penugasan, melakukan perbaikan fisik, dan mengunggah foto bukti penyelesaian.
 * **Verifier (HSE Officer):** Meninjau bukti dan memutuskan untuk menutup CAPA (Close) atau menolaknya (Reject).
## 3. Business Logic & Rules
 1. **Polymorphic Source Linkage:**
   * Tabel CAPA harus menggunakan relasi Polimorfik Laravel (source_type dan source_id).
   * Hal ini karena CAPA bisa lahir dari Laporan Insiden (App\Models\Incident), Temuan Audit (App\Models\AuditFinding), atau Temuan Bahaya (App\Models\HazardObservation).
 2. **SLA & Escalation Engine:**
   * Sistem setiap tengah malam mengecek CAPA yang lewat due_date.
   * Hari +1: Status berubah jadi overdue. Kirim email teguran ke PIC.
   * Hari +3: *Escalation*. Kirim email tembusan ke Manajer Proyek.
 3. **Verification Loop:**
   * PIC tidak bisa secara mandiri menutup status menjadi closed. Mereka hanya bisa mengubah ke pending_verification.
   * HSE Officer harus mengecek foto bukti sebelum status sah menjadi closed.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Untuk PIC Lapangan)
 * **Task List:** Pengguna dengan *Role* selain HSE (misal: Mandor Konstruksi) ketika membuka aplikasi akan melihat daftar "Tugas Perbaikan Saya".
 * **Upload Evidence:** Mengklik tugas, memotret kondisi yang sudah diperbaiki (misal: oli tumpah sudah dibersihkan), memberikan keterangan, lalu klik "Submit Bukti".
### 4.2 Web Flow
 * **CAPA Tracker Dashboard:** Menampilkan grafik batang/donat status CAPA keseluruhan (Open, In Progress, Overdue). Ini adalah metrik yang paling disukai Manajemen Eksekutif.
## 5. API & Integration Requirements
 * **Endpoint:** GET /api/v1/capa/my-tasks (Mengambil tugas berdasarkan auth()->user()->id).
 * **Endpoint:** POST /api/v1/capa/{id}/evidence (Kirim foto bukti perbaikan via *multipart form data*).
 * **Endpoint:** PATCH /api/v1/capa/{id}/verify (HSE Officer menyetujui/menutup tugas).
## 6. AI Agent Engineering Directives
 * **Cron Job / Scheduler:** Agent AI harus membuat file Console/Kernel.php atau Laravel 11 Schedule definition untuk menjalankan *job* pengecekan CAPA *Overdue* setiap jam 00:00.
 * **Relationships:** Deklarasikan relasi morphTo() pada Model CorrectiveAction dengan sangat hati-hati agar tidak terjadi kesalahan referensi modul.
