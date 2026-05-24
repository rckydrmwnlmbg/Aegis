# FR-05: Hazard & Near Miss Reporting
**Module:** Operational Safety
**Related Schemas:** hazard_observations, near_miss_reports, corrective_actions
## 1. Module Overview
Modul ini merupakan inti dari K3 Proaktif. Mendorong pekerja untuk melaporkan Kondisi Tidak Aman (*Unsafe Condition*), Tindakan Tidak Aman (*Unsafe Act*), dan Kejadian Nyaris Celaka (*Near Miss*) secepat mungkin sebelum berubah menjadi insiden nyata.
## 2. Actors & Roles
 * **All Field Users:** Dapat melaporkan Hazard dan Near Miss. Opsi pelaporan anonim (*Anonymous Reporting*) tersedia untuk Near Miss.
 * **HSE Supervisor/Officer:** Menerima laporan, menilai tingkat risiko (*Risk Rating*), dan menugaskan Tindakan Perbaikan (*Corrective Action* / CAPA).
 * **AI Agent (Classifier):** Menentukan kategori bahaya dan menyarankan CAPA.
## 3. Business Logic & Rules
 1. **Separation of Concerns:** - Meskipun secara UI mungkin berada di satu tombol "Lapor Bahaya", AI Intent Router harus memisahkan apakah data masuk ke tabel hazard_observations atau near_miss_reports berdasarkan analisis teks/suara.
 2. **Anonymous Near Miss:**
   * Jika pengguna memilih "Lapor Anonim", sistem TIDAK MENCATAT reporter_user_id di *database*. Peringatan: hal ini tidak bisa dibatalkan untuk melindungi pelapor.
 3. **Risk Scoring (Matrix):**
   * Sistem menggunakan matriks risiko standar (Misal: *Likelihood* x *Consequence* = *Risk Rating*).
   * AI dapat mengusulkan skor awal, namun Officer berhak mengubahnya secara final.
 4. **Direct to CAPA:**
   * Setiap *Hazard* berisiko *High/Extreme* **wajib** menghasilkan minimal 1 tiket di tabel corrective_actions (CAPA) sebelum bisa ditutup (*Closed*).
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter)
 * **Quick Hazard Screen:** UI berfokus pada foto. Foto -> Rekam Suara (Misal: "Ada tumpahan oli di dekat genset blok B") -> Simpan. Target waktu pembuatan laporan < 30 detik.
### 4.2 Web Flow (Next.js)
 * **Triage Dashboard:** Tampilan daftar laporan bahaya baru. Officer bisa mem- *filter* berdasarkan tingkat keparahan yang disarankan AI.
 * **CAPA Assignment Modal:** *Pop-up* cepat untuk meneruskan temuan bahaya ke PIC terkait (Misal: "Tugaskan Tim Mekanik untuk bersihkan oli").
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/sync/hazards.
 * **Endpoint:** POST /api/v1/sync/near_misses.
 * **Endpoint:** POST /api/v1/hazards/{id}/capa (Mengubah bahaya menjadi tindakan perbaikan).
## 6. AI Agent Engineering Directives
 * **Prompting:** AI harus sangat teliti membedakan antara "Tindakan" (pekerja merokok di area dilarang) dan "Kondisi" (kabel mesin terkelupas).
