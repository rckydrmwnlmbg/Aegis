# Aegis Observability Runbook
## Canonical Monitoring, Telemetry, Detection & Operational Visibility Governance
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Operations Governance / Security Detection Authority  
**Authority Level:** Canonical Observability Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical observability governance for Aegis.

It governs:

- telemetry doctrine
- monitoring expectations
- alerting philosophy
- logging governance
- tracing expectations
- metrics governance
- audit signal separation
- security detection expectations
- AI observability expectations
- tenant operational visibility
- AI-assisted implementation observability constraints

---

## 1.2 Scope

Applies to:

- APIs
- workers
- databases
- queues
- storage
- integrations
- analytics infrastructure
- AI systems
- authentication dependencies
- workflow engines
- export systems
- security events
- tenant-scoped operations

---

# 2. Core Observability Doctrine

# 2.1 Core Principle

Silent failure is unacceptable.

---

# 2.2 Operational Objective

Critical failures must be detectable.

Operational degradation should be observable.

Security anomalies should be visible.

---

# 2.3 Audit Distinction Rule

Operational telemetry ≠ audit evidence.

---

Logs:

operational diagnostics

---

Audit events:

governance evidence

---

They are distinct architectural concerns.

---

# 2.4 Constitutional Authority

This document governs observability expectations.

---

# 3. Canonical Observability Pillars

Canonical observability pillars:

LOGS

METRICS

TRACES

AUDIT_EVENTS

SECURITY_SIGNALS

BUSINESS_SIGNALS

---

# 4. Pillar Definitions

# 4.1 LOGS

Purpose:

operational diagnostics

runtime visibility

failure investigation

---

# 4.2 METRICS

Purpose:

health monitoring

capacity awareness

performance visibility

SLO support

---

# 4.3 TRACES

Purpose:

request path visibility

dependency latency analysis

cross-service execution understanding

---

# 4.4 AUDIT_EVENTS

Purpose:

governed operational evidence

security-relevant business actions

compliance evidence

---

# 4.5 SECURITY_SIGNALS

Purpose:

threat detection

abuse detection

boundary violation awareness

---

# 4.6 BUSINESS_SIGNALS

Purpose:

operational workflow health visibility

business failure awareness

governed workflow observability

---

# 5. Severity Model

Canonical severities:

INFO

WARN

ERROR

CRITICAL

SECURITY_CRITICAL

---

# 6. Severity Definitions

# 6.1 INFO

Definition:

Informational operational visibility.

No immediate intervention expected.

---

# 6.2 WARN

Definition:

Potential issue requiring awareness or investigation.

---

Examples:

degraded integration retries

non-critical AI provider instability

queue lag warning

---

# 6.3 ERROR

Definition:

Operational failure requiring action.

---

Examples:

failed workflow execution

database connection failure

attachment upload failure

---

# 6.4 CRITICAL

Definition:

Severe operational degradation or service-impacting failure.

---

Examples:

major outage

queue saturation

identity dependency outage

transaction failure spike

---

# 6.5 SECURITY_CRITICAL

Definition:

Potential or confirmed security incident requiring immediate attention.

---

Examples:

tenant boundary violation signal

forged webhook detection

privilege escalation anomaly

cross-tenant AI retrieval attempt

unauthorized export anomaly

---

# 7. Logging Doctrine

# 7.1 Core Principle

Logs are structured governed telemetry.

Not console dumping.

---

# 7.2 Structured Logging Requirement

Structured logging mandatory.

---

Expected characteristics:

machine-parseable

consistent schema

correlation support

severity classification

---

# 7.3 Logging Governance

Logs must comply with:

```text
privacy-classification.md
```

---

# 7.4 Explicit Logging Prohibitions

Forbidden:

raw auth token logging

secret logging

medical payload logging

full unrestricted request dumping

credential leakage

---

# 8. Correlation Doctrine

# 8.1 Core Principle

Operational actions must be traceable.

---

# 8.2 Correlation Expectations

Support correlation identifiers for:

API requests

worker jobs

integration executions

AI invocations

workflow operations

export generation

security investigations

---

# 8.3 Tenant Correlation Rule

Telemetry must preserve tenant-aware contextual traceability.

Without violating privacy governance.

---

# 9. Metrics Doctrine

# 9.1 Core Principle

Critical platform health must be measurable.

---

# 9.2 Required Metric Domains

At minimum:

availability

latency

error rates

queue health

database health

storage health

authentication dependency health

AI provider health

integration reliability

workflow health

export performance

tenant health indicators

---

# 10. Trace Doctrine

# 10.1 Core Principle

Cross-boundary execution paths should be observable where architecture requires.

---

# 10.2 Trace Scope Examples

Examples:

API → DB

API → worker enqueue

worker → integration

AI request path

workflow orchestration

analytics query path

---

# 10.3 Trace Privacy Constraints

Traces must respect privacy governance.

---

# 11. Security Detection Doctrine

# 11.1 Core Principle

Security-relevant anomalies must generate governed visibility.

---

# 11.2 Detection Scope

Examples:

auth failure spikes

authorization denial anomalies

tenant scope violations

