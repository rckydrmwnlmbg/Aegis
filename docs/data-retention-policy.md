# Aegis Data Retention Policy
## Canonical Data Lifecycle, Archival, Legal Hold & Deletion Governance
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Data Governance / Compliance Authority  
**Authority Level:** Canonical Retention Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical data retention governance for Aegis.

It governs:

- data lifecycle states
- retention ownership
- archival governance
- deletion governance
- legal hold doctrine
- retention override governance
- attachment lifecycle inheritance
- AI interaction retention
- audit log retention
- export lifecycle governance
- backup interaction expectations
- AI-assisted implementation retention constraints

---

## 1.2 Scope

Applies to:

- transactional records
- attachments
- exports
- logs
- analytics projections
- AI interaction metadata
- integration records
- audit records
- compliance records
- sensitive records
- archived records
- backups (governance interaction only)

---

# 2. Core Retention Doctrine

# 2.1 Core Principle

Data lifecycle must be governed.

Retention is intentional.

---

# 2.2 Explicit Anti-Chaos Rule

Forbidden assumptions:

retain everything forever

delete arbitrarily

tenant-specific hidden retention hacks

silent destructive cleanup

---

# 2.3 Retention ≠ Access

Retention state does not weaken access governance.

Archived sensitive data remains sensitive.

---

# 2.4 Constitutional Authority

This document governs data lifecycle behavior.

---

# 3. Canonical Lifecycle States

Canonical states:

ACTIVE

ARCHIVED

RESTRICTED_ARCHIVE

LEGAL_HOLD

PENDING_DELETION

PURGED

---

# 4. Lifecycle Definitions

# 4.1 ACTIVE

Definition:

Operationally active governed data.

---

Characteristics:

normal governed access

operational mutation allowed where workflow permits

full authorization enforcement

---

# 4.2 ARCHIVED

Definition:

Operationally inactive retained data.

Still governed.

---

Characteristics:

read-oriented access

reduced mutation expectations

authorization preserved

tenant isolation preserved

auditability preserved

---

# 4.3 RESTRICTED_ARCHIVE

Definition:

Archived high-sensitivity retained data requiring elevated controls.

---

Examples:

serious investigations

regulated compliance evidence

sensitive privacy records

high-risk audit evidence

---

Characteristics:

enhanced access controls

heightened auditability

strict export governance

---

# 4.4 LEGAL_HOLD

Definition:

Retention suspension due to legal, regulatory, investigative, or governance hold.

---

Characteristics:

purge prohibited

retention timers suspended

special governance required

auditability mandatory

---

# 4.5 PENDING_DELETION

Definition:

Governed retention expiry awaiting destruction execution.

---

Characteristics:

deletion workflow governance

final validation expectations

hold validation required

---

# 4.6 PURGED

Definition:

Authoritative governed destruction completed.

---

Characteristics:

operational access impossible

authoritative record removed

governed destruction complete

---

# 5. Retention Governance Principles

# 5.1 Least Retention Principle

Data should not be retained longer than governed necessity.

---

# 5.2 Compliance Preservation Principle

Retention must preserve enterprise governance obligations.

---

# 5.3 Sensitive Minimization Principle

Sensitive data should not persist indefinitely by default.

---

# 5.4 Hold Supremacy Principle

Legal hold overrides normal lifecycle progression.

---

# 6. Domain Retention Ownership

# 6.1 Ownership Rule

Retention policy follows authoritative data ownership.

---

Examples:

incident records
→ incident domain ownership

permit records
→ permit domain ownership

occupational health
→ sensitive domain governance

attachments
→ inherited ownership

---

# 6.2 Cross-Domain Mutation Prohibition

Non-owning domains must not arbitrarily destroy authoritative records.

---

# 7. Default Baseline Retention Model

# 7.1 Core Rule

Retention durations are governed baseline defaults.

Jurisdictional or tenant compliance overrides may apply.

---

# 7.2 Duration Governance Doctrine

Durations below are baseline policy defaults.

Not universal legal guarantees.

---

# 8. Transactional Record Baselines

# 8.1 Incident Records

Default baseline:

7 years minimum retention baseline

---

Lifecycle:

ACTIVE
→ ARCHIVED
→ PURGE governed by policy

---

Legal hold may suspend destruction.

---

# 8.2 Hazard Records

Default baseline:

5 years minimum baseline

---

# 8.3 Permit Records

Default baseline:

5 years minimum baseline

---

# 8.4 JSA Records

Default baseline:

5 years minimum baseline

---

# 8.5 CAPA Records

Default baseline:

5–7 years baseline depending governance

---

# 8.6 Audit Records

Default baseline:

7 years minimum baseline

---

# 8.7 Compliance Records

Default baseline:

7+ years baseline depending obligation class

---

# 8.8 Controlled Documents

Retention governed by document class and supersession policy.

---

# 8.9 Contractor Governance Records

Default baseline:

contract lifecycle + governed retention window

---

# 8.10 Training Records

Default baseline:

employment relevance + governed retention baseline

---

# 8.11 Occupational Health Records

Separate elevated retention governance required.

Longer retention may apply depending regulatory context.

---

High sensitivity remains mandatory.

---

# 9. Attachment Retention Doctrine

# 9.1 Inheritance Rule

Attachments inherit parent retention governance.

---

Examples:

incident photo
→ incident retention

audit evidence
→ audit retention

