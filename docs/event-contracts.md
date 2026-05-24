# Aegis Event Contracts
## Canonical Event Architecture, Async Messaging & Event Governance Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Architecture Governance / Async Messaging Authority  
**Authority Level:** Canonical Event Contract Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical event architecture governance for Aegis.

It governs:

- event doctrine
- event taxonomy
- event naming conventions
- payload contract rules
- tenant propagation requirements
- delivery assumptions
- idempotency expectations
- versioning doctrine
- replay governance
- consumer expectations
- async security constraints
- observability requirements
- AI-assisted implementation event constraints

---

## 1.2 Scope

Applies to:

- internal async messaging
- domain event publishing
- background workers
- integration event handling
- analytics projections
- notification orchestration
- workflow async coordination
- recovery replay behavior

---

# 2. Core Event Doctrine

# 2.1 Core Principle

Events communicate completed facts.

Events do NOT communicate imperative commands.

---

# 2.2 Constitutional Reference

Architectural authority:

```text
architecture-decision-records.md
```

ADR reference:

event-driven internal collaboration

at-least-once delivery doctrine

---

# 2.3 Explicit Prohibitions

Forbidden event semantics:

imperative commands

hidden workflow orchestration hacks

generic async RPC disguised as events

tenant-blind async messaging

---

# 3. Event Taxonomy

Canonical event types:

DOMAIN_EVENT

INTEGRATION_EVENT

SYSTEM_EVENT

AUDIT_EVENT

---

# 4. Event Type Definitions

# 4.1 DOMAIN_EVENT

Definition:

Business fact emitted by authoritative domain ownership.

---

Examples:

IncidentReported

PermitApproved

CAPAClosed

HazardVerified

DocumentPublished

---

# 4.2 INTEGRATION_EVENT

Definition:

Governed async representation of integration-originated or integration-targeted activity.

---

Examples:

WebhookValidated

IdentitySyncCompleted

NotificationDeliveryFailed

---

# 4.3 SYSTEM_EVENT

Definition:

Operational platform lifecycle events.

---

Examples:

QueueConsumerRestarted

ConfigReloaded

ProviderFallbackActivated

---

# 4.4 AUDIT_EVENT

Definition:

Governance evidence event.

Distinct from operational messaging.

---

Constraint:

AUDIT_EVENT is not generic business async workflow messaging.

---

# 5. Naming Doctrine

# 5.1 Core Rule

Event names describe facts.

Past-tense domain facts preferred.

---

Good:

```text
IncidentReported
IncidentClosed
PermitActivated
CAPAAssigned
ExportGenerated
```

---

Bad:

```text
ProcessIncident
HandlePermit
DoCAPAStuff
WorkflowManagerAction
```

---

# 5.2 Naming Format

Canonical naming:

PascalCase domain fact names

---

# 5.3 Ambiguity Prohibition

Generic vague names prohibited.

---

Bad examples:

```text
Updated
Changed
Processed
Handled
Triggered
```

---

# 6. Canonical Event Envelope

# 6.1 Core Principle

All governed events use explicit canonical envelope structure.

---

# 6.2 Canonical Envelope

```json
{
  "event_id": "uuid",
  "event_type": "IncidentReported",
  "event_category": "DOMAIN_EVENT",
  "event_version": 1,
  "occurred_at": "ISO-8601 timestamp",
  "tenant_id": "tenant identifier",
  "correlation_id": "trace correlation id",
  "producer": "domain/service identifier",
  "payload": {}
}
```

---

# 6.3 Mandatory Fields

Required:

event_id

event_type

event_category

event_version

occurred_at

tenant_id

correlation_id

producer

payload

---

# 6.4 Event ID Doctrine

event_id must be globally unique.

---

Purpose:

deduplication

idempotency

audit correlation

traceability

---

# 6.5 Correlation Doctrine

correlation_id required for end-to-end observability.

---

Governed by:

```text
observability-runbook.md
```

---

# 7. Tenant Propagation Doctrine

# 7.1 Core Rule

Tenant identity must propagate across governed async flows.

---

# 7.2 Mandatory Rule

tenant_id required for tenant-scoped events.

---

# 7.3 Cross-Tenant Safety Rule

Loss of tenant context is critical architectural failure.

---

# 7.4 Forbidden Behaviors

Forbidden:

tenant-blind event publishing

consumer reconstruction guesswork

shared async tenant ambiguity

---

# 8. Payload Doctrine

# 8.1 Core Principle

Payloads must be explicit, deterministic, and governed.

---

# 8.2 Payload Philosophy

Events communicate relevant facts.

Not arbitrary state dumps.

---

Good:

minimal relevant domain fact payload

---

Bad:

entire database entity dumps

sensitive unrestricted payload duplication

opaque unstructured blobs

---

# 8.3 Sensitive Payload Governance

Payload sensitivity must comply with:

```text
privacy-classification.md
```

---

# 9. Delivery Doctrine

# 9.1 Core Rule

Delivery assumption:

AT_LEAST_ONCE

---

# 9.2 Architectural Implication

Duplicate delivery is expected reality.

---

# 9.3 Unsafe Assumption Prohibition

Forbidden:

exactly-once fantasy assumptions

duplicate-impossible assumptions

---

# 10. Consumer Idempotency Doctrine

# 10.1 Core Principle

Consumers must be idempotent.

---

# 10.2 Duplicate Safety Rule

Duplicate event processing must not corrupt business truth.

