# FR-08: Job Safety Analysis (JSA / JHA)
**Module:** Work Governance
**Related Schemas:** jsa_documents, jsa_tasks, jsa_hazards
## 1. Module Overview
Menyediakan *tools* untuk merencanakan keselamatan langkah demi langkah sebelum pekerjaan berisiko dimulai. Modul ini memungkinkan pengguna menguraikan pekerjaan menjadi beberapa langkah (*Tasks*), mengidentifikasi bahaya (*Hazards*) pada tiap langkah, dan menetapkan kontrol (*Controls/PPE*).
## 2. Actors & Roles
 * **Engineer/Supervisor:** Pembuat dokumen JSA.
 * **HSE Officer:** Peninjau dan penyetuju (Approver) JSA.
 * **AI Agent (JSA Assistant):** Membantu memecah tugas secara logis jika pengguna bingung menyusun urutan JSA.
## 3. Business Logic & Rules
 1. **Hierarchical Data Structure:**
   * Satu JSA memiliki banyak *Tasks* (Satu-ke-Banyak).
   * Satu *Task* memiliki banyak *Hazards* (Satu-ke-Banyak).
   * Struktur data *nested* (bersarang) ini menuntut kehati-hatian ekstra saat *Create* atau *Update* via API.
 2. **Residual Risk Calculation:**
   * Tiap bahaya awalnya memiliki skor risiko awal (Inherent Risk).
   * Setelah menetapkan *Control*, pengguna harus mengevaluasi ulang menjadi skor Sisa Risiko (Residual Risk). JSA tidak boleh disetujui jika *Residual Risk* masih berstatus "High/Extreme".
 3. **Template Reusability:**
   * JSA yang sering digunakan (misal: JSA Pengelasan Pipa) harus bisa di-*copy/clone* menjadi dokumen baru agar Supervisor tidak perlu mengetik ulang semuanya dari awal.
## 4. UI/UX Flows
### 4.1 Web Flow (Pembuatan Utama)
 * **Matrix Builder Interface:** UI berbentuk tabel dinamis seperti Excel. Pengguna bisa "+ Tambah Langkah" dan "+ Tambah Bahaya".
 * **AI Generator Button:** Terdapat opsi "Auto-Generate Draft with AI" di mana pengguna cukup memasukkan "Pekerjaan: Membersihkan Kaca Gedung Bertingkat".
### 4.2 Mobile Flow (Eksekusi & Sosialisasi)
 * **Read-Only Viewer:** Di lapangan, Safetyman menggunakan HP untuk melihat JSA yang terikat dengan PTW aktif guna memastikan pekerja menerapkan kontrol yang tertulis (Misal: Apakah *full body harness* benar-benar dipakai?).
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/jsa (Menerima *Payload JSON* kompleks/nested yang berisi *Header*, array *Tasks*, dan array *Hazards*).
 * **Endpoint:** POST /api/v1/jsa/{id}/clone (Duplikasi JSA yang ada).
## 6. AI Agent Engineering Directives
 * **Backend (Transaction):** Saat menerima JSON JSA yang kompleks, Agent AI yang menulis *Controller* di Laravel WAJIB membungkus proses *Insert* JSA Header, JSA Tasks, dan JSA Hazards ke dalam satu DB::transaction(). Jika salah satu gagal, semuanya harus di- *rollback* agar tidak ada data yatim-piatu (*orphan data*).
