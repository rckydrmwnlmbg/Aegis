# Aegis Schema Architecture
## Canonical Transactional Data Architecture Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Engineering / Data Architecture / Implementation Governance  
**Authority Level:** Canonical Schema Reference

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical transactional data architecture for Aegis.

It governs:

- transactional truth ownership
- domain persistence boundaries
- shared infrastructure data
- sensitive segregation
- audit persistence
- attachment governance
- AI metadata persistence

---

## 1.2 Scope

This document governs transactional operational persistence.

It does NOT define analytical storage architecture.

Analytics is separately governed.

---

# 2. Schema Doctrine

# 2.1 Core Doctrine

Schema architecture shall reflect domain ownership.

Persistence convenience shall not override architecture correctness.

---

# 2.2 Ownership Rule

Each authoritative business entity shall have exactly one owning domain.

Cross-domain ownership prohibited.

---

# 2.3 Mutation Rule

Only owning domains may mutate authoritative truth.

Direct cross-domain mutation prohibited.

---

# 2.4 Shared Infrastructure Rule

Shared infrastructure may exist only for platform concerns.

Examples:

Allowed:
- tenant metadata
- audit infrastructure
- attachment infrastructure
- notification infrastructure
- integration infrastructure

Not business truth ownership.

---

# 2.5 Analytics Separation Rule

Transactional schema shall not become analytics storage.

Prohibited:

- dashboard aggregates
- executive KPI truth
- cross-domain reporting caches

inside OLTP truth schema.

---

# 2.6 Sensitive Segregation Rule

Sensitive domains require explicit isolation.

Applies especially to:

- occupational health
- medical restrictions
- restricted investigations
- sensitive compliance evidence

---

# 3. Canonical Schema Architecture Model

# 3.1 Architecture Philosophy

Canonical persistence model:

logical modular schema segmentation.

Not giant flat schema sprawl.

---

# 3.2 Canonical Logical Domains

Core platform:

- core_tenant
- core_identity
- core_org
- core_audit
- core_attachment

Operational:

- incident
- near_miss
- hazard
- inspection
- permit
- jsa
- capa

Governance:

- audit
- compliance
- contractor
- document

Workforce:

- training
- toolbox
- ppe
- occupational_health

Operational support:

- emergency
- environmental

Platform:

- notification
- integration
- ai
- administration

---

# 3.3 Namespace Doctrine

Physical implementation may use:

- logical namespaces
- schema prefixes
- bounded modules

But ownership boundaries must remain explicit.

---

# 4. Shared Core Platform Schemas

# 4.1 core_tenant

Purpose:

Tenant governance truth.

Canonical entities:

tenant
tenant_policy
tenant_feature_flag
tenant_deployment_profile
tenant_integration_policy
tenant_security_policy

---

## tenant

Fields:

- id
- tenant_code
- name
- status
- deployment_mode
- created_at
- updated_at

Status examples:

- active
- restricted
- suspended
- archived

---

# 4.2 core_identity

Purpose:

Application identity governance metadata.

Authentication truth remains external.

---

Canonical entities:

app_user
role
permission_policy
user_role_assignment
access_scope_assignment
session_metadata (optional)
privileged_access_event

---

## app_user

Purpose:

application identity mapping

Fields:

- id
- tenant_id
- external_identity_id
- employee_code
- contractor_reference_id (nullable)
- status
- locale
- timezone
- created_at
- updated_at

---

Important:

app_user is NOT authentication authority.

---

## role

Fields:

- id
- tenant_id
- role_code
- role_name
- role_type

Examples:

- worker
- hse_officer
- permit_approver
- auditor
- contractor_supervisor
- tenant_admin

---

## access_scope_assignment

Purpose:

organizational scoping

Fields:

- id
- user_id
- scope_type
- scope_reference_id
- constraints_json

---

# 4.3 core_org

Purpose:

organizational hierarchy

Canonical entities:

organization_unit
site
project
department
operational_area
contractor_partition

---

# 4.4 core_audit

Purpose:

platform-wide immutable audit infrastructure

Canonical entities:

audit_event
access_audit_event
admin_audit_event
security_audit_event
ai_audit_event
export_audit_event

---

## audit_event

Fields:

- id
- tenant_id
- domain
- entity_type
- entity_id
- action_type
- actor_id
- actor_type
- occurred_at
- correlation_id
- metadata_json

---

Doctrine:

append-oriented persistence.

---

# 4.5 core_attachment

Purpose:

central governed evidence infrastructure

Canonical entities:

