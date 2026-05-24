# Aegis RBAC Matrix
## Canonical Authorization Capability & Access Governance Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Security Governance / Authorization Authority  
**Authority Level:** Canonical Authorization Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical authorization governance for Aegis.

It governs:

- authorization capabilities
- role definitions
- permission assignments
- access scope semantics
- privileged access boundaries
- sensitive access restrictions
- implementation authorization truth
- AI-assisted implementation authorization constraints

---

## 1.2 Scope

Applies to:

- APIs
- web application
- mobile application
- workers
- background processing
- attachments
- exports
- analytics
- AI retrieval
- integrations
- admin operations

---

# 2. Authorization Doctrine

# 2.1 Core Rule

Authorization is capability-based.

Roles are capability bundles.

---

# 2.2 Role Doctrine

Roles are implementation governance abstractions.

Roles do not replace explicit capability evaluation.

---

# 2.3 Trust Rule

Client-side role claims are untrusted.

Server-side authorization mandatory.

---

# 2.4 Authority Rule

This document is authoritative for permission governance.

No alternate permission definition allowed elsewhere.

---

# 3. Scope Semantics

# 3.1 Scope Model

Canonical authorization scopes:

OWN

TEAM

ORG_UNIT

SITE

TENANT

PLATFORM

---

# 3.2 OWN

Definition:

Actor may access records explicitly owned by that actor.

Examples:

self-created hazard

self-assigned draft

own submitted report

---

# 3.3 TEAM

Definition:

Actor may access records within governed operational team scope.

---

# 3.4 ORG_UNIT

Definition:

Actor may access records within authorized organizational boundary.

Examples:

department

division

business unit

---

# 3.5 SITE

Definition:

Actor may access records within authorized physical site boundary.

---

# 3.6 TENANT

Definition:

Actor may access governed tenant-wide scope.

---

# 3.7 PLATFORM

Definition:

Platform operational governance scope.

Strictly restricted.

---

# 4. Canonical Roles

# 4.1 Workforce Roles

Operational roles:

Worker

Supervisor

---

# 4.2 Safety Governance Roles

Operational governance roles:

HSE Officer

HSE Manager

Permit Approver

---

# 4.3 Governance Roles

Governance roles:

Auditor

Compliance Officer

Document Controller

---

# 4.4 Contractor Roles

External workforce roles:

Contractor User

Contractor Supervisor

---

# 4.5 Administrative Roles

Administrative roles:

Tenant Admin

Platform Super Admin

---

# 4.6 Leadership Roles

Executive Viewer

---

# 4.7 Sensitive Roles

Occupational Health Officer

---

# 5. Capability Taxonomy

# 5.1 Capability Naming Rule

Canonical format:

```text
domain:action
```

Examples:

```text
incident:create
incident:view_scope
permit:approve
attachment:download
analytics:view_dashboard
```

---

# 5.2 Capability Groups

Capability groups:

incident

hazard

inspection

permit

JSA

CAPA

audit

compliance

documents

contractor

training

attachments

analytics

exports

admin

AI

occupational_health

platform

---

# 6. Incident Capabilities

Canonical capabilities:

```text
incident:create
incident:view_own
incident:view_scope
incident:update_draft
incident:submit
incident:assign_investigation
incident:investigate
incident:close
incident:reopen
incident:export
```

---

# 7. Hazard Capabilities

Canonical capabilities:

```text
hazard:create
hazard:view_own
hazard:view_scope
hazard:update
hazard:assign
hazard:verify
hazard:close
hazard:export
```

---

# 8. Inspection Capabilities

Canonical capabilities:

```text
inspection:create
inspection:view_scope
inspection:execute
inspection:update
inspection:complete
inspection:raise_finding
inspection:export
```

---

# 9. Permit Capabilities

Canonical capabilities:

```text
permit:create
permit:view_scope
permit:update_draft
permit:submit
permit:approve
permit:reject
permit:activate
permit:suspend
permit:revalidate
permit:close
permit:export
```

---

# 10. JSA Capabilities

Canonical capabilities:

```text
jsa:create
jsa:view_scope
jsa:update
jsa:submit
jsa:approve
jsa:revise
jsa:export
```

---

# 11. CAPA Capabilities

Canonical capabilities:

```text
capa:create
capa:view_scope
capa:update
capa:assign
capa:verify
capa:close
capa:reopen
capa:export
```

---

# 12. Audit Capabilities

Canonical capabilities:

```text
audit:create
audit:view_scope
audit:execute
audit:raise_finding
audit:close
audit:export
```

---

# 13. Compliance Capabilities

Canonical capabilities:

```text
compliance:create
compliance:view_scope
compliance:update
compliance:manage_obligation
compliance:export
```

