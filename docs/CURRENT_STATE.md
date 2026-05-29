## Phase 1 Status
- [x] Task 1: Inisialisasi proyek Laravel 11. Konfigurasi PostgreSQL. (Codebase is Laravel 13, see deviations)
- [x] Task 2: Buat Migration untuk domain Identity & Tenancy (tenants, organizations, projects, sites, areas, users).
- [x] Task 3: Setup paket spatie/laravel-permission (modifikasi agar mendukung UUID).
- [x] Task 4: Buat TenantScope (Global Scope) dan trait BelongsToTenant.
## Milestone 1.2 Status
- [x] Task 1: Setup Laravel Sanctum untuk API Tokens.
- [x] Task 2: Buat AuthController dengan metode login dan proteksi *Rate Limiting*.
- [~] Task 3: Buat fitur *Audit Trail* sederhana untuk mencatat setiap proses login ke tabel auth_audit_logs. (Logs are written to audit_events table instead of auth_audit_logs)
- [x] Task 4: Buat Feature Test (PHPUnit/Pest) untuk memastikan Login gagal jika kredensial salah.

## Milestone 1.3 Status
- [ ] Task 1 (Web): Setup Next.js dengan Tailwind CSS.
- [ ] Task 2 (Mobile): Setup Flutter.
- [ ] Task 3 (Mobile): Buat mekanisme simpan Token.

## Phase 2 Status
## Milestone 2.1 Status
- [x] Task 1 (Backend): Buat endpoint POST /api/v1/sync/incidents dan hazards.
- [ ] Task 2 (Mobile): Buat tabel SQLite lokal sync_queue.
- [ ] Task 3 (Mobile): Buat *Background Worker* di Flutter.

## Milestone 2.2 Status
- [~] Task 1: Setup Laravel Horizon / Redis Queue. (Redis Queue is configured but Horizon is not clearly set up)
- [~] Task 2: Buat Laravel Job ProcessAudioToStructuredData. (ProcessIncidentDataJob and ProcessHazardDataJob exist instead)
- [x] Task 3: Simpan *Raw Payload* dari LLM ke tabel ai_runs, lalu ekstrak dan pindahkan ke tabel incidents atau hazard_observations dengan status draft_ready.

## Milestone 2.3 Status
- [ ] Task 1 (Mobile): Buat UI "Smart Capture".
- [ ] Task 2 (Web): Buat tabel Triage Dashboard.
- [ ] Task 3 (Web): Buat form *Review*.

## Phase 3 Status
## Milestone 3.1 Status
- [ ] Task 1 (Backend): Buat skema dan API untuk inspection_templates dan inspections.
- [ ] Task 2 (Mobile): UI Eksekusi Inspeksi.

## Milestone 3.2 Status
- [~] Task 1 (Backend): Buat endpoint pembuatan JSA yang kompleks. (Jsa models exist, and tables, but no explicit endpoint in routes/api.php for creation of JSA)
- [x] Task 2 (Backend): Buat logika *Digital Signature* & rantai persetujuan (permit_approvals).
- [ ] Task 3 (Web & Mobile): UI Pengajuan Izin Kerja.

## Milestone 3.3 Status
- [x] Task 1 (Backend): Buat Model CorrectiveAction dengan relasi polimorfik (source_type, source_id).
- [x] Task 2 (Backend): Buat Laravel Scheduler (Cron) yang mengecek CAPA setiap jam 00:00 dan mengirim email teguran jika statusnya *Overdue*.
- [ ] Task 3 (Mobile): Buat fitur "Tugas Saya".

## Phase 4 Status
## Milestone 4.1 Status
- [~] Task 1: API dan UI untuk mengunggah dan melacak tanggal kedaluwarsa certifications. (Certification model exists, tests exist, API not clearly defined in api.php)
- [ ] Task 2: Buat fitur *Scanner* di Flutter.

