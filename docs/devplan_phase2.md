# Development Plan: Phase 2 (MVP Field Ops & AI Bridge)
**Project:** Aegis AI EHS Platform
**Goal:** Membangun fitur unggulan (Wedge Strategy) yaitu pelaporan Insiden & Bahaya berbasis *Offline-First* dan diproses oleh AI di *background*.
## ⚠️ Source of Truth
 * @src/FR-03-SyncEngine.md, @src/FR-04-Incident.md, @src/FR-05-Hazard.md
 * @api-contracts.md (Offline-First Sync Engine)
 * @agents.md (Agent 1 & Agent 2)
## Milestone 2.1: The Offline Sync Engine (Mobile & Backend)
**Target:** Aplikasi mobile bisa menyimpan data saat internet mati dan mengirimnya perlahan saat online.
 * [x] **Task 1 (Backend):** Buat endpoint POST /api/v1/sync/incidents dan hazards. Pastikan mengembalikan status 202 Accepted HANYA setelah file tersimpan lokal dan Job masuk ke Queue.
 * [ ] **Task 2 (Mobile):** Buat tabel SQLite lokal sync_queue.
 * [ ] **Task 3 (Mobile):** Buat *Background Worker* di Flutter yang mendeteksi koneksi internet, mengambil antrean di SQLite, dan mengirimkannya ke Backend via *multipart/form-data*.
## Milestone 2.2: AI Structuring Pipeline (Backend)
**Target:** Integrasi LLM (OpenAI/Anthropic) yang berjalan di *background*.
 * [~] **Task 1:** Setup Laravel Horizon / Redis Queue.
 * [~] **Task 2:** Buat Laravel Job ProcessAudioToStructuredData.
   * *Aturan:* Gunakan instruksi *Prompt* dari @agents.md. Pastikan response_format diset ke json_object.
 * [x] **Task 3:** Simpan *Raw Payload* dari LLM ke tabel ai_runs, lalu ekstrak dan pindahkan ke tabel incidents atau hazard_observations dengan status draft_ready.
## Milestone 2.3: Operational UI (Web & Mobile)
**Target:** Pengguna bisa merekam data dan memvalidasi hasil AI.
 * [ ] **Task 1 (Mobile):** Buat UI "Smart Capture" (Tombol Rekam Suara besar & Kamera).
 * [ ] **Task 2 (Web):** Buat tabel Triage Dashboard untuk menampilkan laporan yang berstatus draft_ready.
 * [ ] **Task 3 (Web):** Buat form *Review* bagi HSE Officer untuk membaca hasil *parsing* AI, mengedit jika salah, dan menekan "Submit" (Ubah status ke under_review).
