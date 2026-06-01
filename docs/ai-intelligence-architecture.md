# Aegis AI Intelligence Architecture
## AI as Platform Neural System — Capability, Boundary & Implementation Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 1.0  
**Status:** Source of Truth  
**Classification:** AI Architecture / Intelligence Governance  
**Authority Level:** Level 2 — Architecture Truth  

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical AI intelligence architecture for Aegis.

It governs:

- AI capability level definitions
- AI provider assignments per capability
- AI input and output contracts
- AI decision boundary doctrine
- human oversight requirements
- AI data access scope
- anti-hallucination AI constraints
- implementation AI truth

---

## 1.2 Core Doctrine

AI in Aegis is not a feature.

AI is the platform's neural system.

AI flows through every operational layer.

AI does not replace human judgment.

AI accelerates, warns, and eliminates administrative friction.

Final decisions always belong to HSE professionals.

---

## 1.3 AI Boundary Doctrine

```
AI may:
- Structure unstructured input
- Surface relevant patterns from historical data
- Recommend actions with supporting evidence
- Draft documents for human review
- Alert on anomalies and predicted risk
- Answer questions using company knowledge base

AI must not:
- Approve or reject operational documents autonomously
- Modify approved records without human action
- Make safety-critical decisions without human confirmation
- Access data outside tenant boundary
- Fabricate regulatory references
- Operate without audit trail
```

---

# 2. Four-Level Intelligence Architecture

## 2.1 Overview

```
LEVEL 1 — REACTIVE    (implemented)
LEVEL 2 — ASSISTIVE   (next build)
LEVEL 3 — PROACTIVE   (roadmap)
LEVEL 4 — AUTONOMOUS  (vision)
```

Each level builds on the previous.

No level may be implemented before the level below it is stable.

---

## 2.2 Level 1 — Reactive AI

### 2.2.1 Definition

AI receives input from the field and structures raw data into standardized records.

AI responds only when explicitly triggered by user action.

### 2.2.2 Capabilities

**Voice-to-Report**

```
Input:  Audio recording (30-120 seconds, Bahasa Indonesia)
Process: OpenAI Whisper → transcription
         GPT-4o-mini → structured JSON extraction
Output: Populated incident report draft (status: draft_ready)
Human step required: HSE Officer reviews and submits
```

**Photo-to-Hazard**

```
Input:  Photo from mobile camera
Process: GPT-4o Vision → hazard classification + risk level
Output: Pre-populated hazard observation form
Human step required: Field worker confirms and submits
```

**Form Intelligence**

```
Input:  User session context (role, site, shift, location)
Process: Rule-based prediction + last-used values
Output: Auto-filled non-critical form fields
Human step required: User reviews all fields before submit
```

### 2.2.3 AI Provider

```
Transcription:    OpenAI Whisper
Structuring:      GPT-4o-mini
Vision analysis:  GPT-4o (vision capable)
```

### 2.2.4 Output Contract

All Level 1 AI outputs must:

- have status field set to: `draft_ready`
- never be auto-submitted without human action
- include confidence score where applicable
- include ai_run_id for traceability

Authority for ai_run schema:

```
schema.md — ai_runs table
```

---

## 2.3 Level 2 — Assistive AI

### 2.3.1 Definition

AI actively accompanies each role with contextual knowledge.

AI answers questions using company-specific data via RAG.

AI recommends specific actions based on current operational state.

### 2.3.2 Capabilities

**HSE Copilot**

```
Input:  Natural language question from HSE Officer or Manager
        + User context (role, site, active contexts)
Process: RAG retrieval from company knowledge base
         (SOP, Permenaker, incident history, asset status,
          certification status, APAR status)
         Claude Haiku → contextual answer generation
Output: Answer with source citation + recommended actions
Human step required: User decides whether to act on recommendation
```

Copilot knowledge base sources:

```
Company SOPs (uploaded by TENANT_ADMIN)
Indonesian K3 regulations (Permenaker, PP, UU)
Aegis canonical glossary
Incident and near miss history (tenant-scoped)
Asset and APAR status (live query)
Worker certification status (live query)
Active PTW and JSA (live query)
```

**Smart CAPA Recommendation**