medical attachment
→ occupational health retention

---

# 9.2 Orphan Attachment Prohibition

Unowned orphan governed attachments prohibited.

---

# 10. Logs & Telemetry Retention

# 10.1 Core Rule

Operational logs are governed records.

Not disposable debugging artifacts.

---

# 10.2 Baseline Retention

Application operational logs:

90–365 days baseline depending operational class

---

Security-relevant logs:

minimum 1 year baseline

longer where governance requires

---

Sensitive audit-relevant logs:

governed elevated retention

---

# 10.3 Privacy Constraints

Retention must comply with:

```text
privacy-classification.md
```

---

# 11. Audit Trail Retention

# 11.1 Core Principle

Audit trails are governance-critical evidence.

---

# 11.2 Baseline Retention

Minimum baseline:

7 years

or governed compliance override

---

# 11.3 Purge Constraints

Audit destruction requires explicit governance.

Silent audit destruction prohibited.

---

# 12. AI Interaction Retention

# 12.1 Scope

Applies to:

AI invocation metadata

prompt metadata

retrieval context metadata

AI output references

human acceptance records

provider routing metadata

---

# 12.2 Core Rule

AI interaction retention must be governed.

---

# 12.3 Sensitive Prompt Constraints

Sensitive payload persistence minimized.

Full prompt retention not universally required.

---

# 12.4 Baseline Retention

Operational AI telemetry:

90–365 days baseline

governed exceptions allowed

---

Sensitive AI interaction metadata:

strict minimization required

---

# 13. Export Retention

# 13.1 Core Principle

Generated exports are governed lifecycle artifacts.

---

# 13.2 Baseline

Ephemeral exports preferred.

---

Recommended baseline:

7–30 days

unless governed preservation required

---

# 13.3 Sensitive Export Rule

Sensitive exports require shorter controlled retention where feasible.

---

# 13.4 Unbounded Export Persistence Prohibition

Forbidden.

---

# 14. Analytics Retention

# 14.1 Core Principle

Derived analytics are governed data.

---

# 14.2 Baseline

Retention depends on analytical purpose.

Examples:

rolling KPI aggregates

historical trend datasets

forecast support data

---

# 14.3 Privacy Rule

Derived analytics do not automatically become low-risk.

---

# 15. Integration Retention

# 15.1 Scope

Applies to:

integration payload metadata

delivery records

connector execution traces

mapping failures

reconciliation traces

---

# 15.2 Baseline

Governed operational baseline:

90 days to 2 years depending integration class

---

Sensitive integrations may require stricter governance.

---

# 16. Backup Interaction Doctrine

# 16.1 Core Principle

Backup existence does not invalidate retention governance.

---

# 16.2 Practical Doctrine

Purged operational records may remain in backup media until governed backup lifecycle expiry.

---

# 16.3 Restore Governance

Restoration must not accidentally resurrect expired data into active operations without governance.

---

# 17. Deletion Governance

# 17.1 Core Rule

Deletion must be governed lifecycle behavior.

---

# 17.2 Destructive Shortcut Prohibition

Forbidden:

arbitrary delete scripts

silent destructive cron cleanup

tenant-specific hidden purge hacks

manual untracked destruction

---

# 17.3 Deletion Preconditions

Required validation:

retention expiry reached

no active legal hold

ownership validation

policy compliance

---

# 18. Legal Hold Governance

# 18.1 Core Principle

Legal hold supersedes retention expiry.

---

# 18.2 Hold Effects

Legal hold:

suspends purge

suspends destruction timers

requires auditability

preserves governed evidence

---

# 18.3 Hold Removal

Hold release must be governed and auditable.

---

# 19. Tenant Override Governance

# 19.1 Controlled Flexibility Rule

Tenant-specific retention variation may be supported.

---

# 19.2 Allowed Variations

Examples:

longer compliance retention

jurisdictional override

tenant contractual governance

regulated operational preservation

---

# 19.3 Forbidden Variations

Forbidden:

breaking privacy doctrine

bypassing legal hold

arbitrary undocumented retention behavior

security audit destruction shortcuts

---

# 20. Sensitive Data Retention

# 20.1 Elevated Governance

Sensitive domains require stricter retention governance.

Examples:

occupational health

regulated investigations

restricted privacy records

---

# 20.2 Minimization Principle

High sensitivity does not imply indefinite retention.

---

# 21. Access During Retention

# 21.1 Core Rule

Retention state does not weaken authorization.

---

Archived records remain governed.

Restricted archives remain restricted.

---

# 22. AI Governance Reference

AI-related lifecycle behavior must comply with:

```text
ai-governance.md
```

---

# 23. Incident Response Reference

Retention-affecting incidents governed by:

```text
incident-response-playbook.md
```

---

# 24. Jules Retention Constraints

# 24.1 Explicit Prohibitions

Jules must NOT:

retain everything forever

delete arbitrarily

purge without legal hold checks

treat archived data as unrestricted

persist sensitive exports indefinitely

retain sensitive prompts indiscriminately

ignore attachment inheritance

resurrect expired data through restore shortcuts

hardcode tenant-specific destructive behavior

---

# 24.2 Missing Requirement Rule

If retention ambiguity exists:

choose safer governed retention

require clarification

never choose destructive convenience

---

# 25. Canonical Authority Rule

This document governs lifecycle retention and destruction behavior.

All implementations must comply.

---

