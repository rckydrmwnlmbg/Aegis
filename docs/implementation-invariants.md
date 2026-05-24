# Aegis Implementation Invariants
## Canonical Non-Negotiable Engineering Constraints
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Engineering Governance / Implementation Constraints  
**Authority Level:** Implementation Constitutional Constraint

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines non-negotiable implementation invariants for Aegis.

These are hard engineering constraints.

Violation constitutes architectural non-compliance.

---

## 1.2 Scope

Applies to:

- backend engineering
- frontend engineering
- mobile engineering
- API implementation
- database implementation
- event processing
- integrations
- analytics
- AI systems
- infrastructure implementation
- AI-assisted implementation agents

---

# 2. Core Doctrine

# 2.1 Invariant Rule

Invariants are not recommendations.

They are mandatory constraints.

---

# 2.2 Violation Definition

Any implementation violating an invariant is architecturally invalid.

---

# 3. Tenant Isolation Invariants

# 3.1 Cross-Tenant Access Prohibition

Cross-tenant data access is prohibited unless explicitly governed administrative behavior.

---

Forbidden:

tenant A reading tenant B incident data

tenant A downloading tenant B attachments

tenant A analytics including tenant B truth

tenant A AI retrieval using tenant B documents

---

# 3.2 Tenant Context Requirement

Every governed request must execute inside explicit tenant context.

No exceptions.

---

# 3.3 Tenantless Persistence Prohibition

Governed business records must not be persisted without tenant ownership.

---

# 3.4 Cache Isolation Requirement

Tenant-sensitive cache contamination prohibited.

---

# 3.5 Queue Tenant Propagation Requirement

Async workflows must preserve tenant identity.

---

# 4. Authorization Invariants

# 4.1 Frontend Authorization Prohibition

Frontend authorization is not authoritative.

Server-side authorization mandatory.

---

# 4.2 Client Role Trust Prohibition

Client-provided roles are untrusted.

---

# 4.3 Privilege Escalation Prohibition

Implementation must not allow unauthorized privilege elevation.

---

# 4.4 RBAC Compliance Requirement

Authorization must comply with:

```text
rbac-matrix.md
```

---

# 5. Domain Ownership Invariants

# 5.1 Cross-Domain Ownership Prohibition

Domains may reference each other.

Domains may not own each other's truth.

---

Forbidden:

CAPA owning incident truth

Analytics mutating permit truth

AI mutating incident truth

Inspection owning CAPA lifecycle

---

# 5.2 God Service Prohibition

Central mega-service architecture prohibited.

---

Forbidden examples:

WorkflowService doing everything

SystemService owning all domains

MegaController architecture

---

# 5.3 Domain Boundary Preservation

Bounded ownership mandatory.

---

# 6. Workflow Integrity Invariants

# 6.1 Hidden Workflow Mutation Prohibition

Workflow state mutation must be explicit and governed.

---

Forbidden:

silent permit closure

implicit incident closure

hidden CAPA reassignment

background undocumented workflow mutation

---

# 6.2 Generic CRUD Governance Prohibition

Governed workflows must not degrade into naive CRUD mutation.

---

Forbidden:

```http
PATCH /permit
status=approved
```

---

# 6.3 Workflow Policy Compliance

Workflow logic must comply with:

```text
workflow-configurability.md
```

---

# 7. Auditability Invariants

# 7.1 Audit Bypass Prohibition

Governed critical actions must not bypass auditability.

---

Examples:

permit approval

incident closure

CAPA closure

privileged access

sensitive export

AI governance actions

---

# 7.2 Silent Mutation Prohibition

Critical truth mutation without traceability prohibited.

---

# 8. API Contract Invariants

# 8.1 Contract Drift Prohibition

Implementation must comply with:

```text
api-contracts.md
```

---

# 8.2 Undocumented Endpoint Prohibition

Hidden undocumented behavioral APIs prohibited.