object enumeration patterns

forged webhook attempts

privilege misuse patterns

sensitive export anomalies

break-glass invocation

AI boundary violations

---

# 12. Business Signal Doctrine

# 12.1 Core Principle

Operational business failures must be observable.

Not only infrastructure failures.

---

# 12.2 Required Business Signal Domains

Examples:

incident workflow health

permit approval flow health

JSA review bottlenecks

CAPA overdue growth

audit remediation backlog

document approval delays

training completion anomalies

contractor onboarding failures

export generation anomalies

---

# 12.3 Workflow Stuck Detection

Governed workflows should expose stuck-state detection.

---

Examples:

permit pending approval beyond threshold

CAPA verification stalled

investigation aging anomalies

document approval stagnation

---

# 13. Tenant Health Monitoring

# 13.1 Core Principle

Multi-tenant operational visibility requires tenant-aware health detection.

---

# 13.2 Examples

Examples:

tenant-specific latency degradation

tenant-specific queue failures

tenant export anomalies

tenant integration failures

tenant workflow failures

tenant AI capability degradation

---

# 13.3 Cross-Tenant Privacy Constraint

Tenant observability must not expose other tenant intelligence.

---

# 14. AI Observability Doctrine

# 14.1 Core Principle

AI behavior must be observable as governed infrastructure.

---

# 14.2 Required AI Signals

Examples:

provider latency

provider timeout rate

provider failure rate

fallback activation

token usage

cost signals

retrieval authorization denials

sensitive retrieval attempts

unsafe output rejection

prompt injection detection indicators

tenant routing failures

---

# 14.3 Unsafe Blindness Prohibition

Forbidden:

AI execution without observability

---

# 15. Integration Observability Doctrine

# 15.1 Core Principle

External dependency behavior must be observable.

---

# 15.2 Required Signals

Examples:

webhook signature failures

delivery failures

retry storms

connector timeouts

schema validation failures

replay rejection

provider degradation

integration auth failures

---

# 16. Export Observability Doctrine

# 16.1 Core Principle

Export behavior requires governed visibility.

---

# 16.2 Required Signals

Examples:

export generation rate

large export anomalies

failed export generation

sensitive export attempts

repeated export behavior

unauthorized export denials

---

# 17. Audit Event Doctrine

# 17.1 Core Principle

Governance-relevant actions require audit evidence.

---

# 17.2 Example Audit Domains

Examples:

workflow approvals

role assignment

privileged access

tenant configuration mutation

export execution

sensitive access

break-glass invocation

AI sensitive access

legal hold changes

workflow configuration changes

---

# 17.3 Audit Integrity Rule

Audit events are evidence.

Not diagnostic convenience logs.

---

# 18. Alerting Doctrine

# 18.1 Core Principle

Actionable alerts only.

Alert fatigue is operational failure.

---

# 18.2 Alert Classes

Examples:

availability alerts

security alerts

workflow alerts

tenant degradation alerts

integration alerts

AI alerts

export abuse alerts

---

# 18.3 Escalation Expectations

SECURITY_CRITICAL requires immediate governed escalation.

---

Authority:

```text
incident-response-playbook.md
```

---

# 19. SLO / Reliability Doctrine

# 19.1 Core Principle

Operational reliability should be measurable.

---

# 19.2 Candidate SLO Domains

Examples:

API availability

workflow completion reliability

queue processing reliability

authentication dependency reliability

export success rate

critical integration reliability

---

# 19.3 AI SLO Distinction

AI capabilities may have separate lower criticality reliability objectives.

---

# 20. High Cardinality Governance

# 20.1 Core Principle

Telemetry design must avoid observability self-destruction.

---

# 20.2 Governance Constraints

Avoid uncontrolled cardinality explosion.

---

Examples:

unsafe per-user label explosion

raw request payload labels

unbounded dynamic metric dimensions

---

# 21. Storage & Retention Governance

# 21.1 Core Principle

Observability data is governed data.

---

# 21.2 Compliance References

Governed by:

```text
privacy-classification.md
data-retention-policy.md
```

---

# 21.3 Sensitive Data Constraints

Sensitive operational telemetry requires stricter minimization.

---

# 22. Detection Validation Doctrine

# 22.1 Core Principle

Undetected critical failure is observability failure.

---

# 22.2 Validation Expectations

Test detection where applicable:

tenant isolation alerts

auth anomaly alerts

webhook forgery alerts

queue saturation alerts

AI boundary alerts

export abuse alerts

workflow stall alerts

---

# 23. Jules Observability Constraints

# 23.1 Explicit Prohibitions

Jules must NOT:

use print debugging as observability architecture

log secrets

log raw sensitive payloads

omit correlation identifiers

implement tenant-blind telemetry

confuse logs with audit evidence

ignore workflow business signals

ignore AI observability

create uncontrolled telemetry cardinality

ship silent failure architecture

---

# 23.2 Missing Requirement Rule

If observability ambiguity exists:

choose stronger governed visibility

preserve privacy constraints

require clarification

---

# 24. Canonical Authority Rule

This document governs observability expectations for Aegis.

All implementations must comply.

---

