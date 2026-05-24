# Aegis Privacy Classification
## Canonical Data Sensitivity, Handling & Privacy Governance Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Privacy Governance / Data Protection Authority  
**Authority Level:** Canonical Data Sensitivity Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical privacy and data classification governance for Aegis.

It governs:

- data sensitivity classification
- access expectations
- export governance
- analytics privacy boundaries
- AI privacy constraints
- logging privacy requirements
- integration data sharing constraints
- masking expectations
- auditability requirements
- AI-assisted implementation privacy constraints

---

## 1.2 Scope

Applies to:

- APIs
- persistence
- attachments
- analytics
- AI systems
- logs
- exports
- integrations
- admin operations
- mobile sync
- background processing
- generated artifacts

---

# 2. Core Privacy Doctrine

# 2.1 Core Principle

All data is not equal.

Sensitivity-based governance is mandatory.

---

# 2.2 Least Exposure Principle

Systems must expose the minimum necessary data.

---

# 2.3 Need-to-Know Principle

Access is governed by operational necessity.

Not convenience.

---

# 2.4 Privacy-by-Architecture Principle

Privacy controls must be architectural.

Not optional implementation afterthoughts.

---

# 2.5 Constitutional Authority

This document governs sensitivity classification and privacy handling expectations.

---

# 3. Canonical Classification Model

Canonical classification levels:

PUBLIC

INTERNAL

CONFIDENTIAL

RESTRICTED

HIGHLY_RESTRICTED

---

# 4. Classification Definitions

# 4.1 PUBLIC

Definition:

Information approved for unrestricted disclosure.

---

Examples:

marketing collateral

public documentation

published public product materials

public training references

---

Expected controls:

minimal governance

integrity protection

basic change governance

---

# 4.2 INTERNAL

Definition:

Operational internal information with low privacy sensitivity.

Not intended for public disclosure.

---

Examples:

general procedures

non-sensitive operational checklists

internal reference material

generic training materials

---

Expected controls:

authenticated access

basic tenant governance

controlled sharing

---

# 4.3 CONFIDENTIAL

Definition:

Tenant-sensitive business operational information.

Unauthorized disclosure causes meaningful business risk.

---

Examples:

incident records

hazard reports

permits

JSA

CAPA records

audit findings

compliance obligations

controlled internal documents

contractor governance records

training completion records

---

Expected controls:

strict authorization

tenant isolation

auditability

governed exports

limited integrations

---

# 4.4 RESTRICTED

Definition:

High-sensitivity operational information requiring elevated governance.

Unauthorized disclosure causes severe business, legal, privacy, or trust impact.

---

Examples:

security investigations

disciplinary investigations

serious incident investigations

high-risk compliance evidence

regulated audit evidence

privileged operational investigations

restricted export datasets

---

Expected controls:

enhanced authorization

heightened auditability

strict export governance

limited AI access

explicit operational need

---

# 4.5 HIGHLY_RESTRICTED

Definition:

Highest sensitivity regulated or privacy-critical information.

Unauthorized disclosure causes severe privacy, regulatory, ethical, or legal harm.

---

Examples:

occupational health records

medical restrictions

fitness-for-duty sensitive records

regulated personal health data

protected personal identifiers where explicitly governed

high-risk privacy-regulated evidence

---

Expected controls:

strict least privilege

explicit role governance

enhanced auditing

strong export restrictions

AI denial by default

minimal retention

special handling governance

---

# 5. Aegis Domain Classification Mapping

# 5.1 Incident Domain

Default classification:

CONFIDENTIAL

Escalates to:

RESTRICTED

Examples:

serious investigations

security-sensitive incidents

---

# 5.2 Hazard Domain

Default classification:

CONFIDENTIAL

---

# 5.3 Permit Domain

Default classification:

CONFIDENTIAL

---

# 5.4 JSA Domain

Default classification:

CONFIDENTIAL

---

# 5.5 CAPA Domain

Default classification:

CONFIDENTIAL

May escalate:

RESTRICTED

---

# 5.6 Audit Domain

Default classification:

CONFIDENTIAL

May escalate:

RESTRICTED

---

# 5.7 Compliance Domain

Default classification:

CONFIDENTIAL

May escalate:

RESTRICTED

---

# 5.8 Controlled Documents

Classification depends on document governance.

Possible:

INTERNAL
CONFIDENTIAL
RESTRICTED

---

# 5.9 Contractor Governance Records

Default classification:

CONFIDENTIAL

---

# 5.10 Occupational Health

Default classification:

HIGHLY_RESTRICTED

---

# 5.11 Attachments

Inheritance rule:

attachments inherit governing parent classification.

---

Example:

incident photo
→ incident classification

medical attachment
→ HIGHLY_RESTRICTED

---

# 5.12 Analytics Data

Derived analytics classification depends on underlying sensitivity.

---

Examples:

aggregated tenant KPI dashboard
→ CONFIDENTIAL

health-sensitive derived analytics
→ HIGHLY_RESTRICTED or RESTRICTED

---

# 5.13 AI Interaction Metadata

Classification depends on included contextual sensitivity.

Never assumed low-risk by default.

---

# 5.14 Logs / Telemetry

Classification depends on content.

Unsafe sensitive logging prohibited.

---

# 6. Access Governance

# 6.1 Core Rule