---

# 14. Document Capabilities

Canonical capabilities:

```text
document:create
document:view_scope
document:update
document:approve
document:publish
document:archive
document:export
```

---

# 15. Attachment Capabilities

Canonical capabilities:

```text
attachment:upload
attachment:view_scope
attachment:download
attachment:delete
```

---

# 16. Analytics Capabilities

Canonical capabilities:

```text
analytics:view_dashboard
analytics:view_scope
analytics:query
analytics:export
```

---

# 17. Export Capabilities

Canonical capabilities:

```text
export:generate
export:download
```

---

# 18. AI Capabilities

Canonical capabilities:

```text
ai:use_assistance
ai:query_knowledge
ai:transcription
```

---

AI governance authority capabilities intentionally absent.

---

# 19. Sensitive Capabilities

Canonical capabilities:

```text
occupational_health:view_sensitive
occupational_health:update_sensitive
occupational_health:export_sensitive
```

---

# 20. Admin Capabilities

Canonical capabilities:

```text
admin:user_manage
admin:role_assign
admin:tenant_config
admin:site_manage
admin:org_manage
```

---

# 21. Platform Capabilities

Strictly restricted:

```text
platform:tenant_support
platform:ops_access
platform:incident_response
```

---

# 22. Explicit Prohibitions

Forbidden capability assumptions:

Worker approving permits

Contractor unrestricted tenant analytics

Executive workflow mutation

Auditor operational data mutation

AI workflow authority

Tenant admin platform ownership

---

# 23. Canonical Role Capability Matrix

Legend:

✅ Allowed

⚠ Allowed with governed scope/policy constraints

❌ Prohibited

---

# 23.1 Worker

Primary scope:

OWN / limited TEAM (tenant configurable)

| Capability | Access |
|----------|--------|
| incident:create | ✅ |
| incident:view_own | ✅ |
| incident:view_scope | ⚠ |
| incident:update_draft | ✅ |
| incident:submit | ✅ |
| incident:assign_investigation | ❌ |
| incident:investigate | ❌ |
| incident:close | ❌ |
| incident:reopen | ❌ |
| hazard:create | ✅ |
| hazard:view_own | ✅ |
| hazard:view_scope | ⚠ |
| hazard:update | ⚠ |
| hazard:assign | ❌ |
| hazard:verify | ❌ |
| hazard:close | ❌ |
| inspection:execute | ⚠ |
| permit:create | ⚠ |
| permit:update_draft | ⚠ |
| permit:submit | ⚠ |
| permit:approve | ❌ |
| permit:suspend | ❌ |
| permit:close | ❌ |
| capa:create | ⚠ |
| capa:view_scope | ⚠ |
| analytics:view_dashboard | ❌ |
| export:generate | ❌ |
| ai:use_assistance | ⚠ |

---

# 23.2 Supervisor

Primary scope:

TEAM / ORG_UNIT / SITE

| Capability | Access |
|----------|--------|
| incident:create | ✅ |
| incident:view_scope | ✅ |
| incident:assign_investigation | ⚠ |
| incident:investigate | ⚠ |
| incident:close | ❌ |
| hazard:view_scope | ✅ |
| hazard:assign | ✅ |
| hazard:verify | ⚠ |
| hazard:close | ⚠ |
| inspection:create | ⚠ |
| inspection:execute | ✅ |
| inspection:complete | ✅ |
| permit:create | ✅ |
| permit:submit | ✅ |
| permit:approve | ❌ |
| permit:suspend | ❌ |
| capa:create | ✅ |
| capa:assign | ⚠ |
| capa:verify | ⚠ |
| analytics:view_dashboard | ⚠ |
| export:generate | ⚠ |
| ai:use_assistance | ✅ |

---

# 23.3 HSE Officer

Primary scope:

SITE / ORG_UNIT / TENANT (tenant policy governed)

| Capability | Access |
|----------|--------|
| incident:view_scope | ✅ |
| incident:assign_investigation | ✅ |
| incident:investigate | ✅ |
| incident:close | ⚠ |
| incident:reopen | ⚠ |
| hazard:view_scope | ✅ |
| hazard:assign | ✅ |
| hazard:verify | ✅ |
| hazard:close | ✅ |
| inspection:create | ✅ |
| inspection:execute | ✅ |
| inspection:complete | ✅ |
| inspection:raise_finding | ✅ |
| permit:view_scope | ✅ |
| permit:create | ✅ |
| permit:submit | ✅ |
| permit:suspend | ⚠ |
| permit:approve | ❌ |
| capa:create | ✅ |
| capa:assign | ✅ |
| capa:verify | ✅ |
| capa:close | ⚠ |
| audit:view_scope | ⚠ |
| analytics:view_dashboard | ✅ |
| analytics:query | ⚠ |
| export:generate | ⚠ |
| ai:use_assistance | ✅ |
| ai:query_knowledge | ✅ |

