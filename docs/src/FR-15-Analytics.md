# FR-15: Executive Analytics Dashboard
**Module:** Intelligence & Governance
**Related Schemas:** telemetry_events, metric_snapshots, KPI_fact_records
## 1. Module Overview
Modul "Layar Kaca" bagi pimpinan eksekutif (*HSE Manager, Project Manager, Corporate Director*). Menerjemahkan ribuan baris data insiden, temuan audit, dan kepatuhan CAPA menjadi visualisasi grafik (KPI) *real-time* yang dapat ditindaklanjuti.
## 2. Actors & Roles
 * **HSE Manager & Executive:** Pengguna utama. Hanya butuh melihat data, tidak melakukan *input* data harian.
 * **Supervisor:** Melihat *dashboard* dengan cakupan yang terbatas hanya pada proyek/situs (*Site*) miliknya saja.
## 3. Business Logic & Rules
 1. **Dynamic Data Scoping (ABAC):**
   * Tampilan grafik akan otomatis menyesuaikan wewenang. Manajer Pusat melihat statistik gabungan 10 Proyek. Manajer Proyek A hanya melihat grafik Proyek A.
 2. **Key Performance Indicators (KPIs):**
   * Total Insiden (Dikelompokkan berdasarkan LTI, Near Miss, dll).
   * CAPA Overdue Rate (Persentase tindakan perbaikan yang terlambat).
   * Contractor Safety Score (Papan Peringkat / *Leaderboard* kepatuhan vendor subkon).
   * Audit Closure Rate.
 3. **Data Aggregation Strategy:**
   * Untuk mencegah *database* transaksional utama kelebihan beban (lemot) saat merender grafik, grafik berfokus pada data metric_snapshots yang diagregasi melalui *Cron Job* secara periodik, bukan melalui kueri COUNT(*) secara *real-time* setiap kali halaman di- *refresh*.
## 4. UI/UX Flows
### 4.1 Web Flow (Next.js)
 * **Grid Layout:** Papan instrumen (*Dashboard*) berbasis *Card* & *Widget* yang responsif.
 * **Interactive Charts:** Menggunakan pustaka *charting* (seperti Recharts atau Chart.js). Pengguna dapat mengklik grafik pie "Near Miss" untuk langsung melompat ke tabel data mentah (*Drill-down navigation*).
 * **Export to PDF:** Tombol sekali klik untuk mengekspor tampilan *dashboard* saat ini menjadi Laporan PDF Mingguan/Bulanan (digunakan untuk *Management Meeting*).
## 5. API & Integration Requirements
 * **Endpoint:** GET /api/v1/analytics/kpi-summary (Mengambil agregasi data level atas).
 * **Endpoint:** GET /api/v1/analytics/trends/incidents (Data berkala/time-series untuk *Line Chart*).
## 6. AI Agent Engineering Directives
 * **Backend (Performance):** Terapkan *Caching Layer* (Redis) yang kuat untuk semua *endpoint* analitik dengan TTL (Time To Live) misal 15 menit. JANGAN menjalankan *query* agregasi SQL berat secara langsung setiap kali *endpoint* dipanggil.
 * **Frontend (Next.js):** Pastikan komponen *Dashboard* di- *fetch* secara paralel (*Promise.all* atau React Suspense) agar pemuatan satu grafik lambat tidak memblokir render seluruh halaman.
