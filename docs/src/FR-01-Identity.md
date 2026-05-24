# FR-01: Identity, Authentication & RBAC
**Module:** Core Platform
**Related Schemas:** users, roles, permissions, auth_audit_logs
## 1. Module Overview
Modul ini menangani proses autentikasi (Login/Logout), manajemen sesi pengguna, dan kontrol hak akses berbasis peran (Role-Based Access Control / RBAC) di seluruh platform (Mobile & Web). Modul ini merupakan garis pertahanan pertama keamanan sistem.
## 2. Actors & Roles
 * **Mobile Users (Safetyman, Inspector, Field Worker):** Menggunakan token (Sanctum/JWT) yang berumur panjang (long-lived) untuk sesi mobile.
 * **Web Users (Officer, Manager, Admin):** Menggunakan sesi web standar atau token dengan *idle-timeout*.
 * **System Administrator:** Dapat mengatur *Roles* dan melakukan *Assign Permissions* kepada pengguna lain.
## 3. Business Logic & Rules
 1. **Authentication:**
   * Login menggunakan email dan password.
   * Endpoint login harus mencatat *device_name* (Mobile) atau *user_agent* (Web).
   * Password *hashing* menggunakan algoritma bawaan Laravel (Bcrypt/Argon2).
 2. **RBAC (Role-Based Access Control):**
   * Menggunakan pendekatan standar paket spatie/laravel-permission.
   * Peran bersifat dinamis namun peran inti (admin, hse_officer, safetyman) dikunci (*seeded*) secara default di setiap *tenant*.
 3. **Audit Trail (Mandatory):**
   * Setiap upaya login (berhasil maupun gagal), *logout*, dan perubahan *password* HARUS dicatat secara *immutable* di tabel auth_audit_logs.
## 4. UI/UX Flows
### 4.1 Mobile Flow (Flutter)
 * **Login Screen:** Input Email & Password. Menampilkan indikator loading.
 * **Offline Auth:** Jika pengguna tidak memiliki internet namun token masih tersimpan dan *valid* di *Secure Storage*, izinkan masuk ke *Offline Dashboard*. Jika token kadaluarsa, tendang ke *Login Screen*.
 * **Biometric (Optional Phase 2):** Dukungan FaceID/Fingerprint untuk mempercepat login harian.
### 4.2 Web Flow (Next.js)
 * **Login Screen:** Input standar.
 * **User Management Page (Admin):** Tabel CRUD untuk menambahkan User baru, menetapkan *Role*, dan mereset password.
## 5. API & Integration Requirements
 * **Endpoint:** POST /api/v1/auth/login (Menerima kredensial, mengembalikan token + data *user*).
 * **Endpoint:** POST /api/v1/auth/logout (Mencabut token).
 * **Endpoint:** GET /api/v1/auth/me (Mendapatkan data *profile*, *tenant_id*, dan array *permissions* aktif).
## 6. AI Agent Engineering Directives (For Jules/Claude)
 * **Backend:** JANGAN membuat *middleware* RBAC sendiri. Gunakan $user->hasRole() atau *middleware* bawaan Spatie.
 * **Security:** Terapkan *Rate Limiting* pada *endpoint* login (maksimal 5 percobaan per menit per IP) untuk mencegah *brute-force*.