---

# 23.4 HSE Manager

Primary scope:

ORG_UNIT / SITE / TENANT

| Capability | Access |
|----------|--------|
| incident:view_scope | ✅ |
| incident:assign_investigation | ✅ |
| incident:investigate | ✅ |
| incident:close | ✅ |
| incident:reopen | ✅ |
| hazard:view_scope | ✅ |
| hazard:assign | ✅ |
| hazard:verify | ✅ |
| hazard:close | ✅ |
| inspection:* governed equivalents | ✅ |
| permit:view_scope | ✅ |
| permit:suspend | ✅ |
| permit:approve | ⚠ |
| permit:close | ⚠ |
| capa:* governed equivalents | ✅ |
| audit:view_scope | ✅ |
| compliance:view_scope | ⚠ |
| analytics:view_dashboard | ✅ |
| analytics:query | ✅ |
| export:generate | ⚠ |
| ai:* allowed assistance | ✅ |

---

# 23.5 Permit Approver

Primary scope:

tenant-configured governance scope

| Capability | Access |
|----------|--------|
| permit:view_scope | ✅ |
| permit:approve | ✅ |
| permit:reject | ✅ |
| permit:suspend | ⚠ |
| permit:revalidate | ✅ |
| permit:close | ⚠ |
| permit:export | ⚠ |

---

# 23.6 Auditor

Primary scope:

governed audit scope

| Capability | Access |
|----------|--------|
| audit:create | ✅ |
| audit:view_scope | ✅ |
| audit:execute | ✅ |
| audit:raise_finding | ✅ |
| audit:close | ⚠ |
| incident:view_scope | ⚠ |
| hazard:view_scope | ⚠ |
| permit:view_scope | ⚠ |
| capa:view_scope | ⚠ |
| operational mutation capabilities | ❌ |
| admin capabilities | ❌ |

---

# 23.7 Compliance Officer

Primary scope:

TENANT / governance scope

| Capability | Access |
|----------|--------|
| compliance:create | ✅ |
| compliance:view_scope | ✅ |
| compliance:update | ✅ |
| compliance:manage_obligation | ✅ |
| audit:view_scope | ⚠ |
| document:view_scope | ⚠ |
| analytics:view_dashboard | ⚠ |
| export:generate | ⚠ |

---

# 23.8 Document Controller

Primary scope:

governed document scope

| Capability | Access |
|----------|--------|
| document:create | ✅ |
| document:view_scope | ✅ |
| document:update | ✅ |
| document:approve | ⚠ |
| document:publish | ✅ |
| document:archive | ⚠ |

---

# 23.9 Contractor User

Primary scope:

OWN / contractor-governed limited scope

| Capability | Access |
|----------|--------|
| incident:create | ⚠ |
| incident:view_own | ✅ |
| hazard:create | ⚠ |
| hazard:view_own | ✅ |
| permit:create | ⚠ |
| permit:submit | ⚠ |
| permit:approve | ❌ |
| analytics:view_dashboard | ❌ |
| export:generate | ❌ |
| occupational_health:* | ❌ |

---

# 23.10 Contractor Supervisor

Primary scope:

contractor governance scope

| Capability | Access |
|----------|--------|
| incident:view_scope | ⚠ |
| hazard:view_scope | ⚠ |
| permit:create | ⚠ |
| permit:submit | ⚠ |
| permit:approve | ❌ |
| capa:view_scope | ⚠ |
| analytics:view_dashboard | ❌ |

---

# 23.11 Tenant Admin

Primary scope:

TENANT administrative boundary only

| Capability | Access |
|----------|--------|
| admin:user_manage | ✅ |
| admin:role_assign | ✅ |
| admin:tenant_config | ✅ |
| admin:site_manage | ✅ |
| admin:org_manage | ✅ |
| incident:view_scope | ⚠ |
| hazard:view_scope | ⚠ |
| permit:view_scope | ⚠ |
| audit:view_scope | ⚠ |
| analytics:view_dashboard | ⚠ |
| export:generate | ⚠ |
| occupational_health:view_sensitive | ❌ by default |
| platform:* | ❌ |

---

## Doctrine

Tenant admin is tenant governance administrator.

Tenant admin is NOT platform operator.

---

# 23.12 Executive Viewer

Primary scope:

TENANT / governed executive scope