attachment
attachment_link
attachment_access_audit
attachment_integrity_metadata

---

## attachment

Fields:

- id
- tenant_id
- storage_provider
- storage_key
- media_type
- size_bytes
- checksum
- created_by
- created_at
- status

---

## attachment_link

Purpose:

link attachments to domain entities

Fields:

- id
- tenant_id
- attachment_id
- domain
- entity_type
- entity_id
- linkage_type
- linked_at
- linked_by

---

This avoids domain-specific attachment chaos.

---

# 5. Cross-Cutting Reference Doctrine

Reference patterns allowed:

foreign reference by identifier

Not ownership transfer.

Example:

incident may reference:
contractor_id

but contractor domain owns contractor truth.

---

# 6. Core Integrity Conventions

Mandatory conventions:

all governed entities should generally include:

- id
- tenant_id
- status
- created_at
- created_by
- updated_at
- updated_by
- version

Where appropriate.

---

# 7. Identifier Doctrine

Preferred identifiers:

internal UUID / ULID style immutable IDs.

Human-readable business numbers separate.

Examples:

permit_number
incident_number
audit_reference

---

# 8. Versioning Doctrine

Governed mutable records should support optimistic versioning where appropriate.

Especially:

- permits
- JSA
- incident investigations
- documents

---

# 9. Operational Domain Schemas

# 9.1 Incident Domain Schema

## Purpose

Authoritative transactional truth for incident governance.

---

## Canonical Entities

incident
incident_classification
incident_person
incident_witness
incident_timeline_event
incident_investigation
incident_root_cause
incident_severity_assessment
incident_closure
incident_reopen_event

---

## incident

Purpose:

primary incident truth

Fields:

- id
- tenant_id
- incident_number
- incident_type
- classification_id
- status
- title
- summary
- occurred_at
- reported_at
- reported_by
- location_reference
- project_reference
- contractor_reference_id (nullable)
- severity_status
- current_owner_id
- created_at
- created_by
- updated_at
- updated_by
- version

---

## incident_classification

Purpose:

normalized enterprise classification metadata

Fields:

- id
- tenant_id
- code
- name
- classification_group
- severity_policy_reference

---

## incident_person

Purpose:

affected / involved personnel references

Fields:

- id
- tenant_id
- incident_id
- person_type
- app_user_reference_id (nullable)
- contractor_person_reference_id (nullable)
- injury_role
- metadata_json

---

## incident_witness

Fields:

- id
- tenant_id
- incident_id
- witness_name
- witness_contact
- statement_reference

---

## incident_timeline_event

Purpose:

structured chronology

Fields:

- id
- tenant_id
- incident_id
- event_type
- event_timestamp
- description
- actor_reference

---

## incident_investigation

Purpose:

investigation truth

Fields:

- id
- tenant_id
- incident_id
- investigator_id
- status
- findings_summary
- started_at
- completed_at
- version

---

## incident_root_cause

Fields:

- id
- tenant_id
- investigation_id
- methodology
- root_cause_type
- description

---

## incident_severity_assessment

Fields:

- id
- tenant_id
- incident_id
- assessed_by
- severity_level
- rationale
- assessed_at

---

## incident_closure

Fields:

- id
- tenant_id
- incident_id
- closure_summary
- closed_by
- closed_at

---

## incident_reopen_event

Fields:

- id
- tenant_id
- incident_id
- reason
- reopened_by
- reopened_at

---

Doctrine:

Incident owns incident truth.

CAPA references only.

---

# 9.2 Near Miss Domain Schema

## Canonical Entities

near_miss
near_miss_escalation
near_miss_closure

---

## near_miss

Fields:

- id
- tenant_id
- near_miss_number
- status
- title
- summary
- occurred_at
- reported_by
- anonymous_flag
- contractor_reference_id
- current_owner_id
- created_at
- updated_at
- version

---

## near_miss_escalation

Fields:

- id
- tenant_id
- near_miss_id
- escalation_type
- target_reference_id
- escalated_at
- escalated_by

---

Doctrine:

May reference incident conversion.

Does not own incident truth.

---

# 9.3 Hazard Domain Schema

## Canonical Entities

hazard_observation
hazard_category
hazard_remediation
hazard_verification
hazard_closure

---

## hazard_observation

Fields:

- id
- tenant_id
- hazard_number
- category_id
- severity_level
- title
- description
- observed_at
- observed_by
- location_reference
- contractor_reference_id
- status
- owner_id
- created_at
- updated_at
- version

---

## hazard_remediation

Fields:

