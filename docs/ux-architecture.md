# Aegis UX Architecture
## Role-Based Adaptive Interface & Contextual Flexibility Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 1.0  
**Status:** Source of Truth  
**Classification:** UX Architecture / Interface Governance  
**Authority Level:** Level 2 — Architecture Truth  

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical UX architecture governance for Aegis.

It governs:

- interface layer stratification
- role-to-interface mapping
- dynamic context switching doctrine
- workspace personalization rules
- mobile interface constraints
- delegation display rules
- implementation interface truth

---

## 1.2 Critical Implementation Note

This documentation corpus is intended for:

- frontend engineering teams
- UI/UX implementation agents (including Jules)
- architecture review
- product review

Therefore:

Interface implementation must follow this document exactly.

Deviation from interface doctrine is prohibited without ADR.

---

## 1.3 Core Philosophy

Interface design doctrine:

```
Role determines PERMISSION.
Context determines INTERFACE.
Personalization determines LAYOUT.
```

One application.

Many interfaces.

No user sees everything at once.

---

# 2. Interface Layer Doctrine

## 2.1 Three-Layer Architecture

Aegis interface operates on three independent layers:

```
LAYER 1 — Base Role (Static)
LAYER 2 — Dynamic Context (Situational)
LAYER 3 — Workspace Personalization (Personal)
```

These layers are independent.

They compose to produce the final interface.

---

## 2.2 Layer 1 — Base Role

Base Role is permanent.

Base Role is assigned by Tenant Admin.

Base Role defines capability boundaries.

Base Role cannot be changed by the user.

Canonical Base Roles:

```
FIELD_WORKER
HSE_OFFICER
HSE_MANAGER
EXECUTIVE
CONTRACTOR
TENANT_ADMIN
AUDITOR_EXTERNAL
```

Authority for Base Role definitions:

```
rbac-matrix.md
```

---

## 2.3 Layer 2 — Dynamic Context

Dynamic Context is situational.

Dynamic Context is activated per session.

Dynamic Context can be switched without logout.

Dynamic Context grants additional interface access within Base Role boundaries.

Canonical Dynamic Contexts:

```
SITE_CONTEXT       — scopes dashboard to a specific site
AUDIT_CONTEXT      — activates audit-mode interface overlays
P2K3_CONTEXT       — activates P2K3 meeting and recommendation tools
CONTRACTOR_CONTEXT — activates contractor supervision view
EMERGENCY_CONTEXT  — activates emergency response interface
DELEGATE_CONTEXT   — activates delegated authority interface
```

Multiple contexts may be active simultaneously.

Context switching must not require logout.

Context switching must not lose unsaved form data.

---

## 2.4 Layer 3 — Workspace Personalization

Workspace is personal.

Workspace is per-user within their Base Role and active Contexts.

Workspace governs:

- which widgets appear on the dashboard
- widget position and size
- default landing page after login
- notification display preferences
- preferred site (for multi-site users)

Workspace personalization cannot override RBAC capability boundaries.

Workspace personalization cannot reveal prohibited modules.

---

# 3. Role Interface Specifications

## 3.1 FIELD_WORKER Interface

### 3.1.1 Interface Doctrine

Primary device: mobile.

Interface must be operable with one hand.

Interface must be operable offline.

Interface must be operable by low-digital-literacy users.

Complexity is prohibited.

### 3.1.2 Home Screen

Maximum 4 primary action buttons:

```
┌─────────────────┬─────────────────┐
│  🎙️ Laporkan    │  ⚠️ Hazard      │
│     Insiden     │   Observasi     │
├─────────────────┼─────────────────┤
│  📋 Tugas       │  🪪 Izin        │
│     Saya        │    Kerja        │
└─────────────────┴─────────────────┘
```

No sidebar.

No analytics.

No configuration menu.

### 3.1.3 Navigation

Bottom navigation bar only.

Maximum 4 tabs:

```
Home | Tugas | Notifikasi | Profil
```

### 3.1.4 Prohibited Elements

The following are prohibited in FIELD_WORKER interface:

- Analytics charts
- Data tables with more than 5 columns
- Configuration screens
- Export functions
- Multi-step wizards exceeding 3 steps

---

## 3.2 HSE_OFFICER Interface

