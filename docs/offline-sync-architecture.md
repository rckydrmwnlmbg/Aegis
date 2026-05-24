# Aegis Offline Sync Architecture
## Canonical Offline-First Operational Synchronization Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Architecture / Mobile / Platform / Engineering Governance  
**Authority Level:** Canonical Offline Architecture Reference

---

# 1. Executive Purpose

## 1.1 Architectural Purpose

Aegis operates in industrial environments where reliable connectivity cannot be assumed.

Offline capability is therefore a core operational requirement.

However, offline capability must preserve governance integrity, auditability, and transactional correctness.

---

## 1.2 Canonical Doctrine

Aegis is:

**offline-first operationally**

but

**server-authoritative transactionally**

Meaning:

Clients may stage operational actions.

The server remains authoritative source-of-truth.

Offline clients do not become autonomous transactional authorities.

---

# 2. Offline Architecture Philosophy

## 2.1 Design Principles

Mandatory principles:

- durable local persistence
- explicit sync state visibility
- retry-safe operations
- idempotent mutation handling
- deterministic reconciliation
- attachment recovery
- safe degradation
- secure local handling
- observable failures

---

## 2.2 Anti-Fantasy Rule

"Offline-first" shall not be treated as vague marketing language.

Every supported offline workflow must be explicitly defined.

---

# 3. Offline Capability Classification

# 3.1 Fully Supported Offline Workflows

These workflows shall support full offline staging.

Supported:

- incident draft creation
- incident submission staging
- near miss capture
- hazard observation
- inspection execution
- inspection findings
- evidence capture
- JSA drafting
- toolbox attendance staging
- selected document access (cached policy-defined)

---

# 3.2 Partially Supported Offline Workflows

Supported with constrained governance:

- permit drafting
- permit preparation
- permit evidence staging
- JSA-to-permit preparation linkage

Real-time approval authority remains server-governed.

---

# 3.3 Restricted Offline Workflows

Not supported as autonomous offline authority:

- final permit approval
- CAPA closure approval
- audit closure
- compliance adjudication
- sensitive admin governance
- authoritative identity changes

---

# 4. Client Persistence Architecture

# 4.1 Local Persistence Doctrine

Offline clients require durable local persistence.

Ephemeral in-memory-only offline handling is prohibited.

---

# 4.2 Local Storage Requirements

Must support:

- draft persistence
- queued mutation persistence
- attachment staging
- sync metadata
- crash recovery state

---

# 4.3 Local Record Metadata

Offline records must maintain:

- local identifier
- tenant context
- actor context
- workflow type
- creation timestamp
- sync status
- retry metadata
- conflict markers where applicable

---

# 4.4 Device Crash Recovery

Client restarts must preserve:

- drafts
- queued actions
- staged attachments
- retry state

Silent loss prohibited.

---

# 5. Synchronization Architecture

# 5.1 Sync Doctrine

Synchronization is governed mutation reconciliation between client and server.

---

# 5.2 Sync Model

Canonical sync model:

client mutation staging
→ queued operation persistence
→ authenticated sync attempt
→ server validation
→ transactional acceptance / rejection
→ client reconciliation

---

# 5.3 Sync Directionality

Supported:

Outbound:
client → server mutations

Inbound:
server → client reconciliation

Bidirectional sync must remain server-authoritative.

---

# 5.4 Ordered Mutation Handling

Where workflow ordering matters, sync must preserve deterministic sequencing.

Examples:

- incident create before attachment linkage
- hazard create before closure attempt
- permit draft before supporting updates

---

# 6. Idempotency Architecture

# 6.1 Idempotency Doctrine

Offline systems must assume retries.

Retries must not create duplicate truth.

---

# 6.2 Mandatory Idempotent Operations

Required for:

- incident submissions
- hazard submissions
- near miss submissions
- inspection submissions
- attachment registration
- queued workflow mutations

---

# 6.3 Duplicate Prevention

System must defend against:

- repeated sync attempts
- network retry duplication
- interrupted acknowledgment retries
- device reconnect replay

---

# 7. Conflict Resolution Architecture

# 7.1 Conflict Doctrine

Conflicts are expected.

Conflict handling must be explicit.

Silent overwrite prohibited.

---

# 7.2 Conflict Categories

Examples:

- concurrent edits
- stale closure attempts
- stale approval attempts
- reopened workflow collisions
- duplicate entity submissions
- attachment reference conflicts

---

# 7.3 Resolution Strategy

Supported strategies:

- server wins
- client retry required
- manual reconciliation required
- merge (only where explicitly safe)