Access must align with sensitivity classification.

---

# 6.2 Access Expectations by Classification

PUBLIC

broad access permitted

---

INTERNAL

authenticated governed access

---

CONFIDENTIAL

strict role-based access

tenant isolation mandatory

---

RESTRICTED

elevated authorization controls

explicit operational justification

heightened auditability

---

HIGHLY_RESTRICTED

strict least privilege

explicit governed access

sensitive role restriction

enhanced monitoring

---

# 6.3 Authority Source

Authorization governed by:

```text
rbac-matrix.md
```

---

# 7. Export Governance

# 7.1 Core Principle

Export sensitivity follows data sensitivity.

---

# 7.2 Export Rules by Classification

PUBLIC

generally unrestricted

---

INTERNAL

governed authenticated export

---

CONFIDENTIAL

explicit export authorization required

auditability required

---

RESTRICTED

heightened export governance required

justification may be required

strict auditability

---

HIGHLY_RESTRICTED

strongly constrained export

default denial unless explicitly governed

enhanced approval/audit expectations

---

# 7.3 Bulk Export Governance

Broad-scope exports require stricter governance.

---

# 8. AI Privacy Governance

# 8.1 Core Rule

AI access respects sensitivity classification.

---

# 8.2 AI Access Expectations

PUBLIC

allowed

---

INTERNAL

allowed with governance

---

CONFIDENTIAL

authorization-aware access only

tenant isolation mandatory

---

RESTRICTED

limited AI access only

explicit governance required

---

HIGHLY_RESTRICTED

denied by default

unless explicitly governed exceptional capability

---

# 8.3 Sensitive AI Doctrine

Sensitive AI access must not be assumed safe.

---

# 9. Analytics Privacy Governance

# 9.1 Core Principle

Analytics access does not weaken privacy classification.

---

# 9.2 Derived Data Rule

Aggregation does not automatically declassify data.

---

Bad assumption:

aggregated = automatically safe

Prohibited.

---

# 9.3 Sensitive Analytics

Sensitive-derived analytics require privacy governance.

---

# 10. Logging Governance

# 10.1 Core Rule

Logs are governed data.

---

# 10.2 Logging Restrictions by Sensitivity

PUBLIC

generally acceptable

---

INTERNAL

controlled operational logging

---

CONFIDENTIAL

minimal necessary logging

avoid raw payload dumping

---

RESTRICTED

strict minimization

avoid sensitive payload logging

---

HIGHLY_RESTRICTED

payload logging prohibited unless explicitly governed emergency scenarios

---

# 10.3 Explicit Prohibitions

Forbidden:

medical payload logging

raw auth token logging

full sensitive export payload logging

secret leakage

---

# 11. Masking & Data Minimization

# 11.1 Core Principle

Only minimum required data should be exposed.

---

# 11.2 Masking Expectations

Where appropriate:

partial identifier masking

sensitive attribute suppression

summary views over raw sensitive exposure

redacted audit visibility

---

# 12. Integration Sharing Governance

# 12.1 Core Rule

Data sharing with integrations must preserve classification controls.

---

# 12.2 Sharing Expectations

PUBLIC

low restriction

---

INTERNAL

governed integration sharing

---

CONFIDENTIAL

strict contract-governed sharing

minimum necessary principle

---

RESTRICTED

limited explicit sharing only

---

HIGHLY_RESTRICTED

default denial unless explicitly governed

---

# 13. Storage Governance

# 13.1 Core Rule

Storage controls should align with classification.

---

# 13.2 Expectations

CONFIDENTIAL+

tenant isolation mandatory

---

RESTRICTED+

enhanced access governance

---

HIGHLY_RESTRICTED

strongest handling expectations

---

# 14. Sync Governance

# 14.1 Core Principle

Offline sync must preserve privacy governance.

---

# 14.2 Restrictions

Sensitive data must not lose governance during disconnected workflows.

---

# 14.3 Device Risk Awareness

Client device compromise is explicit risk.

---

# 15. Administrative Privacy Constraints

# 15.1 Tenant Admin Rule

Tenant admin does not automatically gain sensitive visibility.

---

# 15.2 Platform Operator Rule

Platform operations do not automatically gain unrestricted data access.

---

# 15.3 Sensitive Role Separation

Sensitive domains require explicit governance.

---

# 16. Auditability Requirements

# 16.1 Sensitive Audit Expectations

Audit required for sensitive access, exports, exceptional visibility, and privileged access.

---

# 17. Retention Governance Reference

Retention governed by:

```text
data-retention-policy.md
```

---

Classification does not imply indefinite retention.

---

# 18. Incident Response Privacy Reference

Privacy incidents governed by:

```text
incident-response-playbook.md
```

---

# 19. Jules Privacy Constraints

# 19.1 Explicit Prohibitions

Jules must NOT:

treat all data equally

grant tenant admin unrestricted sensitive access

allow HIGHLY_RESTRICTED AI access by default

log sensitive payloads

export sensitive data without governance

declassify data through aggregation assumption

ignore attachment inheritance rules

bypass privacy controls for convenience

---

# 19.2 Missing Requirement Rule

If privacy ambiguity exists:

choose stricter interpretation

require clarification

---

# 20. Canonical Authority Rule

This document governs privacy classification and handling expectations.

All implementations must comply.

---

