# Aegis Delegation Engine
## Authority Delegation, Time-Bound Access & Contextual Override Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 1.0  
**Status:** Source of Truth  
**Classification:** Governance Architecture / Access Control  
**Authority Level:** Level 4 — Governance & Operational Truth  

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical delegation governance for Aegis.

It governs:

- authority delegation rules
- time-bound access mechanics
- delegation scope boundaries
- delegation audit requirements
- external auditor access provisioning
- emergency delegation override
- implementation delegation truth

---

## 1.2 Core Problem This Solves

Static role assignment cannot handle real-world HSE operational dynamics:

```
Problem 1: HSE Manager on leave — who approves PTW?
Problem 2: External auditor needs temporary read access
Problem 3: HSE Officer acting as site lead for one week
Problem 4: Contractor supervisor needs limited access for project duration
Problem 5: Emergency — authority must transfer immediately
```

Delegation Engine solves all five.

---

## 1.3 Delegation Doctrine

Delegation is explicit.

Delegation is time-bound.

Delegation is audited.

Delegation does not elevate beyond delegator's own authority.

Delegation does not transfer permanently.

---

# 2. Delegation Types

## 2.1 Type Definitions

```
TYPE 1 — Approval Delegation
TYPE 2 — Site Authority Delegation
TYPE 3 — External Access Provisioning
TYPE 4 — Emergency Override Delegation
TYPE 5 — Project-Scoped Contractor Access
```

---

## 2.2 Type 1 — Approval Delegation

### 2.2.1 Definition

Delegator temporarily transfers approval authority for specific document types to a delegate.

### 2.2.2 Use Case

```
HSE Manager (Budi) is on annual leave for 7 days.
He delegates PTW approval authority to:
HSE Officer Senior (Reza) for duration of leave.
```

### 2.2.3 Rules

Delegate must have Base Role of HSE_OFFICER or higher.

Delegate cannot re-delegate to a third party.

Delegation must have explicit start and end datetime.

All approvals made by delegate are recorded as:

```
Approved by: Reza Firmansyah
On behalf of: Budi Santoso (Delegation ID: DEL-2024-001)
```

Delegation expires automatically at end datetime.

No manual extension by delegate is permitted.

Extension requires new delegation by original delegator.

### 2.2.4 Scope Options

Delegator may scope by document type:

```
PTW_APPROVAL
JSA_APPROVAL
INCIDENT_APPROVAL
CAPA_CLOSURE
AUDIT_SIGN_OFF
```

Delegator may scope by site:

```
All sites
Specific site only
```

---

## 2.3 Type 2 — Site Authority Delegation

### 2.3.1 Definition

HSE Manager or HSE Officer temporarily transfers site-level governance authority to another HSE Officer.

### 2.3.2 Use Case

```
HSE Officer Karawang is temporarily reassigned to Batam site.
She delegates her Karawang site authority to junior HSE Officer
for 2 weeks.
```

### 2.3.3 Rules

Delegate receives full operational authority for the specified site.

Delegate does not receive cross-site authority.

Delegator retains notification access to the site during delegation period.

Site authority delegation must be visible to all HSE_MANAGER roles in the tenant.

---

## 2.4 Type 3 — External Access Provisioning

### 2.4.1 Definition

TENANT_ADMIN provisions read-only time-limited access for external parties.

### 2.4.2 Use Case

```
Disnaker auditor arriving for 3-day SMK3 audit.
TENANT_ADMIN creates AUDITOR_EXTERNAL account:
- Access: SMK3 documentation, incident reports, CAPA evidence
- Duration: 3 days
- Read-only: enforced
- Auto-expire: Day 3 at 23:59
```

### 2.4.3 Rules

Only TENANT_ADMIN may provision external access.

Access scope must be explicitly defined at provisioning time.

Default scope is empty — access must be granted, not revoked.

External access is always read-only. No exception.

All external access activity is logged to audit trail.

External account cannot be extended by the external user.

Extension requires TENANT_ADMIN action with documented reason.

### 2.4.4 Canonical External Access Types

```
DISNAKER_AUDITOR    — Regulatory audit access
SMK3_AUDITOR        — SMK3 certification audit access
BPJS_VERIFIER       — BPJS Ketenagakerjaan verification access
INSURANCE_AUDITOR   — Insurance compliance audit access
CLIENT_OBSERVER     — Client safety performance observer
```

---

## 2.5 Type 4 — Emergency Override Delegation

### 2.5.1 Definition

In declared emergency situations, HSE_MANAGER may immediately grant elevated operational authority to available personnel.

### 2.5.2 Use Case

```
Major incident at Site Cikampek at 02:00.
HSE Manager is unreachable.
Emergency Response Coordinator needs authority to:
- Activate emergency response protocol
- Issue immediate work stop orders
- Approve emergency equipment release
```

### 2.5.3 Rules

Emergency delegation requires emergency state to be active.

Emergency state must be declared through Aegis Emergency Management module.

Emergency delegation auto-expires when emergency state is closed.

Emergency delegation actions are logged with EMERGENCY flag.

Post-emergency: all emergency delegation actions must be reviewed and signed off by HSE_MANAGER within 24 hours.

---

## 2.6 Type 5 — Project-Scoped Contractor Access

### 2.6.1 Definition

Contractor company supervisor receives access scoped to their active project and team only.

