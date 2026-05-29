# Development Plan: Phase 4 (Enterprise AI & Executive Polish)
**Project:** Aegis AI EHS Platform
**Goal:** Menyelesaikan modul Kepatuhan, mengintegrasikan RAG Copilot, dan merilis visualisasi Dashboard tingkat eksekutif.
## ⚠️ Source of Truth
 * @src/FR-10-Audit.md, @src/FR-11-Contractor.md, @src/FR-14-AI-Copilot.md, @src/FR-15-Analytics.md
 * @devops-infrastructure.md
## Milestone 4.1: Contractor & Compliance (Backend & Web)
**Target:** Memastikan alat berat dan pekerja bersertifikat.
 * [~] **Task 1:** API dan UI untuk mengunggah dan melacak tanggal kedaluwarsa certifications.
 * [ ] **Task 2:** Buat fitur *Scanner* di Flutter untuk membaca QR Code ID Card pekerja.
## Milestone 4.2: AI Copilot & RAG Setup (Backend & Web)
**Target:** Chatbot interaktif untuk menanyakan SOP perusahaan.
 * [ ] **Task 1 (Backend):** Konfigurasi ekstensi pgvector di PostgreSQL.
 * [ ] **Task 2 (Backend):** Buat alur RAG: terima file PDF (SOP), pecah (*chunking*), dan simpan *embeddings* (vektor) dengan batas tenant_id.
 * [ ] **Task 3 (Backend & Web):** Integrasi AI Agent 4.2 dari @agents.md dan buat UI *Floating Chat* di *Dashboard* Web.
## Milestone 4.3: Analytics Dashboard & Deployment (Web & DevOps)
**Target:** Visualisasi data pimpinan dan peluncuran produksi.
 * [ ] **Task 1 (Backend):** Buat endpoint agregasi data (KPI Insiden, CAPA Rate) dengan lapisan *Cache* Redis agar tidak membebani database utama.
 * [ ] **Task 2 (Web):** Buat komponen *Chart* (Pie, Bar, Line) di Next.js menggunakan *Recharts*.
 * [ ] **Task 3 (DevOps):** Susun Dockerfile untuk Backend dan Web Frontend. Konfigurasi CI/CD Pipeline (GitHub Actions) sesuai @devops-infrastructure.md.
