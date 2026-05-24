# Development Plan: Phase 3 (Work Governance & Compliance)
**Project:** Aegis AI EHS Platform
**Goal:** Membangun birokrasi digital untuk Izin Kerja (PTW), Analisa Keselamatan (JSA), dan Tindakan Perbaikan (CAPA).
## ⚠️ Source of Truth
 * @src/FR-07-PTW.md, @src/FR-08-JSA.md, @src/FR-09-CAPA.md, @src/FR-06-Inspection.md
## Milestone 3.1: Inspection & Checklist Engine
**Target:** Form dinamis yang bisa diisi *offline*.
 * [ ] **Task 1 (Backend):** Buat skema dan API untuk inspection_templates (Builder) dan inspections (Hasil eksekusi).
 * [ ] **Task 2 (Mobile):** UI Eksekusi Inspeksi (*swipe-based*) yang otomatis menyimpan ke SQLite setiap 1 soal terjawab.
## Milestone 3.2: Permit To Work (PTW) & JSA Workflows
**Target:** Sistem persetujuan berjenjang.
 * [ ] **Task 1 (Backend):** Buat endpoint pembuatan JSA yang kompleks (*Nested JSON: Task & Hazard*). Wajib gunakan DB::transaction.
 * [ ] **Task 2 (Backend):** Buat logika *Digital Signature* & rantai persetujuan (permit_approvals).
 * [ ] **Task 3 (Web & Mobile):** UI Pengajuan Izin Kerja dan Notifikasi Persetujuan untuk Manajer.
## Milestone 3.3: Universal CAPA Tracker
**Target:** Sistem pelacakan tindakan perbaikan dengan tenggat waktu.
 * [ ] **Task 1 (Backend):** Buat Model CorrectiveAction dengan relasi polimorfik (source_type, source_id).
 * [ ] **Task 2 (Backend):** Buat Laravel Scheduler (Cron) yang mengecek CAPA setiap jam 00:00 dan mengirim email teguran jika statusnya *Overdue*.
 * [ ] **Task 3 (Mobile):** Buat fitur "Tugas Saya" agar pekerja bisa mengunggah foto bukti perbaikan lapangan.