### 2.6.2 Use Case

```
PT Bangun Jaya is contracted for warehouse construction.
Project duration: 3 months.
Their supervisor (Agus) needs access to:
- PTW submissions for their team
- Hazard observations in their work area
- Their workers' certification status
- Daily toolbox talk records

Agus cannot see:
- Other contractor data
- Internal company incidents
- Financial or commercial data
```

### 2.6.3 Rules

Project-scoped access expires at project end date.

Project end date is set by TENANT_ADMIN at onboarding.

Early termination of project must trigger immediate access revocation.

Contractor access is isolated per contractor company.

Cross-contractor data visibility is prohibited.

---

# 3. Delegation Data Model

## 3.1 Delegations Table

```
delegations
├── id (UUID)
├── tenant_id (UUID)
├── delegation_type (ENUM: approval|site|external|emergency|contractor)
├── delegator_id (UUID — user)
├── delegate_id (UUID — user)
├── scope_document_types (JSON array, nullable)
├── scope_site_ids (JSON array, nullable)
├── reason (text)
├── starts_at (timestamp)
├── expires_at (timestamp)
├── status (ENUM: active|expired|revoked)
├── revoked_at (timestamp, nullable)
├── revoked_by (UUID, nullable)
├── created_at
└── updated_at
```

## 3.2 Delegation Audit Log

```
delegation_audit_log
├── id (UUID)
├── delegation_id (UUID)
├── action (ENUM: created|activated|used|revoked|expired)
├── performed_by (UUID)
├── document_type (nullable — when action is 'used')
├── document_id (UUID, nullable)
├── metadata (JSON)
└── created_at
```

---

# 4. UI Implementation Rules

## 4.1 Delegation Indicator

When a user is operating under a delegation:

Dashboard must display a persistent banner:

```
⚠️ Anda sedang bertugas atas delegasi dari:
   Budi Santoso — PTW Approval | Site Karawang
   Berakhir: Jumat, 15 Nov 2024 pukul 17:00
```

Banner must not be dismissable.

Banner must appear on every page during delegation.

## 4.2 Approval Attribution

Every approval action made under delegation must display:

```
Disetujui oleh: Reza Firmansyah
Atas delegasi dari: Budi Santoso
ID Delegasi: DEL-2024-001
Waktu: 12 Nov 2024, 10:23 WIB
```

This attribution is permanent in the audit trail.

## 4.3 Delegation Management UI

TENANT_ADMIN and HSE_MANAGER may access:

```
Settings > Delegasi & Akses
├── Delegasi Aktif
├── Buat Delegasi Baru
├── Riwayat Delegasi
└── Akses Eksternal
```

Delegator may view and revoke their own active delegations.

Delegator may not modify active delegation scope — must revoke and recreate.

---

# 5. Notification Requirements

## 5.1 Delegation Created

Notify delegate:

```
"Anda mendapat delegasi [TYPE] dari [DELEGATOR].
Berlaku: [START] s/d [END].
Scope: [SCOPE SUMMARY]."
```

Notify HSE_MANAGER (if delegator is HSE_OFFICER):

```
"[DELEGATOR] membuat delegasi kepada [DELEGATE].
[SUMMARY]"
```

## 5.2 Delegation Expiring

Notify delegator and delegate 24 hours before expiry:

```
"Delegasi Anda akan berakhir dalam 24 jam."
```

## 5.3 Delegation Expired

Notify delegate immediately on expiry:

```
"Delegasi dari [DELEGATOR] telah berakhir.
Akses elevated telah dicabut secara otomatis."
```

## 5.4 Emergency Delegation

Notify HSE_MANAGER and TENANT_ADMIN immediately:

```
"Emergency delegation activated.
[DELEGATE] has been granted emergency authority at [SITE].
Emergency ID: [ID]"
```

---

# 6. Implementation Constraints for Jules

## 6.1 Critical Rules

```
Rule 1: Delegation scope check must occur at API middleware level,
        not at controller level only.

Rule 2: Expired delegations must be enforced at request time,
        not only at login time.

Rule 3: Delegation audit log is append-only. No update or delete.

Rule 4: Emergency delegation requires active emergency record.
        Emergency record ID must be stored in delegation row.

Rule 5: External access accounts must be separate user records
        with AUDITOR_EXTERNAL role — not temporary role changes
        on existing accounts.
```

## 6.2 Scheduled Jobs Required

```
DelegationExpiryJob     — runs every 15 minutes
                          marks expired delegations as expired
                          revokes access immediately
                          sends expiry notifications

DelegationReminderJob   — runs daily at 08:00
                          sends 24h expiry warnings
```

## 6.3 Prohibited Patterns

The following are prohibited:

- Storing delegation state in JWT only (must validate against DB)
- Allowing delegate to extend their own delegation
- Silently expiring delegation without notification
- Allowing re-delegation (delegate cannot create sub-delegation)

---

# 7. Document Ownership

This document owns:

- delegation type definitions
- delegation data model
- delegation UI rules
- delegation notification rules
- external access provisioning rules
- emergency delegation rules

This document does not own:

- base role definitions (owned by `rbac-matrix.md`)
- interface rendering rules (owned by `ux-architecture.md`)
- emergency response protocol (owned by `incident-response-playbook.md`)
- notification delivery mechanism (owned by `devops-infrastructure.md`)
