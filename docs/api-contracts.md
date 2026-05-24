# Enterprise API Contracts & Integration Spec
**Project:** Aegis AI EHS Platform
**Version:** 1.0 (Full Enterprise Scope)
## 1. Global API Engineering Standards (Strict Enforcement)
These rules apply to ALL AI Agents generating backend or frontend networking code.
 1. **Format:** All requests and responses MUST use application/json. File uploads MUST use multipart/form-data.
 2. **Authentication:** All endpoints (except public/auth) require a Bearer Token (Authorization: Bearer {token}).
 3. **Tenant Scoping:** The tenant_id is STRICTLY derived from the authenticated user's token/session. **Never** trust a tenant_id sent in a client request payload.
 4. **Client-Side UUIDs:** For all creation requests (POST), the client (Mobile/Web) MUST generate and send the id (UUID). This enables offline creation and idempotency.
 5. **Idempotency:** Critical POST endpoints (like approvals, submissions) should support an Idempotency-Key header to prevent double execution on network retries.
 6. **Pagination:** List endpoints (GET) MUST use Cursor-based or standard Offset pagination, wrapped in a meta object.
 7. **Response Wrapper:** All responses MUST follow the standard JSend format.
### Standard Response Wrappers
**Success Response (200 OK / 201 Created / 202 Accepted):**
```json
{
  "status": "success",
  "data": { ... },
  "message": "Optional human-readable message",
  "meta": { 
    "current_page": 1, 
    "last_page": 5, 
    "total": 120 
  } // Only for paginated GET requests
}

```
**Error Response (4xx, 5xx):**
```json
{
  "status": "error",
  "message": "Human readable error description",
  "code": "DOMAIN_ERROR_CODE",
  "errors": {
    "field_name": ["Validation error detail"]
  },
  "correlation_id": "uuid-for-log-tracing"
}

```
## 2. Authentication & Identity Domain
### POST /api/v1/auth/login
**Purpose:** Authenticate user and retrieve JWT/Sanctum token.
**Payload:** email, password, device_name.
### GET /api/v1/auth/me
**Purpose:** Retrieve current user profile, roles, permissions, and active tenant context.
## 3. Offline-First Sync Engine (AI Bridge)
*These endpoints handle data captured in offline environments. They save raw data, dispatch to RabbitMQ/Redis, and return 202 Accepted.*
### POST /api/v1/sync/incidents
### POST /api/v1/sync/hazards
**Purpose:** Sync offline operational data.
**Content-Type:** multipart/form-data
**Payload Requirements:**
 * id (UUID, Required)
 * audio_evidence (File, Optional)
 * photo_evidence[] (Array of Files, Optional)
 * Raw metadata (location, timestamp, offline text notes).
**Success Response (202 Accepted):**
```json
{
  "status": "success",
  "message": "Data synced and queued for AI processing",
  "data": { "id": "uuid", "sync_status": "processing" }
}

```
### GET /api/v1/sync/status
**Purpose:** Polling endpoint for mobile app to check if AI processing is complete for a batch of UUIDs.
## 4. Permit To Work (PTW) Domain
### POST /api/v1/permits
**Purpose:** Create a new Permit To Work draft.
**Content-Type:** application/json
**Payload:**
```json
{
  "id": "uuid",
  "permit_type_id": "uuid",
  "site_id": "uuid",
  "work_location": "Blok C, Tangki 04",
  "work_scope": "Pengelasan pipa flange",
  "planned_start": "2026-05-25T08:00:00Z",
  "planned_end": "2026-05-25T17:00:00Z",
  "controls": [
    { "control_type": "gas_test", "description": "Uji LEL sebelum mulai" },
    { "control_type": "fire_watch", "description": "Sediakan APAR dan fire watcher" }
  ]
}

```
### POST /api/v1/permits/{id}/approve
**Purpose:** Digital signature/approval by Supervisor/HSE.
**Payload:**
```json
{
  "decision": "approved", // or "rejected"
  "comments": "Pastikan fire blanket digelar",
  "signature_attachment_id": "uuid" // Optional digital signature image
}

```
## 5. Job Safety Analysis (JSA) Domain
### POST /api/v1/jsa
**Purpose:** Create a new JSA, often linked to a PTW.
**Payload:**
```json
{
  "id": "uuid",
  "permit_id": "uuid (optional)",
  "job_scope": "Pengangkatan material dengan crane",
  "tasks": [
    {
      "task_sequence": 1,
      "task_description": "Memasang lifting gear",
      "hazards": [
        {
          "hazard_description": "Tangan terjepit sling",
          "control_description": "Gunakan sarung tangan katun tebal, komunikasi 2 arah",
          "residual_risk_score": "low"
        }
      ]
    }
  ]
}

```
## 6. CAPA (Corrective & Preventive Action) Domain
### POST /api/v1/capa
**Purpose:** Create a corrective action originating from an Incident, Audit, or Hazard.
**Payload:**
```json
{
  "id": "uuid",
  "source_type": "incident", // Polymorphic type
  "source_id": "uuid", // Polymorphic ID
  "title": "Perbaikan pagar pengaman perancah",
  "description": "Ganti pagar yang keropos di lantai 3",
  "assigned_to_user_id": "uuid",
  "due_date": "2026-05-30",
  "priority_level": "high"
}

```
### PATCH /api/v1/capa/{id}/status
**Purpose:** Update CAPA progress or submit for verification.
**Payload:**
```json
{
  "workflow_status": "pending_verification",
  "update_notes": "Pagar sudah diganti dengan pipa galvanis baru",
  "evidence_attachment_ids": ["uuid"]
}

```
## 7. Audit & Compliance Domain
### POST /api/v1/audits/{id}/findings
**Purpose:** Log a finding (Non-Conformance/Observation) during an active audit.
**Payload:**
```json
{
  "id": "uuid",
  "finding_type": "non_conformance",
  "severity_level": "major",
  "description": "Pekerja di area B tidak menggunakan full body harness",
  "checklist_item_id": "uuid (optional)"
}

```
## 8. AI Intelligence & Copilot Domain
### POST /api/v1/ai/copilot/query
**Purpose:** Ask the AI Copilot a contextual question (RAG - Retrieval Augmented Generation).
**Payload:**
```json
{
  "query": "Apa prosedur bekerja di ruang terbatas sesuai SOP perusahaan?",
  "context_domain": "compliance" // guides which vector DB index to search
}

```
**Response:**
```json
{
  "status": "success",
  "data": {
    "answer": "Berdasarkan SOP-HSE-004, bekerja di ruang terbatas mewajibkan:\n1. Adanya Gas Test...\n2. Surat Izin Kerja Aman (PTW)...",
    "sources": [
      { "document_id": "uuid", "title": "SOP-HSE-004 Confined Space" }
    ],
    "ai_confidence_score": 95
  }
}

```
### POST /api/v1/ai/permits/draft
**Purpose:** AI assists in drafting PTW controls based on job description.
**Payload:**
```json
{
  "job_description": "Melakukan pengelasan di dalam tangki bahan bakar yang sudah dikosongkan"
}

```
**Response:**
```json
{
  "status": "success",
  "data": {
    "suggested_permit_type": "hot_work_confined_space",
    "suggested_controls": [
      "Purging dan ventilasi minimal 24 jam",
      "Gas test LEL, O2, H2S, CO setiap 2 jam",
      "Sediakan standby person / fire watcher di luar manhole"
    ]
  }
}

```
