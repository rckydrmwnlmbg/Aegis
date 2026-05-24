# FR-02: Organization & Tenancy (Multi-Tenant Architecture)
**Module:** Core Platform
**Related Schemas:** tenants, organizations, projects, sites, areas
## 1. Module Overview
Modul ini memastikan bahwa data antar perusahaan klien (Tenant) terisolasi dengan ketat secara logikal (*Logical Separation*), serta memetakan hierarki proyek dan lokasi fisik di lapangan (Sites/Areas) agar pelaporan insiden memiliki konteks geografis yang tepat.
## 2. Actors & Roles
 * **Platform Super Admin:** Memiliki akses ke sistem *root* untuk mendaftarkan tenants baru (Perusahaan Klien).
 * **Tenant Admin:** Mengelola projects, sites, dan areas di dalam perusahaannya sendiri.
 * **Mobile User:** Menerima data daftar projects dan sites untuk dipilih saat membuat laporan (Dropdown tersinkronisasi).
## 3. Business Logic & Rules
 1. **Tenant Isolation (Critical Rule):**
   * Semua *query* operasional (seperti Incident::all()) **TIDAK BOLEH** digunakan tanpa *scoping*.
   * Wajib menggunakan Laravel Global Scope (misal: TenantScope) untuk menyisipkan where('tenant_id', auth()->user()->tenant_id) ke setiap *query* ORM.
 2. **Hierarchy Enforcement:**
   * Area harus terikat ke Site.
   * Site harus terikat ke Project atau Organization.
 3. **Soft Deletes:**
   * Menghapus sebuah Site tidak menghapus data secara fisik dari tabel, melainkan mengubah kolom deleted_at. Laporan masa lalu yang terikat dengan Site tersebut harus tetap dapat dirender dengan benar.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter)
 * **Master Data Sync:** Saat *online* atau saat buka aplikasi, *Mobile* menarik daftar projects dan sites terbaru dan menyimpannya di SQLite lokal agar *Safetyman* tetap bisa memilih lokasi kejadian saat *offline*.
### 4.2 Web Flow (Next.js)
 * **Hierarchy Tree View:** Admin dapat melihat hierarki Perusahaan -> Proyek -> Site -> Area dalam bentuk pohon (*tree view*) yang intuitif.
## 5. API & Integration Requirements
 * **Endpoint:** GET /api/v1/reference/sites (Mengembalikan daftar *site* milik *tenant* yang sedang *login*).
 * **Endpoint:** GET /api/v1/reference/projects (Mengembalikan daftar proyek aktif).
## 6. AI Agent Engineering Directives
 * **Backend:** Buatkan Trait bernama BelongsToTenant yang secara otomatis mengaplikasikan TenantScope di *boot method*-nya, dan secara otomatis mengisi tenant_id pada saat creating *event*. Aplikasikan trait ini ke semua Model operasional.