```
Input:  Newly submitted incident record
Process: Pattern analysis against last 90 days incident history
         Root cause classification
         Regulatory requirement lookup
         Claude Haiku → CAPA recommendation generation
Output: Suggested CAPA list with:
        - action description
        - suggested assignee (by role and site)
        - suggested due date
        - regulatory reference if applicable
        - priority classification
Human step required: HSE Officer reviews, modifies, and confirms
```

**Document Intelligence**

```
Input:  Uploaded document (SOP, Permenaker, procedure)
Process: PDF extraction → chunking → embedding → vector storage
         Claude Haiku → document understanding
Output: Document becomes queryable through Copilot
        Gap detection: AI compares document content
        against current practice records
Human step required: HSE Manager reviews gap findings
```

**Audit Assistant (SMK3)**

```
Input:  Active audit session + SMK3 criteria selection
Process: Map available company documents to each criterion
         Identify gaps in evidence
         Estimate readiness percentage per criterion
         Claude Haiku → gap narrative generation
Output: Per-criterion status with:
        - readiness percentage
        - available evidence documents
        - missing evidence list
        - suggested actions before external audit
Human step required: HSE Manager validates and adds missing evidence
```

**Photo Hazard Detection**

```
Input:  Photo submitted by field worker
Process: GPT-4o vision analysis
         Hazard classification against company HIRARC
         Regulatory matching (relevant Permenaker)
Output: Hazard type, risk level, regulatory reference,
        pre-filled hazard observation form
        If risk level HIGH or CRITICAL:
        immediate notification to HSE Officer on-duty
Human step required: HSE Officer confirms and assigns CAPA
```

### 2.3.3 AI Provider

```
RAG retrieval:       pgvector (PostgreSQL extension)
Embedding model:     OpenAI text-embedding-3-small
Answer generation:   Claude Haiku (primary)
                     GPT-4o-mini (fallback)
Vision:              GPT-4o
```

### 2.3.4 RAG Doctrine

```
Retrieval must be tenant-scoped. No cross-tenant retrieval.
Maximum context window: 8,000 tokens per query.
Source citation is mandatory in every Copilot answer.
If no relevant document found: AI must say so explicitly.
AI must not fabricate regulatory article numbers.
If regulatory reference is uncertain: AI must flag uncertainty.
```

---

## 2.4 Level 3 — Proactive AI

### 2.4.1 Definition

AI actively monitors all platform data streams without being asked.

AI generates alerts and insights before problems occur.

AI learns from historical patterns to predict risk.

### 2.4.2 Capabilities

**Anomaly Detection**

```
Input:  Continuous stream of:
        - incident and near miss records
        - hazard observation volume per area
        - CAPA completion rates
        - PTW approval patterns
        - inspection compliance rates
Process: Statistical baseline per site per time window
         Deviation detection (>2 standard deviations)
         Pattern matching against known pre-incident patterns
Output: Anomaly alert to HSE_MANAGER with:
        - detected pattern description
        - historical precedent (if exists)
        - affected area and time window
        - suggested investigation action
Human step required: HSE Manager decides whether to investigate
```

**Predictive Risk Intelligence**

```
Input:  90-day historical data:
        - incident patterns by day/shift/weather
        - near miss frequency by area
        - team experience composition
        - workload indicators
        External data:
        - BMKG weather forecast API
        - Work schedule (from Aegis shift data)
Process: Time-series pattern analysis
         Risk factor correlation
         GPT-4o → risk narrative generation
Output: Daily morning risk briefing per site:
        - predicted high-risk windows (time + area)
        - contributing factors with weight
        - specific mitigation recommendations
Human step required: HSE Manager or Officer reviews and decides
```

**Contextual Risk Score**

```
Input:  Real-time combination of:
        - Current weather (BMKG API)
        - Scheduled high-risk work today
        - Available experienced personnel (on-duty status)
        - Recent near miss frequency (last 14 days)
        - Open CAPA count in area
        - APAR inspection status in area
Process: Weighted risk formula (configurable per tenant)
         Score recalculated every 30 minutes
Output: Site risk score (0-100, color coded)
        Score displayed on HSE_MANAGER and HSE_OFFICER dashboard
        If score exceeds threshold: automatic alert
Human step required: HSE professional interprets and responds
```