---

Examples:

duplicate notification
→ tolerable if governed

duplicate permit approval mutation
→ unacceptable

---

# 11. Ordering Doctrine

# 11.1 Core Rule

Global ordering assumptions prohibited.

---

# 11.2 Safe Assumption

Ordering may be unreliable across distributed async boundaries.

---

# 11.3 Design Implication

Consumers must tolerate ordering imperfections where applicable.

---

# 12. Event Versioning Doctrine

# 12.1 Core Principle

Event contracts are governed interfaces.

Schema evolution must be intentional.

---

# 12.2 Version Requirement

event_version mandatory.

---

# 12.3 Compatibility Rule

Breaking schema changes require governed version evolution.

---

# 12.4 Unsafe Evolution Prohibition

Forbidden:

silent payload mutation

schema drift without versioning

consumer-breaking hidden contract changes

---

# 13. Replay Doctrine

# 13.1 Core Principle

Replay is governed operational behavior.

Not casual retry improvisation.

---

# 13.2 Supported Replay Scenarios

Examples:

projection rebuild

dead-letter recovery

controlled recovery replay

analytics regeneration

integration reconciliation

---

# 13.3 Replay Constraints

Replay must preserve:

tenant isolation

idempotency

workflow correctness

audit consistency

authorization boundaries

---

# 13.4 Unsafe Replay Prohibition

Forbidden:

blind production replay

tenant-blind replay

unsafe workflow mutation replay

duplicate destructive side effects

---

# 14. Consumer Doctrine

# 14.1 Core Principle

Consumers react safely to facts.

They do not become hidden workflow gods.

---

# 14.2 Consumer Responsibilities

Consumers may:

update projections

send notifications

trigger governed integrations

create derived operational support behavior

---

# 14.3 Consumer Prohibitions

Forbidden:

authoritative workflow mutation without governance

cross-domain ownership takeover

tenant context reconstruction guesswork

unsafe side-effect chains

---

# 15. Dead-Letter Governance

# 15.1 Core Principle

Failed events require governed handling.

Not silent disappearance.

---

# 15.2 Required Handling

Support:

dead-letter capture

failure visibility

retry governance

operator investigation

controlled replay

---

# 15.3 Silent Loss Prohibition

Forbidden:

event drop without visibility

silent poison discard

hidden consumer failure loops

---

# 16. Analytics Projection Doctrine

# 16.1 Core Principle

Analytics projections are derived truth.

Not authoritative transactional truth.

---

# 16.2 Allowed Behavior

Examples:

KPI projection updates

trend aggregation

analytics snapshot enrichment

derived reporting materialization

---

# 16.3 Prohibited Behavior

Forbidden:

analytics projection mutating authoritative operational truth

cross-tenant projection contamination

tenant-blind aggregation

---

# 17. Integration Event Doctrine

# 17.1 Core Principle

Integration-originated async activity is governed trust-boundary behavior.

---

# 17.2 Requirements

Required:

validation

schema governance

tenant attribution where applicable

replay safety

source authenticity governance

---

# 17.3 Unsafe Trust Prohibition

Forbidden:

blind trust of integration payloads

unsafe business truth mutation

tenant attribution guessing

---

# 18. Security Doctrine

# 18.1 Core Principle

Async messaging is security-relevant infrastructure.

---

# 18.2 Required Controls

Examples:

publisher governance

consumer validation

tenant propagation enforcement

schema validation

event authenticity governance

replay protection where applicable

---

# 18.3 Threat Reference

Threat assumptions governed by:

```text
threat-model.md
```

---

# 19. Sensitive Data Doctrine

# 19.1 Core Principle

Event payloads must minimize sensitive exposure.

---

# 19.2 Prohibited Behaviors

Forbidden:

medical payload over-sharing

raw secret propagation

unbounded personal data duplication

sensitive state dumping

---

# 20. Observability Integration

# 20.1 Core Principle

Async behavior must be observable.

---

# 20.2 Required Signals

Examples:

publish failures

consumer failures

dead-letter volume

retry storms

tenant propagation anomalies

poison event detection

replay activity

processing latency

duplicate rejection

---

Authority:

```text
observability-runbook.md
```

---

# 21. Audit Interaction Doctrine

# 21.1 Core Principle

Governance-relevant async behavior may require audit evidence.

---

Examples:

privileged async workflow actions

sensitive async operations

security-relevant async events

---

# 21.2 Distinction Rule

Audit evidence remains distinct from operational event messaging.

---

# 22. Testing Doctrine

# 22.1 Required Validation

Where applicable:

consumer idempotency

schema compatibility

tenant propagation

replay safety

dead-letter handling

ordering tolerance

security validation

integration authenticity validation

---

Authority:

```text
testing-strategy.md
```

---

# 23. Jules Async Constraints

# 23.1 Explicit Prohibitions

Jules must NOT:

use events as hidden command bus

assume exactly-once delivery

ignore duplicate delivery

ignore tenant propagation

silently drop failed events

blindly replay production traffic

mutate authoritative workflow state from unsafe consumers

dump sensitive payloads into events

implement tenant-blind projections

assume global ordering

---

# 23.2 Missing Requirement Rule

If async ambiguity exists:

choose safer deterministic behavior

preserve tenant isolation

require clarification

---

# 24. Canonical Authority Rule

This document governs async messaging and event contract behavior for Aegis.

All implementations must comply.

---

