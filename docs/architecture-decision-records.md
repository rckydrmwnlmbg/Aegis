# Aegis Architecture Decision Records
## Canonical Architectural Decision Authority
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Architecture Governance / Decision Authority  
**Authority Level:** Architectural Rationale Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document records canonical architectural decisions for Aegis.

It governs:

- architectural rationale
- implementation interpretation
- decision permanence
- anti-regression architectural context
- anti-hallucination decision constraints

---

## 1.2 Scope

Applies to:

- architecture implementation
- engineering decisions
- platform evolution
- AI-assisted implementation interpretation

---

# 2. ADR Doctrine

# 2.1 Core Rule

Architectural decisions are intentional.

They are not arbitrary implementation suggestions.

---

# 2.2 Modification Rule

Architectural decisions may only change through explicit architectural governance.

Silent implementation reinterpretation prohibited.

---

# 2.3 Interpretation Rule

When implementation ambiguity exists:

architectural rationale must inform conservative interpretation.

---

# ADR-001 — Laravel Version Selection

## Decision

Canonical application framework version:

Laravel 13.8 instead of Laravel 11 as stated in devplan.

---

## Status

ACCEPTED

---

## Rationale

Chosen because:

- Jules initialized with latest stable version

---

## Impact

None — Laravel 13 is backward compatible for all planned features.

---

# ADR-002 — Modular Monolith Architecture

## Decision

Canonical application architecture:

modular monolith

with strict bounded internal domains.

---

## Status

ACCEPTED

---

## Rationale

Chosen because:

- domain separation preserved
- deployment complexity controlled
- enterprise portability improved
- faster delivery coherence
- reduced distributed system overhead
- simpler operational governance

---

## Explicit Rejection

Rejected:

premature microservices-first decomposition

---

## Implementation Constraint

Do not split domains into distributed services without explicit architectural change.

---

# ADR-003 — REST-First API Architecture

## Decision

Canonical API architecture:

REST-first resource-oriented APIs

---

## Status

ACCEPTED

---

## Rationale

Chosen because:

- mobile compatibility
- offline sync compatibility
- enterprise integration familiarity
- predictable auth enforcement
- explicit behavioral semantics

---

## Explicit Rejection

Rejected:

GraphQL-first transactional architecture

unstructured RPC sprawl

---

# ADR-004 — Behavioral Workflow APIs

## Decision

Governed workflows use explicit behavioral endpoints.

---

Examples:

```http
POST /permits/{id}/approve
POST /capa/{id}/close
```

---

## Rationale

Prevents:

generic unsafe status mutation

workflow ambiguity

audit bypass

---

## Explicit Rejection

Rejected:

generic CRUD workflow mutation

---

# ADR-005 — External Identity Provider Authority

## Decision

Authentication authority remains external.

Application identity remains local governance metadata.

---

## Rationale

Benefits:

enterprise SSO compatibility

identity governance separation

reduced auth complexity

better federation support

---

## Explicit Rejection

Rejected:

homegrown primary authentication ownership

---

# ADR-006 — Strict Tenant Isolation

## Decision

Tenant isolation is foundational architecture.

---

## Rationale

Aegis is enterprise multi-tenant platform.

Cross-tenant leakage is catastrophic.

---

## Constraints

Applies to:

APIs

storage

cache

events

analytics

AI

exports

attachments

---

# ADR-007 — Analytics Separation

## Decision

Analytics infrastructure separated from transactional truth.

---

## Rationale

Prevents:

OLTP degradation

operational instability

schema pollution

cross-domain analytical coupling

---

## Explicit Rejection

Rejected:

analytics-heavy direct transactional querying as primary architecture

---

# ADR-008 — Central Attachment Infrastructure

## Decision

Attachments are governed platform infrastructure.

---

## Rationale

Prevents:

scattered file ownership

unsafe public exposure

authorization inconsistency

evidence governance fragmentation

---

## Explicit Rejection

Rejected:

per-domain unmanaged file URL storage

---

# ADR-009 — Event-Driven Internal Collaboration

## Decision

Internal async collaboration uses governed domain events.

---

## Rationale

Benefits:

bounded decoupling

async resilience

notification orchestration

analytics projection support

integration orchestration

---

## Constraints

Events communicate facts.

Not commands.

---

# ADR-010 — At-Least-Once Event Delivery

## Decision

Event delivery assumption:

AT-LEAST-ONCE

---

## Rationale

Operational realism.

Distributed exact-once assumptions are unsafe and misleading.

---

## Constraint

Consumers must be idempotent.

---

# ADR-011 — AI as Assistive Layer Only

## Decision

AI is assistive augmentation.

Not governance authority.

---

## Rationale

Prevents:

unsafe automation

hallucinated approvals

unaudited governance mutation

authorization bypass

---

## Explicit Rejection

Rejected:

AI autonomous operational authority

---

# ADR-012 — Offline-First Selected Operational Workflows

## Decision

Offline capability supported for defined workflows.

---

## Rationale

Operational HSE reality:

field disconnection scenarios

mobile operational workflows

site constraints

---

## Constraints

Offline support requires:

idempotency

conflict governance

tenant preservation

audit correctness

---

# ADR-013 — Central Auditability

## Decision

Critical governed actions require centralized auditability.

---

## Rationale

Enterprise governance requirement.

Security + compliance necessity.

---

## Explicit Rejection

Rejected:

best-effort audit logging

partial workflow auditability

---

# ADR-014 — Schema Domain Ownership

## Decision

Transactional truth owned by bounded domains.

---

## Rationale

Prevents:

schema chaos

ownership ambiguity

cross-domain mutation

architectural drift

---

## Explicit Rejection

Rejected:

flat mega-schema ownership model

---

# ADR-015 — Sensitive Data Segregation

## Decision

Sensitive operational domains require elevated governance.

---

Examples:

occupational health

medical restrictions

restricted investigations

---

## Rationale

Security, privacy, regulatory risk containment.

---

# ADR-016 — Provider Abstraction for AI & Integrations

## Decision

External dependency providers abstracted behind governed interfaces.

---

## Rationale

Prevents:

hard lock-in

provider drift coupling

unsafe vendor dependency assumptions

---

# ADR-017 — Shared SaaS First Deployment Evolution

## Decision

Initial deployment posture:

shared SaaS

with later enterprise deployment expansion.

---

## Rationale

Fastest controlled maturity path.

---

## Evolution Path

shared SaaS
→ hardened SaaS
→ dedicated tenant
→ private cloud
→ on-prem

---

# ADR-018 — Security-First Enterprise Constraints

## Decision

Security constraints override convenience implementation.

---

## Rationale

Enterprise trust boundary preservation.

---

# ADR-019 — Documentation Constitution Governance

## Decision

Documentation hierarchy governs implementation interpretation.

---

## Authority

See:

```text
docs/README.md
```

---

# ADR-020 — Jules Constraint Doctrine

## Decision

Jules is implementation agent, not architecture author.

---

## Constraint

Jules must implement within constitutional boundaries.

Not reinterpret architecture.

---

# ADR-021 — Zero Duplicate Doctrine

## Decision

Documentation ownership remains singular.

---

## Rationale

Prevents ambiguity.

Prevents hallucinated conflicting implementation.

---

# 3. Final Decision Authority

If implementation convenience conflicts with accepted architecture decisions:

accepted architecture decisions win.

---

# 4. Canonical Authority Statement

This document defines accepted architectural decisions and rationale.

---

