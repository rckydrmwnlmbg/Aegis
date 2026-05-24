# Development Plan: Phase 1 (Foundation & Core Identity)
**Project:** Aegis AI EHS Platform
**Goal:** Membangun fondasi database, arsitektur Multi-Tenant, dan autentikasi dasar untuk API dan Web/Mobile.
## ⚠️ Source of Truth (Wajib Dibaca AI Sebelum Eksekusi)
 * @project_context.md (Aturan arsitektur global)
 * @schema.md (Kamus tipe data & tabel)
 * @design-system.md (Standar UI/UX)
## Milestone 1.1: Database Setup & Core Migrations (Backend)
**Target:** Skema database inti berdiri tegak dengan UUID dan Tenant Isolation.
 * [ ] **Task 1:** Inisialisasi proyek Laravel 11. Konfigurasi PostgreSQL.
 * [ ] **Task 2:** Buat Migration untuk domain Identity & Tenancy (tenants, organizations, projects, sites, areas, users).
   * *Aturan:* Rujuk @schema.md Bagian 1. Wajib pakai UUID.
 * [ ] **Task 3:** Setup paket spatie/laravel-permission (modifikasi agar mendukung UUID).
 * [ ] **Task 4:** Buat TenantScope (Global Scope) dan trait BelongsToTenant.
## Milestone 1.2: Authentication & RBAC API (Backend)
**Target:** Endpoint API untuk Login, Logout, dan Pengambilan Data User selesai.
 * [ ] **Task 1:** Setup Laravel Sanctum untuk API Tokens.
 * [ ] **Task 2:** Buat AuthController dengan metode login dan proteksi *Rate Limiting*.
   * *Aturan:* Rujuk @api-contracts.md (Authentication Domain) dan @src/FR-01-Identity.md.
 * [ ] **Task 3:** Buat fitur *Audit Trail* sederhana untuk mencatat setiap proses login ke tabel auth_audit_logs.
 * [ ] **Task 4:** Buat Feature Test (PHPUnit/Pest) untuk memastikan Login gagal jika kredensial salah. (*Rujuk @testing-strategy.md*).
## Milestone 1.3: Frontend Boilerplate (Web & Mobile)
**Target:** Proyek Flutter dan Next.js terinisialisasi dengan struktur folder dan State Management dasar.
 * [ ] **Task 1 (Web):** Setup Next.js dengan Tailwind CSS. Buat halaman Login dan layout Dashboard dasar dengan Sidebar. Gunakan warna dari @design-system.md.
 * [ ] **Task 2 (Mobile):** Setup Flutter. Konfigurasi sqflite (Database lokal) dan dio (HTTP Client). Buat halaman Login Screen.
 * [ ] **Task 3 (Mobile):** Buat mekanisme simpan Token ke *Secure Storage* dan logika *redirect* ke Dashboard jika token masih valid.