- id
- tenant_id
- hazard_id
- remediation_summary
- assigned_to
- due_date
- status

---

## hazard_verification

Fields:

- id
- tenant_id
- hazard_id
- verified_by
- verification_notes
- verified_at

---

Doctrine:

Hazard closure requires verification semantics.

---

# 9.4 Inspection Domain Schema

## Canonical Entities

inspection_template
inspection_template_item
inspection_execution
inspection_response
inspection_finding

---

## inspection_template

Fields:

- id
- tenant_id
- template_code
- name
- inspection_type
- version
- status
- created_at

---

## inspection_template_item

Fields:

- id
- tenant_id
- template_id
- item_order
- item_type
- prompt
- requirement_metadata

---

## inspection_execution

Fields:

- id
- tenant_id
- inspection_number
- template_id
- inspector_id
- site_reference
- status
- started_at
- completed_at
- version

---

## inspection_response

Fields:

- id
- tenant_id
- execution_id
- template_item_id
- response_value
- response_metadata

---

## inspection_finding

Fields:

- id
- tenant_id
- execution_id
- severity
- title
- description
- owner_id
- status

---

Doctrine:

Inspection findings may trigger CAPA.

Inspection does not own CAPA truth.

---

# 9.5 Permit Domain Schema

## Canonical Entities

permit
permit_type
permit_approval
permit_suspension
permit_extension
permit_revalidation
permit_closure
permit_participant_ack

---

## permit

Purpose:

primary permit truth

Fields:

- id
- tenant_id
- permit_number
- permit_type_id
- status
- title
- work_scope
- location_reference
- contractor_reference_id
- valid_from
- valid_until
- requested_by
- current_owner_id
- created_at
- updated_at
- version

---

## permit_type

Fields:

- id
- tenant_id
- code
- name
- risk_class
- configuration_json

---

## permit_approval

Fields:

- id
- tenant_id
- permit_id
- approval_stage
- approver_id
- decision
- decision_notes
- decided_at

---

## permit_suspension

Fields:

- id
- tenant_id
- permit_id
- reason
- suspended_by
- suspended_at

---

## permit_extension

Fields:

- id
- tenant_id
- permit_id
- requested_until
- approved_until
- approved_by
- approved_at

---

## permit_revalidation

Fields:

- id
- tenant_id
- permit_id
- revalidated_by
- rationale
- revalidated_at

---

## permit_closure

Fields:

- id
- tenant_id
- permit_id
- closure_summary
- closed_by
- closed_at

---

## permit_participant_ack

Fields:

- id
- tenant_id
- permit_id
- participant_id
- acknowledgment_type
- acknowledged_at

---

Doctrine:

Permit lifecycle must be explicitly representable.

No hidden status magic.

---

# 9.6 JSA Domain Schema

## Canonical Entities

jsa
jsa_task
jsa_hazard
jsa_control
jsa_approval
jsa_revision

---

## jsa

Fields:

- id
- tenant_id
- jsa_number
- title
- status
- prepared_by
- project_reference
- linked_permit_id (nullable)
- created_at
- updated_at
- version

---

## jsa_task

Fields:

- id
- tenant_id
- jsa_id
- task_order
- description

---

## jsa_hazard

Fields:

- id
- tenant_id
- jsa_task_id
- description
- likelihood_score
- severity_score
- residual_score

---

## jsa_control

Fields:

- id
- tenant_id
- hazard_id
- control_type
- description

---

## jsa_approval

Fields:

- id
- tenant_id
- jsa_id
- approver_id
- decision
- decided_at

---

## jsa_revision

Fields:

- id
- tenant_id
- jsa_id
- revision_number
- revised_by
- rationale
- revised_at

---

# 9.7 CAPA Domain Schema

## Canonical Entities

capa_action
capa_source_reference
capa_escalation
capa_verification
capa_closure
capa_reopen_event

---

## capa_action

Fields:

- id
- tenant_id
- capa_number
- action_type
- title
- description
- owner_id
- due_date
- status
- created_at
- updated_at
- version

---

## capa_source_reference

Purpose:

link originating domain facts

Fields:

- id
- tenant_id
- capa_id
- source_domain
- source_entity_type
- source_entity_id

---

## capa_escalation

Fields:

- id
- tenant_id
- capa_id
- escalated_to
- escalation_reason
- escalated_at

---

## capa_verification

Fields:

- id
- tenant_id
- capa_id
- verified_by
- verification_notes
- verified_at

---

## capa_closure

Fields:

- id
- tenant_id
- capa_id
- closure_summary
- closed_by
- closed_at