**Cascade Risk Analysis**

```
Input:  Planned work schedule + current asset status + weather
Process: Multi-factor dependency analysis
         Identify converging risk factors
         Detect if single point of failure exists
Output: Cascade alert with:
        - converging factors list
        - failure scenario description
        - recommended mitigation sequence
Human step required: HSE Manager confirms mitigation actions
```

### 2.4.3 AI Provider

```
Anomaly detection:      Statistical models (internal)
                        GPT-4o-mini for narrative
Predictive risk:        GPT-4o for complex analysis
Contextual score:       Rule engine + weighted formula
Cascade analysis:       GPT-4o
External data:          BMKG API (weather)
```

### 2.4.4 Proactive AI Constraints

```
Proactive AI must not send more than 3 unprompted alerts per site per day
to avoid alert fatigue.

Alert priority levels:
CRITICAL — immediate notification (push + in-app)
HIGH     — in-app notification + morning briefing
MEDIUM   — morning briefing digest only
LOW      — weekly digest only

Threshold for each level must be configurable by TENANT_ADMIN.
```

---

## 2.5 Level 4 — Autonomous AI

### 2.5.1 Definition

AI completes complex administrative tasks independently.

Human review and approval is required before output becomes official.

AI does not act on safety-critical decisions without human confirmation.

### 2.5.2 Capabilities

**Auto-Draft PTW & JSA**

```
Input:  Work type + location + duration + assigned workers
Process: Retrieve similar approved PTW from history
         Extract hazard patterns from JSA history
         Match required controls from company HIRARC
         Claude → full PTW and JSA draft generation
Output: Complete draft PTW and JSA document
        Pre-populated with all required fields
        Flagged fields requiring human input highlighted
Human step required: HSE Officer reviews all content,
                     modifies as needed, and formally submits
```

**Laporan K3 Bulanan Otomatis (Disnaker Format)**

```
Input:  Monthly operational data:
        - All incidents and near misses
        - CAPA completion statistics
        - Inspection compliance rate
        - Training completion
        - APAR inspection status
        - PTW statistics
Process: Data aggregation and validation
         Format mapping to Disnaker standard template
         Statistical calculation (LTIR, TRIR, DART)
         Claude → narrative summary generation
Output: Complete Laporan K3 in Disnaker-compliant format
        Ready for HSE Manager digital signature
        Submission reminder to HSE Manager before deadline
Human step required: HSE Manager reviews, signs digitally,
                     and submits (or exports for manual submission)
```

**Self-Learning Knowledge Base**

```
Input:  Every closed incident investigation
        Every completed audit finding with closure evidence
        Every HSE Officer feedback on AI recommendations
Process: Feedback loop into RAG knowledge base
         Confidence weight adjustment per document
         New pattern registration for anomaly detection
Output: Continuously improving AI responses
        More accurate CAPA recommendations over time
        More precise anomaly detection thresholds
Human step required: Periodic review by HSE Manager (quarterly)
                     to validate knowledge base accuracy
```

**Regulatory Watch**

```
Input:  External regulatory monitoring (Kemnaker, BPJS updates)
Process: New regulation detection
         Impact analysis against company SOPs
         Gap identification between new regulation and current practice
         Claude → revision recommendation generation
Output: Regulatory update alert to HSE_MANAGER with:
        - affected SOPs list
        - required changes summary
        - compliance deadline
        - draft SOP revision for review
Human step required: HSE Manager reviews and approves SOP revision
```

### 2.5.3 AI Provider

```
Document drafting:     Claude (primary — long-form generation)
Report generation:     Claude + internal template engine
Knowledge learning:    OpenAI embeddings + pgvector
Regulatory watch:      Web monitoring + Claude analysis
```

---

# 3. AI Data Access Doctrine

## 3.1 Data Scope Rules

