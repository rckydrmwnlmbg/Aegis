# Aegis Engineering Standards
## Canonical Engineering Implementation Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Engineering Governance / Implementation Standards  
**Authority Level:** Engineering Implementation Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical engineering implementation standards for Aegis.

It governs:

- code architecture discipline
- implementation structure
- domain boundary enforcement
- transaction discipline
- error handling
- logging behavior
- testing minimums
- naming conventions
- implementation constraints
- AI-assisted engineering behavior

---

## 1.2 Scope

Applies to:

- backend implementation
- API implementation
- workers
- integrations
- analytics implementation
- AI orchestration code
- infrastructure-support code
- automated implementation agents

---

# 2. Core Engineering Doctrine

# 2.1 Architecture Compliance Rule

Code must implement architecture.

Code must not reinterpret architecture.

---

# 2.2 Convenience Prohibition

Convenience shortcuts violating architecture are prohibited.

---

# 2.3 Deterministic Engineering Rule

Implementation should be explicit, deterministic, and reviewable.

Hidden magic discouraged.

---

# 3. Architectural Code Boundaries

# 3.1 Domain Boundary Enforcement

Bounded domain ownership mandatory.

Code must respect:

```text
domain-boundaries.md
schema.md
implementation-invariants.md
```

---

# 3.2 Cross-Domain Mutation Prohibition

Direct mutation of another domain’s authoritative truth prohibited.

---

Forbidden:

Incident service directly mutating CAPA lifecycle.

CAPA service directly mutating incident truth.

Analytics mutating operational truth.

AI mutating workflow authority.

---

# 3.3 God Service Prohibition

Forbidden:

mega orchestration service owning multiple unrelated domains.

Examples:

```text
SystemService
WorkflowService
MegaBusinessService
UniversalManager
```

---

# 4. Layering Doctrine

# 4.1 Required Layer Discipline

Canonical layers:

API / interface layer

application orchestration layer

domain logic layer

persistence layer

infrastructure adapter layer

---

# 4.2 Responsibility Rules

API layer:

request handling
validation orchestration
auth integration
response shaping

---

Application layer:

use-case orchestration
transaction coordination
workflow coordination

---

Domain layer:

business rules
domain invariants
workflow legality

---

Persistence layer:

data access only

---

Infrastructure layer:

external dependency integration

---

# 4.3 Layer Violation Prohibition

Forbidden:

business rules inside controllers

DB logic inside controllers

HTTP concerns inside domain logic

provider SDK logic inside domain rules

---

# 5. API Engineering Standards

# 5.1 Contract Compliance

API implementation must comply with:

```text
api-contracts.md
```

---

# 5.2 Controller Thinness Rule

Controllers must remain thin.

---

Controllers should not contain:

workflow business logic

authorization business policy logic

complex persistence logic

provider orchestration

---

# 5.3 Response Consistency

Response structures must remain contract-compliant.

---

# 6. Transaction Discipline

# 6.1 Transaction Ownership

Transactions must be explicit.

---

# 6.2 Transaction Scope Rule

Transactions should be bounded.

Avoid unnecessarily broad transaction scopes.

---

# 6.3 Cross-System Transaction Prohibition

Distributed transaction fantasy prohibited.

---

Forbidden:

DB + AI provider + webhook inside single transactional assumption

---

# 7. Persistence Standards

# 7.1 Schema Compliance

Persistence implementation must comply with:

```text
schema.md
```

---

# 7.2 Repository Responsibility

Repositories perform persistence access.

Repositories do NOT own business rules.

---

# 7.3 Query Discipline

Unsafe unrestricted query construction prohibited.

---

# 7.4 Migration Governance

Schema changes require governed migrations.

No silent schema drift.

---

# 8. Event Engineering Standards

# 8.1 Event Contract Compliance

Must comply with:

```text
event-contracts.md
```

---

# 8.2 Event Naming Rule

Events describe completed facts.

---

Good:

```text
IncidentReported
```

Bad:

```text
DoIncidentProcessing
```

---

# 8.3 Consumer Discipline

Consumers must be idempotent.

---

# 9. Error Handling Standards

# 9.1 Explicit Failure Handling

Failures must be explicit.

Silent swallowing prohibited.

---

# 9.2 Exception Discipline

Exceptions must preserve diagnostic value.

Opaque generic failures discouraged.

---

# 9.3 Security Failure Rule

Failures must fail securely.

No unsafe fallback authorization.

---

# 10. Logging Standards

# 10.1 Structured Logging

Structured logging mandatory.

---

# 10.2 Logging Compliance

Must align with:

```text
observability-runbook.md
```

---

# 10.3 Sensitive Logging Restrictions

Forbidden:

token logging

secret logging

raw sensitive health data

unguarded PII dumps

---

# 11. Configuration Standards

# 11.1 Configuration Governance

Environment-dependent behavior must be explicit.

---

# 11.2 Hardcoded Secret Prohibition

Forbidden.

---

# 11.3 Hardcoded Tenant Logic Prohibition

Tenant-specific logic hardcoding prohibited.

---

# 12. Integration Engineering Standards

# 12.1 Adapter Rule

External providers accessed through explicit adapters.

---

# 12.2 Provider Isolation

Provider SDK leakage across business logic prohibited.

---

# 13. AI Engineering Standards

# 13.1 AI Governance Compliance

Must comply with:

```text
ai-governance.md
```

---

# 13.2 AI Boundary Rule

AI is assistive infrastructure.

Not workflow authority.

---

# 14. Testing Standards

# 14.1 Testing Compliance

Must comply with:

```text
testing-strategy.md
```

---

# 14.2 Minimum Expectations

Changes should include relevant tests.

---

Required where applicable:

unit

domain behavior

API contracts

tenant isolation

authorization

event behavior

---

# 14.3 Untested Critical Change Prohibition

Critical architectural changes without testing prohibited.

---

# 15. Naming Standards

# 15.1 Naming Philosophy

Names should be explicit and domain-aligned.

---

# 15.2 Good Naming

Examples:

IncidentService

PermitApprovalPolicy

HazardRepository

CAPAClosureValidator

---

# 15.3 Bad Naming

Examples:

Manager

Helper

Processor

Utils

SystemThing

MegaService

---

# 16. Utility Abuse Prevention

# 16.1 Utility Discipline

Generic dumping-ground utility classes discouraged.

---

# 16.2 Domain Placement Rule

Domain logic belongs in domain ownership.

---

# 17. Jules Implementation Rules

# 17.1 Explicit Constraints

Jules must not:

invent architecture

flatten layering

create god services

bypass contracts

hardcode tenant assumptions

invent auth shortcuts

bypass audit rules

merge analytics into operational logic

---

# 17.2 Missing Requirement Rule

When uncertain:

stop architecture invention

require clarification

---

# 18. Code Review Standards

# 18.1 Review Criteria

Review must validate:

architecture compliance

domain ownership

security compliance

tenant isolation

contract compliance

auditability

test coverage relevance

---

# 19. Final Authority Rule

If implementation style conflicts with architecture doctrine:

architecture doctrine wins.

---

# 20. Canonical Authority Statement

This document defines mandatory engineering implementation standards.

---

**END OF ENGINEERING STANDARDS**