---

Doctrine:

CAPA owns CAPA lifecycle only.

Originating domain remains authoritative for source truth.

---

# 10. Cross-Domain Operational Reference Rules

Allowed references:

Incident → contractor_reference_id

Permit → contractor_reference_id

JSA → linked_permit_id

CAPA → source references

Inspection → CAPA trigger references

---

Forbidden:

direct ownership transfer

cross-domain lifecycle mutation

shared ambiguous status ownership

---
# 11. Governance Domain Schemas

# 11.1 Audit Domain Schema

## Canonical Entities

audit_plan
audit_execution
audit_scope
audit_finding
audit_closure

---

## audit_plan

Fields:

- id
- tenant_id
- audit_reference
- audit_type
- title
- scope_summary
- scheduled_start
- scheduled_end
- owner_id
- status
- created_at
- updated_at
- version

---

## audit_execution

Fields:

- id
- tenant_id
- audit_plan_id
- lead_auditor_id
- started_at
- completed_at
- execution_status
- version

---

## audit_scope

Fields:

- id
- tenant_id
- audit_plan_id
- scope_type
- scope_reference_id

---

## audit_finding

Fields:

- id
- tenant_id
- audit_execution_id
- severity
- finding_type
- title
- description
- owner_id
- status
- created_at

---

## audit_closure

Fields:

- id
- tenant_id
- audit_execution_id
- closure_summary
- closed_by
- closed_at

---

Doctrine:

Audit owns audit truth only.

CAPA references audit findings.

---

# 11.2 Compliance Domain Schema

## Canonical Entities

compliance_framework
compliance_obligation
compliance_control
compliance_evidence_reference
compliance_assessment

---

## compliance_framework

Fields:

- id
- tenant_id
- framework_code
- framework_name
- version
- status

---

## compliance_obligation

Fields:

- id
- tenant_id
- framework_id
- obligation_code
- title
- description
- owner_id
- due_date
- status
- sensitivity_class

---

## compliance_control

Fields:

- id
- tenant_id
- obligation_id
- control_name
- control_type
- owner_id
- status

---

## compliance_evidence_reference

Fields:

- id
- tenant_id
- obligation_id
- attachment_reference_id
- evidence_type
- linked_at

---

Doctrine:

Evidence payload remains in core_attachment.

---

# 11.3 Contractor Domain Schema

## Canonical Entities

contractor
contractor_contact
contractor_compliance_record
contractor_restriction
contractor_benchmark_snapshot

---

## contractor

Fields:

- id
- tenant_id
- contractor_code
- legal_name
- display_name
- status
- risk_class
- approved_at
- created_at
- updated_at
- version

---

## contractor_contact

Fields:

- id
- tenant_id
- contractor_id
- name
- contact_role
- email
- phone

---

## contractor_compliance_record

Fields:

- id
- tenant_id
- contractor_id
- compliance_type
- expiry_date
- status
- attachment_reference_id

---

## contractor_restriction

Fields:

- id
- tenant_id
- contractor_id
- restriction_type
- rationale
- active_from
- active_until
- imposed_by

---

Doctrine:

Benchmark snapshots are derived operational metadata, not analytics truth.

---

# 11.4 Document Governance Schema

## Canonical Entities

controlled_document
document_version
document_publication
document_acknowledgment

---

## controlled_document

Fields:

- id
- tenant_id
- document_code
- title
- document_type
- owner_id
- status
- current_version_reference

---

## document_version

Fields:

- id
- tenant_id
- document_id
- version_number
- attachment_reference_id
- authored_by
- approved_by
- approved_at
- status

---

## document_acknowledgment

Fields:

- id
- tenant_id
- document_version_id
- user_id
- acknowledged_at

---

# 12. Workforce Domain Schemas

# 12.1 Training Domain Schema

## Canonical Entities

training_program
training_session
training_attendance
competency_record
certification_record

---

## training_program

Fields:

- id
- tenant_id
- program_code
- title
- training_type
- status

---

## training_session

Fields:

- id
- tenant_id
- program_id
- scheduled_at
- instructor_id
- location_reference
- status

---

## training_attendance

Fields:

- id
- tenant_id
- session_id
- user_id
- attendance_status
- acknowledged_at

---

## competency_record

Fields:

- id
- tenant_id
- user_id
- competency_type
- achieved_at
- expiry_date
- status

---

# 12.2 Toolbox Domain Schema

## Canonical Entities

toolbox_session
toolbox_attendance
toolbox_topic_reference

---

## toolbox_session

Fields:

- id
- tenant_id
- session_reference
- title
- facilitator_id
- scheduled_at
- status

---

## toolbox_attendance

Fields:

- id
- tenant_id
- toolbox_session_id
- user_id
- attendance_status
- acknowledged_at

---

# 12.3 PPE Domain Schema

## Canonical Entities

ppe_catalog
ppe_assignment
ppe_inventory_event

---

## ppe_catalog

Fields:

- id
- tenant_id
- item_code
- item_name
- item_type
- status

---

## ppe_assignment

Fields:

- id
- tenant_id
- user_id
- ppe_item_id
- issued_at
- replacement_due
- status

---

# 13. Sensitive Domain Schema

# 13.1 Occupational Health Schema

## Sensitivity Classification

HIGH-SENSITIVITY DOMAIN

Special governance mandatory.

---

## Canonical Entities

health_profile
medical_restriction
surveillance_record
fitness_assessment
exposure_monitoring

---

## health_profile

Fields:

- id
- tenant_id
- user_id
- profile_status
- sensitivity_class
- created_at
- updated_at
- version

---

## medical_restriction

Fields:

- id
- tenant_id
- health_profile_id
- restriction_type
- restriction_summary
- effective_from
- effective_until
- imposed_by

---

## surveillance_record

Fields:

- id
- tenant_id
- user_id
- surveillance_type
- scheduled_at
- completed_at
- status

---

## fitness_assessment

Fields:

- id
- tenant_id
- user_id
- assessment_type
- assessed_at
- outcome_class

---

## Security Doctrine

Enhanced access governance mandatory.

Broad unrestricted joins prohibited.

---

# 14. Operational Support Domain Schemas

# 14.1 Emergency Domain

Entities:

- emergency_contact
- emergency_escalation_rule
- drill_event
- emergency_notification_reference

---

# 14.2 Environmental Domain

Entities:

- environmental_incident
- spill_record
- emissions_record
- waste_tracking_record

---

# 15. Platform Domain Schemas

# 15.1 Notification Schema

Entities:

notification_message
notification_delivery
notification_template

---

## notification_message

Fields:

- id
- tenant_id
- notification_type
- recipient_reference
- payload_metadata
- status
- created_at

---

## notification_delivery

Fields:

- id
- tenant_id
- message_id
- channel
- delivery_status
- attempted_at
- provider_reference

---

# 15.2 Integration Schema

Entities:

integration_connector
integration_credential_reference
integration_execution
webhook_subscription
webhook_delivery

---

## integration_connector

Fields:

- id
- tenant_id
- connector_type
- configuration_reference
- status

---

## integration_execution

Fields:

- id
- tenant_id
- connector_id
- execution_type
- execution_status
- started_at
- completed_at
- correlation_id

---

# 15.3 AI Schema

Entities:

ai_interaction
ai_provider_reference
ai_prompt_metadata
ai_response_metadata

---

## ai_interaction

Fields:

- id
- tenant_id
- actor_id
- use_case
- provider_reference
- workflow_domain
- workflow_entity_id
- occurred_at
- correlation_id

---

Doctrine:

No unsafe raw business truth ownership by AI domain.

---

# 15.4 Administration Schema

Entities:

admin_configuration
feature_toggle
policy_override
break_glass_access_event

---

# 16. Integrity & Modeling Rules

# 16.1 Status Doctrine

Statuses are explicit governed states.

No hidden magic enum semantics.

---

# 16.2 JSON Usage Doctrine

JSON allowed only for bounded flexible metadata.

Not for core authoritative business structure.

Bad example:

entire incident record as JSON

Allowed example:

provider metadata payload

---

# 16.3 Soft Delete Doctrine

Soft delete only where explicitly governed.

Audit-sensitive truth should prefer status transitions.

Silent destructive deletion discouraged.

---

# 16.4 Referential Integrity Doctrine

Cross-domain references allowed.

Cross-domain ownership prohibited.

---

# 17. Forbidden Schema Anti-Patterns

Prohibited:

giant mega users table owning everything

domain-crossing mutable ownership

attachment URLs scattered everywhere

analytics truth inside transactional schema

tenantless governed records

hidden status semantics

cross-domain duplicated business truth

AI owning operational workflow state

unsafe unrestricted joins into sensitive domains

unbounded JSON dumping as pseudo-schema

---

# 18. Canonical Authority Rule

This document governs:

- database modeling
- ORM modeling
- migration strategy
- persistence ownership
- integrity rules
- implementation boundaries

Conflicts resolved in favor of schema doctrine.

---