| Capability | Access |
|----------|--------|
| analytics:view_dashboard | ✅ |
| analytics:query | ⚠ |
| incident:view_scope | ⚠ |
| hazard:view_scope | ⚠ |
| permit:view_scope | ⚠ |
| audit:view_scope | ⚠ |
| export:generate | ⚠ |
| operational mutation capabilities | ❌ |
| admin capabilities | ❌ |

---

## Doctrine

Executive visibility ≠ operational authority.

---

# 23.13 Occupational Health Officer

Primary scope:

strict governed sensitive scope

| Capability | Access |
|----------|--------|
| occupational_health:view_sensitive | ✅ |
| occupational_health:update_sensitive | ✅ |
| occupational_health:export_sensitive | ⚠ |
| incident:view_scope | ⚠ (minimal required only) |
| analytics:view_dashboard | ⚠ (privacy-governed only) |
| admin capabilities | ❌ |
| permit:approve | ❌ |

---

## Doctrine

Sensitive access requires explicit heightened governance.

---

# 23.14 Platform Super Admin

Primary scope:

PLATFORM operational governance only

| Capability | Access |
|----------|--------|
| platform:tenant_support | ✅ |
| platform:ops_access | ⚠ |
| platform:incident_response | ✅ |
| admin:user_manage | ❌ |
| tenant business workflow mutation | ❌ by default |
| occupational_health:view_sensitive | ❌ by default |

---

## Doctrine

Platform super admin is platform operator.

Not tenant business operator.

---

## Explicit Restriction

Platform operational access requires:

enhanced audit

break-glass governance where applicable

security monitoring

least privilege

---

# 24. Attachment Authorization Rules

# 24.1 Core Rule

Attachment access inherits governing business authorization.

---

Examples:

incident evidence
→ incident authorization

audit evidence
→ audit authorization

permit attachments
→ permit authorization

---

# 24.2 Direct Access Prohibition

Unauthorized raw attachment access prohibited.

---

# 24.3 Cross-Tenant Attachment Access

Strictly prohibited.

---

# 25. Export Governance Rules

# 25.1 Core Rule

Export capabilities require explicit authorization.

---

# 25.2 Sensitive Export Rule

Sensitive exports require enhanced governance.

---

Examples:

occupational health exports

confidential investigations

regulated compliance evidence

---

# 25.3 Export Audit Rule

All governed exports auditable.

---

# 26. Analytics Authorization Rules

# 26.1 Core Rule

Analytics access respects tenant + role boundaries.

---

# 26.2 Cross-Tenant Analytics Prohibition

Strictly prohibited unless explicitly governed platform operations.

---

# 26.3 Executive Visibility Constraints

Executive access is visibility-focused.

Not workflow mutation authority.

---

# 27. AI Authorization Rules

# 27.1 Core Rule

AI inherits caller authorization boundaries.

---

# 27.2 Retrieval Authorization Rule

AI retrieval may only access documents caller is authorized to access.

---

# 27.3 Cross-Tenant AI Retrieval Prohibition

Strictly prohibited.

---

# 27.4 Sensitive AI Access Restrictions

Sensitive domains require explicit policy governance.

---

# 28. Deny-By-Default Doctrine

# 28.1 Core Rule

Undocumented capability access is denied.

---

# 28.2 Missing Permission Rule

Absence of explicit permission ≠ implicit access.

---

# 29. Role Assignment Governance

# 29.1 Role Assignment Constraints

Role assignment must be governed.

---

# 29.2 Self-Elevation Prohibition

Users may not elevate own privileges.

---

# 29.3 Sensitive Role Assignment

Sensitive roles require heightened governance.

Examples:

Occupational Health Officer

Platform Super Admin

Permit Approver

---

# 30. Separation of Duties Doctrine

# 30.1 Core Rule

Conflicting high-risk authorities should be separable.

---

Examples:

auditor vs workflow operator

permit requester vs permit approver

platform ops vs tenant business operator

sensitive health access vs broad admin access

---

# 31. Tenant Policy Overrides

# 31.1 Controlled Flexibility Rule

Tenant-specific authorization variations allowed only within governed constraints.

---

Examples:

scope tightening

additional approval restrictions

narrower export rights

---

Forbidden:

breaking constitutional security invariants

---

# 32. Jules Authorization Constraints

# 32.1 Explicit Rules

Jules must NOT:

invent undocumented roles

invent undocumented permissions

grant implicit access

trust frontend authorization

merge tenant admin with platform admin

grant AI workflow authority

permit cross-tenant access

grant broad export access by convenience

---

# 32.2 Missing Requirement Rule

If authorization ambiguity exists:

deny by default

require clarification

---

# 33. Canonical Authority Rule

This document is authoritative authorization truth.

All implementations must comply.

---