---

# 8.3 Response Shape Drift Prohibition

Published response contracts must remain stable.

---

# 9. Schema Invariants

# 9.1 Schema Ownership Compliance

Persistence must comply with:

```text
schema.md
```

---

# 9.2 Giant Flat Schema Prohibition

Enterprise mega flat schema prohibited.

---

# 9.3 JSON-as-Schema Prohibition

Unbounded JSON dumping as business truth prohibited.

---

Forbidden:

entire incident truth stored as arbitrary JSON blob

---

# 10. Event Architecture Invariants

# 10.1 Command-Disguised Event Prohibition

Events communicate facts.

Not commands.

---

Forbidden:

CreateCAPAFromIncident

ApprovePermitNow

---

# 10.2 Event Contract Compliance

Must comply with:

```text
event-contracts.md
```

---

# 10.3 Tenantless Event Prohibition

Events without tenant propagation prohibited.

---

# 11. Analytics Invariants

# 11.1 OLTP Abuse Prohibition

Analytics must not directly degrade transactional operational systems.

---

# 11.2 Transactional Mutation Prohibition

Analytics may not mutate operational truth.

---

# 11.3 Cross-Tenant Analytics Leakage Prohibition

Strict tenant isolation mandatory.

---

# 12. AI Invariants

# 12.1 AI Governance Authority Prohibition

AI may not become authoritative workflow controller.

---

Forbidden:

AI approving permits

AI closing incidents

AI closing CAPA

AI overriding RBAC

---

# 12.2 AI Tenant Leakage Prohibition

AI context isolation mandatory.

---

# 12.3 AI Compliance Requirement

Implementation must comply with:

```text
ai-governance.md
```

---

# 13. Attachment Invariants

# 13.1 Direct Object Exposure Prohibition

Raw storage exposure prohibited.

---

Forbidden:

public direct bucket URLs

unguarded object access

---

# 13.2 Attachment Authorization Requirement

Attachment access must be governed.

---

# 13.3 Integrity Validation Requirement

Evidence integrity verification mandatory.

---

# 14. Sync Invariants

# 14.1 Duplicate Mutation Corruption Prohibition

Offline retries must not create duplicate truth.

---

# 14.2 Sync Idempotency Requirement

Mandatory.

---

# 14.3 Hidden Conflict Resolution Prohibition

Conflicts must be explicit.

---

# 15. Security Invariants

# 15.1 Secret Exposure Prohibition

Secrets must never be exposed.

---

# 15.2 Unsafe Logging Prohibition

Forbidden:

auth tokens

credentials

sensitive health payloads

full unrestricted PII dumps

---

# 15.3 Threat Compliance Requirement

Must comply with:

```text
threat-model.md
```

---

# 16. Sensitive Data Invariants

# 16.1 Occupational Health Exposure Prohibition

Sensitive health data access strictly governed.

---

# 16.2 Privacy Classification Compliance

Must comply with:

```text
privacy-classification.md
```

---

# 17. Infrastructure Invariants

# 17.1 Single Point of Failure by Neglect Prohibition

Critical infrastructure negligence prohibited.

---

# 17.2 Observability Blindness Prohibition

Critical operational behavior must be observable.

---

# 17.3 Deployment Compliance

Must comply with:

```text
deployment-architecture.md
```

---

# 18. Coding Integrity Invariants

# 18.1 Architecture Invention Prohibition

Implementation agents must not invent undocumented architecture.

---

# 18.2 Convenience Shortcut Prohibition

Architectural shortcuts violating doctrine prohibited.

---

# 18.3 Engineering Standards Compliance

Must comply with:

```text
engineering-standards.md
```

---

# 19. Final Constitutional Rule

When uncertain:

DO NOT invent architecture.

Escalate for clarification.

---

# 20. Canonical Authority Statement

These invariants are non-negotiable implementation constraints.

Violations invalidate implementation compliance.

---