### 3.2.1 Interface Doctrine

Primary device: web (desktop/tablet).

Secondary device: mobile for field work.

Dashboard is an action inbox, not an analytics showcase.

### 3.2.2 Dashboard Structure

```
⚡ PERLU TINDAKAN SEKARANG
├── Draft laporan AI menunggu review
├── PTW menunggu approval
└── CAPA overdue

📋 KERJA HARI INI
├── Jadwal inspeksi
├── Toolbox talk terjadwal
└── Audit item due hari ini

📊 QUICK STATS (ringkas)
└── Incident minggu ini | CAPA open | PTW aktif
```

### 3.2.3 Sidebar Navigation

Maximum 7 items:

```
Dashboard
Laporan & Insiden
Hazard Observasi
PTW & Izin Kerja
Inspeksi
CAPA
Notifikasi
```

### 3.2.4 Prohibited Elements

The following are prohibited in HSE_OFFICER interface:

- Executive-level analytics dashboards
- Tenant administration panel
- Billing and subscription management
- Cross-tenant data access

---

## 3.3 HSE_MANAGER Interface

### 3.3.1 Interface Doctrine

Primary device: web (desktop).

Dashboard is a governance and oversight view.

HSE_MANAGER does not execute operational tasks — they govern and approve.

### 3.3.2 Dashboard Structure

```
📊 SAFETY PERFORMANCE INDEX
├── Trend chart 30 hari
├── LTIR | TRIR | DART rate
└── vs target status

🔴 PERHATIAN DIPERLUKAN
├── Site dengan anomali
├── Vendor dengan sertifikasi expired
└── Compliance item due

⚙️ GOVERNANCE QUEUE
└── Dokumen menunggu approval saya
```

### 3.3.3 Sidebar Navigation

Maximum 7 items:

```
Overview
Safety Analytics
Compliance & Audit
CAPA Management
PTW Governance
Contractor & CSMS
Laporan Regulasi
```

---

## 3.4 EXECUTIVE Interface

### 3.4.1 Interface Doctrine

Primary device: web or mobile.

Interface must be readable in under 60 seconds.

No operational tools.

Read-only by default.

### 3.4.2 Dashboard Structure

```
SAFETY SCORE
[Single prominent metric with trend]

SITE PERFORMANCE
[Horizontal bar comparison, all sites]

KEY ALERTS
[Maximum 3 items requiring awareness]

DOWNLOAD
[Laporan Disnaker | Executive Summary]
```

### 3.4.3 Prohibited Elements

The following are prohibited in EXECUTIVE interface:

- Form inputs
- Data entry of any kind
- Configuration panels
- Operational task lists

---

## 3.5 TENANT_ADMIN Interface

### 3.5.1 Interface Doctrine

Primary device: web (desktop).

TENANT_ADMIN has access to all configuration modules.

TENANT_ADMIN does not have access to operational HSE data by default.

### 3.5.2 Navigation Clusters

```
CLUSTER — User & Access
├── User Management
├── Role Assignment
├── Delegation Rules
└── Time-bound Access

CLUSTER — Site & Organization
├── Site & Area Hierarchy
├── Department Structure
└── Shift Configuration

CLUSTER — Workflow & Forms
├── Workflow Configurability
├── Custom Form Builder
└── Notification Rules

CLUSTER — Compliance Configuration
├── Regulatory Profile
├── SMK3 Criteria Mapping
└── Audit Schedule

CLUSTER — System
├── Integration Settings
├── Data Retention Policy
├── Security Settings
└── Billing & Subscription
```

---

## 3.6 AUDITOR_EXTERNAL Interface

### 3.6.1 Interface Doctrine

Time-limited access.

Read-only. No exception.

Access scope defined by TENANT_ADMIN at invitation time.

Auto-expire required. No manual expiry extension by auditor.

### 3.6.2 Available Modules

Only modules explicitly granted at invitation:

```
SMK3 Documentation
Audit Findings
Compliance Records
Incident Reports (approved only)
CAPA Evidence
```

### 3.6.3 Prohibited Actions

The following are prohibited for AUDITOR_EXTERNAL:

- Any write operation
- Export of raw data (only approved report export)
- Access to personal employee data
- Access to contractor commercial data

