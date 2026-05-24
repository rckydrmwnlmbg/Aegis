# Aegis Canonical Glossary
## Canonical Terminology & Semantic Authority
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Documentation Governance / Terminology Authority  
**Authority Level:** Canonical Semantic Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical terminology for Aegis.

It governs:

- semantic definitions
- implementation terminology
- architectural language consistency
- anti-hallucination vocabulary constraints
- cross-document terminology alignment

---

## 1.2 Scope

Applies to:

- product documentation
- architecture documentation
- implementation documentation
- APIs
- schemas
- engineering implementation
- AI-assisted implementation agents

---

# 2. Terminology Doctrine

# 2.1 Canonical Vocabulary Rule

Defined terms in this glossary are authoritative.

Alternate conflicting definitions prohibited.

---

# 2.2 Semantic Drift Prohibition

Terms must not change meaning between documents.

---

# 2.3 Synonym Control Rule

Undocumented synonym substitution prohibited.

---

Example:

If canonical term is:

Incident

Implementation must not arbitrarily substitute:

Event
Case
Occurrence
Safety Case

unless explicitly governed.

---

# 3. Organizational Terms

# 3.1 Tenant

Definition:

Top-level isolated customer boundary.

Represents independent operational ownership, security isolation, data governance boundary, and authorization boundary.

Examples:

enterprise customer

subsidiary with isolated deployment model (if explicitly configured)

---

Not equivalent to:

department

site

project

organization unit

user group

---

# 3.2 Organization Unit

Definition:

Internal hierarchical organizational structure inside a tenant.

Examples:

department

division

business unit

operational branch

---

Not equivalent to tenant.

---

# 3.3 Site

Definition:

Physical operational location.

Examples:

plant

facility

construction site

warehouse

office

---

# 3.4 Project

Definition:

Operational project boundary within tenant scope.

May span time-bounded work.

---

# 4. Identity & Access Terms

# 4.1 App User

Definition:

Application identity recognized by Aegis.

May represent:

employee

contractor user

admin user

auditor

---

Not authentication authority.

---

# 4.2 Authentication

Definition:

Identity verification process.

Confirms who actor is.

---

Not equivalent to authorization.

---

# 4.3 Authorization

Definition:

Permission evaluation determining what authenticated actor may do.

---

Not equivalent to approval.

---

# 4.4 Approval

Definition:

Governed business workflow decision.

Examples:

permit approval

JSA approval

document approval

---

Approval is business workflow action.

Not security authorization.

---

# 4.5 RBAC

Definition:

Role-based access control governance model.

Defines capability permissions.

---

Authority:

```text
rbac-matrix.md
```

---

# 5. Workforce Terms

# 5.1 Worker

Definition:

Operational field workforce participant.

May include:

employees

authorized field personnel

tenant-defined operational personnel

---

Contractor inclusion depends on explicit tenant governance.

Not automatically synonymous.

---

# 5.2 Contractor

Definition:

External organization or workforce operating under tenant governance.

---

Not equivalent to employee.

---

# 5.3 Contractor User

Definition:

Application identity representing contractor-associated actor.

---

# 5.4 Supervisor

Definition:

Operational oversight role.

Business role.

Not necessarily security superuser.

---

# 5.5 Tenant Admin

Definition:

Administrative actor within tenant governance boundary.

---

Not equivalent to platform super-admin.

---

# 6. Safety Domain Terms

# 6.1 Incident

Definition:

Governed safety event resulting in actual operational occurrence requiring incident governance lifecycle.

---

Examples:

injury event

equipment incident

safety breach event

environmental incident (if governed in incident model)

---

Not equivalent to:

hazard

near miss

audit finding

---

# 6.2 Near Miss

Definition:

Operational event that could have resulted in incident but did not produce actual incident outcome.

---

Not equivalent to incident.

---

# 6.3 Hazard

Definition:

Observed unsafe condition, unsafe behavior, or risk condition requiring remediation.

---

Hazard is condition/risk observation.

Not equivalent to incident occurrence.

---

# 6.4 Inspection

Definition:

Structured operational verification activity against defined checklist or governance criteria.

---

# 6.5 Finding

Definition:

Issue identified through governed assessment process.

Examples:

inspection finding

audit finding

---

Not equivalent to incident.

---

# 6.6 CAPA

Definition:

Corrective and preventive action governance workflow.

---

Purpose:

remediation / prevention lifecycle

---

CAPA does not own originating truth.

---

# 7. Permit & Workflow Terms

# 7.1 Permit

Definition:

Governed authorization-to-work operational workflow.

---

Examples:

hot work permit

confined space permit

electrical work permit

---

Not security authorization.

---

# 7.2 JSA

Definition:

Job Safety Analysis structured risk assessment for work execution.

---

# 7.3 Closure

Definition:

Governed lifecycle completion action.

---

Not equivalent to deletion.

---

# 7.4 Verification

Definition:

Governed validation that remediation or action meets required conditions.

---

Verification may precede closure.

---

# 7.5 Revalidation

Definition:

Governed reaffirmation of continued permit validity.

---

# 8. Data Terms

# 8.1 Attachment

Definition:

Governed binary or document evidence object managed by attachment infrastructure.

---

Examples:

photos

evidence files

controlled document binaries

exports

---

Not equivalent to public file URL.

---

# 8.2 Transactional Truth

Definition:

Authoritative operational source-of-record data.

---

Examples:

incident records

permits

CAPA

audit findings

---

# 8.3 Analytical Data

Definition:

Derived non-authoritative intelligence representations.

---

Examples:

KPIs

aggregations

trend projections

---

Not operational truth.

---

# 9. Event Terms

# 9.1 Domain Event

Definition:

Fact emitted by authoritative domain representing completed business occurrence.

---

Not command.

---

# 9.2 Integration Event

Definition:

Governed event intended for external system interaction.

---

Not automatically same as internal domain event.

---

# 9.3 Event Consumer

Definition:

Component reacting to event fact.

---

Not event owner.

---

# 10. AI Terms

# 10.1 AI Assistance

Definition:

Non-authoritative intelligent augmentation.

---

Examples:

drafting

classification suggestions

retrieval assistance

transcription

analytics interpretation

---

# 10.2 AI Governance Authority

Definition:

AI acting as authoritative business decision maker.

---

Prohibited.

---

# 10.3 Retrieval

Definition:

Governed document/context acquisition for AI or knowledge use.

---

Must respect access boundaries.

---

# 11. Sync Terms

# 11.1 Offline Sync

Definition:

Governed deferred mutation synchronization between disconnected clients and authoritative backend.

---

# 11.2 Idempotency

Definition:

Repeated equivalent operation does not create duplicate unsafe effect.

---

# 11.3 Conflict

Definition:

State inconsistency requiring explicit governed resolution.

---

# 12. Security Terms

# 12.1 Sensitive Data

Definition:

Data requiring enhanced access governance.

---

Examples:

occupational health

medical restrictions

security incidents

confidential investigations

---

# 12.2 Break Glass

Definition:

Exceptional privileged access mechanism under heightened audit governance.

---

# 12.3 Threat

Definition:

Adversarial risk capable of violating system security assumptions.

---

# 13. Platform Terms

# 13.1 Modular Monolith

Definition:

Single deployable application with strict internal bounded domain architecture.

---

Not giant unstructured monolith.

---

Not microservices.

---

# 13.2 Worker Runtime

Definition:

Background async processing execution environment.

---

# 13.3 API Runtime

Definition:

Request-serving application execution boundary.

---

# 14. Semantic Authority Rule

If terminology ambiguity exists:

this glossary wins.

---

# 15. Canonical Authority Statement

This document is the authoritative semantic reference for all implementation and documentation.

---

