# Aegis Repository Structure
## Canonical Repository Topology & Code Organization Doctrine
**Project:** Aegis AI EHS Platform  
**Version:** 2.0 Canonical Reconstruction  
**Status:** Source of Truth  
**Classification:** Engineering Governance / Repository Architecture  
**Authority Level:** Repository Topology Authority

---

# 1. Executive Purpose

## 1.1 Purpose

This document defines canonical repository structure for Aegis.

It governs:

- repository topology
- code placement
- module ownership
- package boundaries
- test organization
- infrastructure organization
- documentation organization
- AI-assisted implementation placement discipline

---

## 1.2 Scope

Applies to:

- backend code
- frontend code
- mobile code
- infrastructure code
- tests
- documentation
- automation scripts
- generated code placement

---

# 2. Repository Doctrine

# 2.1 Core Rule

Repository structure must reflect architecture.

Repository layout must not contradict architectural boundaries.

---

# 2.2 Anti-Chaos Rule

Repository sprawl prohibited.

Unbounded folder dumping prohibited.

---

# 2.3 Ownership Rule

Every code artifact must have clear architectural ownership.

---

# 3. Canonical Top-Level Structure

Canonical structure:

```text
/docs
/apps
/packages
/infrastructure
/scripts
/tests
/tools
```

---

# 4. Top-Level Directory Ownership

# 4.1 /docs

Purpose:

canonical source-of-truth documentation.

Contains:

architecture

requirements

governance

decision records

implementation doctrine

---

Forbidden:

runtime application code

---

# 4.2 /apps

Purpose:

deployable application runtimes.

Examples:

```text
/apps/api
/apps/web
/apps/mobile
/apps/worker
```

---

Contains:

runtime entrypoints

application bootstrapping

delivery-specific adapters

---

Forbidden:

shared domain dumping

---

# 4.3 /packages

Purpose:

shared architectural packages.

Strictly governed.

---

Examples:

```text
/packages/domains
/packages/platform
/packages/shared
/packages/contracts
```

---

# 4.4 /infrastructure

Purpose:

deployment / infra artifacts.

Examples:

```text
/infrastructure/terraform
/infrastructure/k8s
/infrastructure/docker
```

---

# 4.5 /scripts

Purpose:

governed operational scripts.

Examples:

migration helpers

seed scripts

maintenance automation

---

# 4.6 /tests

Purpose:

cross-system test suites.

Examples:

integration

contract

E2E

performance

security

---

# 4.7 /tools

Purpose:

internal engineering tooling.

Examples:

generators

schema validators

developer automation

---

# 5. Canonical Application Layout

# 5.1 /apps/api

Purpose:

API runtime boundary.

Canonical example:

```text
/apps/api
  /src
    /http
    /config
    /bootstrap
```

---

Contains:

HTTP entrypoints

middleware wiring

runtime bootstrap

dependency composition

---

Forbidden:

domain ownership implementation duplication

---

# 5.2 /apps/worker

Purpose:

background async runtime.

Canonical example:

```text
/apps/worker
  /src
    /jobs
    /consumers
    /bootstrap
```

---

# 5.3 /apps/web

Purpose:

web client application.

---

Contains:

UI runtime

presentation logic

client integration code

---

Forbidden:

authoritative business ownership logic

---

# 5.4 /apps/mobile

Purpose:

mobile application runtime.

---

Contains:

offline client behavior

presentation

sync client orchestration

device integration

---

Forbidden:

authoritative workflow governance

---

# 6. Canonical Package Layout

# 6.1 Package Doctrine

Packages exist for governed architectural ownership.

Not convenience dumping.

---

# 6.2 Canonical Package Structure

Recommended:

```text
/packages
  /domains
  /platform
  /contracts
  /shared
```

---

# 7. Domain Package Structure

# 7.1 /packages/domains

Purpose:

bounded business domain ownership.

Canonical example:

```text
/packages/domains
  /incident
  /hazard
  /capa
  /permit
  /inspection
  /audit
  /compliance
  /training
  /contractor
  /document-control
  /occupational-health
```

---

# 7.2 Domain Internal Structure

Canonical domain layout:

```text
/domain-name
  /application
  /domain
  /infrastructure
  /contracts
```

---

# 7.3 Layer Ownership

application:

use-case orchestration

---

domain:

business rules
entities
policies
validators

---

infrastructure:

domain-specific persistence adapters

---

contracts:

internal domain DTOs/events

---

# 8. Platform Package Structure

# 8.1 /packages/platform

Purpose:

cross-domain platform capabilities.

Examples:

```text
/packages/platform
  /auth
  /audit
  /attachments
  /events
  /notifications
  /observability
  /config
  /tenancy
```

---

Rule:

platform capabilities support domains.

They do not own domain truth.

---

# 9. Contracts Package Structure

# 9.1 /packages/contracts

Purpose:

shared governed contracts.

Examples:

API DTOs

event schemas

integration contracts

shared protocol definitions

---

Forbidden:

business logic ownership

---

# 10. Shared Package Discipline

# 10.1 /packages/shared

Purpose:

minimal shared utilities.

---

Allowed:

small deterministic primitives

safe abstractions

shared helpers with clear ownership

---

Forbidden:

business dumping ground

domain leakage

---

# 10.2 Shared Abuse Prohibition

Forbidden:

```text
/shared/utils
/shared/helpers
/common
```

as architectural dumping grounds.

---

# 11. Test Organization

# 11.1 Cross-System Tests

Canonical structure:

```text
/tests
  /integration
  /contracts
  /e2e
  /security
  /performance
  /tenant-isolation
```

---

# 11.2 Domain Tests

Domain-local tests may live with owning domain package.

---

# 12. Infrastructure Organization

# 12.1 Infra Layout

Recommended:

```text
/infrastructure
  /terraform
  /docker
  /k8s
  /monitoring
  /deployment
```

---

# 13. Documentation Organization

# 13.1 Docs Structure

Recommended:

```text
/docs
  README.md
  project_context.md
  architecture/
  implementation/
  governance/
  operations/
```

---

May evolve physically while preserving authority references.

---

# 14. Generated Code Rules

# 14.1 Generated Artifact Governance

Generated code must live in explicit governed locations.

---

Forbidden:

random generated file scattering

---

# 14.2 Ownership Rule

Generated code must not become undocumented architecture authority.

---

# 15. Naming Rules

# 15.1 Explicit Naming

Directory names should be explicit.

---

Good:

```text
attachments
notifications
tenant-isolation
document-control
```

---

Bad:

```text
misc
helpers
commonstuff
manager
system
```

---

# 16. Jules Repository Constraints

# 16.1 Explicit Rules

Jules must not:

invent alternate topology

create dumping-ground folders

flatten domain boundaries

mix runtime ownership with domain ownership

duplicate domain implementations

create random shared abstractions

---

# 16.2 Missing Placement Rule

If placement ambiguity exists:

choose governed ownership

not convenience dumping

---

# 17. Canonical Authority Rule

Repository structure must remain architecture-compliant.

---

# 18. Canonical Authority Statement

This document defines authoritative repository topology.

---