## Milestone 4.2 Status
- [ ] Task 1 (Backend): Konfigurasi ekstensi pgvector di PostgreSQL.
- [ ] Task 2 (Backend): Buat alur RAG.
- [ ] Task 3 (Backend & Web): Integrasi AI Agent 4.2 dan UI Floating Chat.

## Milestone 4.3 Status
- [ ] Task 1 (Backend): Buat endpoint agregasi data.
- [ ] Task 2 (Web): Buat komponen *Chart*.
- [ ] Task 3 (DevOps): Susun Dockerfile dan CI/CD.

## Known Deviations from Devplan
- Codebase uses Laravel 13 instead of Laravel 11.
- Audit trail logs login events to `audit_events` instead of a separate `auth_audit_logs` table.
- AI Structuring pipeline uses `ProcessIncidentDataJob` and `ProcessHazardDataJob` instead of `ProcessAudioToStructuredData`.
- No frontend applications (Next.js web, Flutter mobile) are present in the repository (`apps/web` and `apps/mobile` are missing).

## File Inventory

### Migrations
- 0001_01_01_000001_create_cache_table.php
- 0001_01_01_000002_create_jobs_table.php
- 2026_05_28_125658_create_permission_tables.php
- 2026_05_28_125710_create_tenants_table.php
- 2026_05_28_125713_create_app_users_table.php
- 2026_05_28_125716_create_organization_units_table.php
- 2026_05_28_125719_create_sites_table.php
- 2026_05_28_125722_create_projects_table.php
- 2026_05_28_125725_create_audit_events_table.php
- 2026_05_28_125738_create_personal_access_tokens_table.php
- 2026_05_28_190121_create_attachment_links_table.php
- 2026_05_28_190121_create_attachments_table.php
- 2026_05_28_190122_create_ai_runs_table.php
- 2026_05_29_091641_create_incidents_table.php
- 2026_05_29_091645_create_hazard_observations_table.php
- 2026_05_29_133203_add_timezone_to_sites_and_projects_table.php
- 2026_05_29_133203_create_corrective_actions_table.php
- 2026_05_29_133418_create_corrective_action_updates_table.php
- 2026_05_29_145436_create_ptw_and_jsa_tables.php
- 2026_05_29_194546_create_contractor_compliance_tables.php

### Models
- AiRun.php
- AppUser.php
- Attachment.php
- AttachmentLink.php
- AuditEvent.php
- Certification.php
- CorrectiveAction.php
- CorrectiveActionUpdate.php
- HazardObservation.php
- Incident.php
- Jsa.php
- JsaControl.php
- JsaHazard.php
- JsaTask.php
- OrganizationUnit.php
- Permission.php
- PermitApproval.php
- PermitToWork.php
- PermitType.php
- PermitWorker.php
- Project.php
- Role.php
- Site.php
- Tenant.php
- Worker.php

### Controllers
- ApiController.php
- AuthController.php
- Controller.php
- Api/V1/AttachmentController.php
- Api/V1/CapaController.php
- Api/V1/HazardController.php
- Api/V1/IncidentController.php
- Api/V1/JsaController.php
- Api/V1/PermitToWorkController.php
- Api/V1/SyncController.php

### Jobs
- CheckOverdueCapaJob.php
- ProcessHazardDataJob.php
- ProcessIncidentDataJob.php

### Tests
- Feature/AiStructuringPipelineTest.php
- Feature/PermitToWorkFeatureTest.php
- Feature/ApiResponseTest.php
- Feature/IncidentDashboardTest.php
- Feature/CapaFeatureTest.php
- Feature/TenantScopeTest.php
- Feature/HazardDashboardTest.php
- Feature/AuthTest.php
- Feature/ContractorComplianceTest.php
- Feature/CheckOverdueCapaJobTest.php
- Feature/ExampleTest.php
- Feature/Api/V1/SyncTest.php
- Feature/Api/V1/AttachmentTest.php
- TestCase.php
- Unit/ExampleTest.php