```
Rule 1: AI may only access data within the active tenant.
        Cross-tenant data access is prohibited.

Rule 2: AI may only access data the requesting user is
        authorized to see under their Base Role.
        AI must not bypass RBAC.

Rule 3: AI training or fine-tuning using tenant data
        is prohibited without explicit tenant consent.

Rule 4: AI responses must not include personal employee
        health data (MCU results) unless user has
        explicit authorization.

Rule 5: External AI API calls must not include:
        - tenant identifiers
        - personal employee names
        - exact GPS coordinates
        - commercially sensitive data
        Anonymization required before external API call.
```

## 3.2 Anonymization Before External API Call

Data sent to OpenAI or Claude API must be anonymized:

```
Worker names    → Worker ID only
GPS coordinates → Site area name only
Tenant name     → removed
Company data    → generalized descriptions
```

Authority for data classification:

```
privacy-classification.md
```

---

# 4. AI Audit Trail Requirements

## 4.1 Every AI Action Must Log

```
ai_runs table must capture:
- ai_run_id (UUID)
- tenant_id
- user_id (who triggered)
- capability_used (ENUM: voice_report, copilot, capa_suggest, etc.)
- input_summary (anonymized, max 500 chars)
- output_summary (anonymized, max 500 chars)
- model_used
- tokens_consumed
- latency_ms
- human_action_taken (accepted|modified|rejected|pending)
- created_at
```

## 4.2 Human Action Tracking

Every AI output that is reviewed by a human must record:

```
human_action: accepted | modified | rejected
modification_summary: (if modified, brief description)
reviewed_by: user_id
reviewed_at: timestamp
```

This enables AI accuracy measurement over time.

---

# 5. AI Failure Doctrine

## 5.1 Graceful Degradation Rules

```
If AI service unavailable:
  - Forms remain fully functional (manual input)
  - AI-assisted fields show empty (not blocked)
  - User notified: "AI tidak tersedia saat ini.
    Silakan isi manual."
  - Offline queue stores audio for later processing

If AI returns low confidence output (< 0.6):
  - Output still presented as draft
  - Low confidence warning shown prominently
  - User encouraged to review carefully

If AI returns empty or error:
  - Do not show error to field worker
  - Fall back to blank form
  - Log error to observability system
  - Alert on-call engineer if frequency > 5/hour
```

## 5.2 AI Must Never Block

No operational workflow may be blocked by AI unavailability.

AI is an accelerator. It is not a gatekeeper.

Every AI-assisted step must have a manual fallback path.

---

# 6. Implementation Constraints for Jules

## 6.1 Level Implementation Order

```
Level 1 must be stable before Level 2 begins.
Level 2 must be stable before Level 3 begins.
Level 3 must be stable before Level 4 begins.
```

## 6.2 AI Job Architecture

All AI processing must be asynchronous:

```
User action → queue job → AI processing → result stored →
notification to user → user reviews result
```

No AI call may block HTTP request.

Maximum acceptable latency for AI response notification: 30 seconds.

## 6.3 Provider Configuration

AI provider credentials must come from environment variables.

Fallback provider must be configured for all capabilities.

```
Primary transcription:   OPENAI_API_KEY (Whisper)
Primary generation:      ANTHROPIC_API_KEY (Claude Haiku)
Fallback generation:     OPENAI_API_KEY (GPT-4o-mini)
Embedding:               OPENAI_API_KEY (text-embedding-3-small)
Vision:                  OPENAI_API_KEY (GPT-4o)
```

## 6.4 Prohibited AI Patterns

The following are prohibited:

- Synchronous AI calls in HTTP request lifecycle
- AI output committed to database without human action record
- RAG retrieval without tenant_id scope filter
- External API calls with identifiable personal data
- AI responses without source citation in Copilot mode
- Auto-submission of any AI-generated document

---

# 7. Document Ownership

This document owns:

- AI capability level definitions
- AI provider assignments
- AI input/output contracts
- AI data access rules
- AI audit trail requirements
- AI failure doctrine
- AI implementation constraints

This document does not own:

- RBAC and authorization rules (owned by `rbac-matrix.md`)
- Data retention for AI logs (owned by `data-retention-policy.md`)
- Privacy classification of data (owned by `privacy-classification.md`)
- Offline sync for AI queue (owned by `offline-sync-architecture.md`)
- Observability and alerting (owned by `observability-runbook.md`)