---

# 7.4 Governance Rule

Governed workflows shall favor correctness over convenience.

Unsafe auto-merge prohibited.

---

# 8. Attachment Synchronization Architecture

# 8.1 Attachment Doctrine

Attachments are first-class sync concerns.

Not side concerns.

---

# 8.2 Attachment Lifecycle

Canonical lifecycle:

captured
→ staged locally
→ queued
→ upload attempt
→ acknowledged
→ linked to authoritative record

---

# 8.3 Attachment Requirements

Mandatory:

- resumable uploads
- metadata preservation
- retry-safe upload
- integrity validation
- linkage reconciliation

---

# 8.4 Orphan Handling

The system must detect and govern orphaned attachments.

Examples:

- uploaded but unlinked
- linked locally but rejected server-side
- partially synced media

---

# 9. Authentication During Sync

# 9.1 Sync Auth Doctrine

All sync operations require authenticated governed execution.

---

# 9.2 Token Expiry Handling

If auth expires:

system must:

- pause sync
- preserve queued data
- require re-authentication
- resume safely after recovery

---

# 9.3 Forbidden Behavior

Prohibited:

- silent unauthenticated sync
- unsafe cached privilege assumptions

---

# 10. Failure Handling Architecture

# 10.1 Failure Doctrine

Failures must be explicit and recoverable.

Silent failure prohibited.

---

# 10.2 Failure Categories

Expected failures:

- network interruption
- timeout
- auth expiry
- partial upload failure
- duplicate rejection
- validation rejection
- server conflict rejection
- storage exhaustion
- corrupted queue entry

---

# 10.3 Recovery Expectations

System must support:

- retry
- queue recovery
- item isolation
- partial sync continuation
- safe rollback where applicable

---

# 10.4 Poison Queue Handling

Invalid repeated-failure items must not block healthy sync queues indefinitely.

---

# 11. Sync Visibility UX Doctrine

# 11.1 User Trust Doctrine

Users must understand sync state.

Hidden sync uncertainty destroys trust.

---

# 11.2 Mandatory Sync States

Visible states:

- draft
- queued
- syncing
- synced
- failed
- retry pending
- conflict
- rejected

---

# 11.3 Failure Messaging

Failures must provide actionable visibility.

Opaque “something went wrong” behavior discouraged.

---

# 12. Security Architecture for Offline

# 12.1 Offline Security Doctrine

Offline support increases risk surface.

Device trust cannot be assumed.

---

# 12.2 Local Data Controls

Required:

- protected local persistence
- minimized sensitive retention
- controlled attachment caching
- token governance

---

# 12.3 Sensitive Data Restrictions

Sensitive domains may restrict offline persistence.

Examples:

- occupational health
- restricted investigations
- privileged analytics

---

# 12.4 Replay Protection

Sync must resist:

- duplicate replay
- stale mutation replay
- malicious retransmission

---

# 13. Observability Requirements

# 13.1 Sync Observability Doctrine

Sync failures must be diagnosable.

---

# 13.2 Required Telemetry

Required visibility:

- sync attempts
- sync successes
- sync failures
- retry counts
- queue depth
- attachment failures
- conflict frequency
- auth-related sync failures

---

# 13.3 Operational Monitoring

Operational teams must be able to detect sync degradation.

---

# 14. Performance Considerations

# 14.1 Performance Doctrine

Offline sync must tolerate:

- poor networks
- intermittent connectivity
- large media uploads
- reconnect bursts

---

# 14.2 Burst Recovery

System must tolerate mass reconnection scenarios without unsafe duplicate creation.

---

# 15. AI Interaction with Offline Workflows

# 15.1 AI Doctrine

AI augmentation may assist offline workflows where locally supported.

AI is non-authoritative.

---

# 15.2 Sync Governance

Post-sync AI enrichment must not overwrite governed user truth silently.

---

# 16. Testing Requirements

Validation required for:

- device restarts
- interrupted uploads
- repeated retries
- duplicate replay
- auth expiry
- stale edits
- conflict scenarios
- queue corruption
- reconnection bursts

---

# 17. Failure Definitions

Critical failures include:

- silent local data loss
- duplicate incident creation from retry
- attachment orphan leakage
- hidden sync failure
- stale unsafe overwrite
- unauthorized sync execution

---

# 18. Canonical Authority Rule

This document governs:

- mobile architecture
- sync engine implementation
- API idempotency design
- attachment upload behavior
- offline UX behavior
- testing strategy

Conflicts resolved in favor of offline integrity doctrine.

---