---

# 4. Context Switching Doctrine

## 4.1 Context Switcher Component

Context Switcher must be accessible from:

- top navigation bar (persistent)
- user profile menu

Context Switcher must display:

- currently active contexts
- available contexts for current user
- site scope selector (if multi-site)

## 4.2 Context Switch Rules

Context switch must not:

- require logout
- lose unsaved form data
- reset notification state
- change Base Role permissions

Context switch must:

- update dashboard widgets to reflect new context
- update sidebar to show context-relevant items
- update notification filter to context scope
- record context switch in audit log

## 4.3 Multi-Site Context

Users with multi-site access may switch site context.

Site context affects:

- dashboard data scope
- incident and hazard lists
- inspection schedules
- PTW queue

Site context does not affect:

- account settings
- delegation rules
- notification preferences

---

# 5. Widget System Doctrine

## 5.1 Widget Definition

Widget is a self-contained dashboard component.

Widget displays a single data concept.

Widget is configurable per user within role boundaries.

## 5.2 Canonical Widget Catalog

```
CAPA_OVERDUE_MINE       — My overdue CAPA items
PTW_PENDING_APPROVAL    — PTW awaiting my approval
INSPECTION_TODAY        — Today's inspection schedule
INCIDENT_7DAY           — Incidents last 7 days
HAZARD_OPEN             — Open hazard observations
AI_DRAFT_QUEUE          — AI-structured drafts awaiting review
TEAM_ONDUTY             — Current on-duty team members
APAR_DUE                — APAR inspections due this month
CERTIFICATION_EXPIRING  — Expiring certifications (30 days)
RISK_SCORE_SITE         — Current contextual risk score
WEATHER_ALERT           — Weather-based risk alert
```

## 5.3 Widget Permission Boundary

Widget visibility is governed by Base Role.

FIELD_WORKER cannot access analytics widgets.

EXECUTIVE cannot access operational input widgets.

Widget content is scoped to active site context.

---

# 6. Mobile Interface Doctrine

## 6.1 Mobile-First Roles

The following roles must have a fully functional mobile interface:

```
FIELD_WORKER    — primary interface
HSE_OFFICER     — secondary interface for field work
CONTRACTOR      — primary interface
```

## 6.2 Mobile Constraints

Mobile interface must:

- function offline (critical actions)
- sync when connection restored
- use native device camera for photo capture
- use native device microphone for voice input
- support biometric authentication

Mobile interface must not:

- require desktop for any primary task
- display tables with more than 3 columns
- require file upload from desktop

## 6.3 Offline-Capable Actions

The following actions must work offline:

```
Submit incident report (voice or form)
Submit hazard observation (with photo)
Complete inspection checklist
View assigned CAPA tasks
View active PTW details
```

Authority for offline sync implementation:

```
offline-sync-architecture.md
```

---

# 7. Implementation Constraints for Jules

## 7.1 Critical Rules

Implementation agents must follow:

```
Rule 1: Never render prohibited elements for any role.
Rule 2: Context switch must not trigger page reload.
Rule 3: Widget drag-and-drop must persist to user preferences.
Rule 4: All interface changes must respect RBAC boundaries.
Rule 5: Mobile interface must pass offline functionality tests.
```

## 7.2 Technology Constraints

Web interface: Next.js 14+ with App Router.

Mobile interface: Flutter.

Widget state: persisted to user_preferences table per user.

Context state: persisted to session, restored on re-login.

Design tokens authority:

```
design-system.md
```

## 7.3 Prohibited Implementation Patterns

The following patterns are prohibited:

- Hiding menu items with CSS only (must use server-side role check)
- Sharing a single dashboard component across all roles
- Hardcoding site list (must come from tenant configuration)
- Storing context state in localStorage only (must sync to backend)

---

# 8. Document Ownership

This document owns:

- interface layer definitions
- role-to-interface mapping
- widget catalog
- context switching rules
- mobile interface constraints

This document does not own:

- RBAC capability definitions (owned by `rbac-matrix.md`)
- Design tokens and visual language (owned by `design-system.md`)
- Delegation authority rules (owned by `delegation-engine.md`)
- Offline sync implementation (owned by `offline-sync-architecture.md`)
