# 1. Executive Summary

## System Overview

KairoFlow is a monolithic PHP 8.4+ application built on the Nette Framework, designed to combat financial chaos and work disorganization caused by ADHD through intelligent automation and friction-free interactions. The system operates as a Kubernetes-deployed service with persistent data storage in PostgreSQL, caching via Redis, and multi-channel API integrations.

## Architecture Philosophy

**Simplicity Over Complexity**: Monolithic architecture with modular organization to reduce operational overhead while maintaining clear boundaries. Event-driven communication between modules enables future scaling without immediate microservice complexity.

**ADHD-Driven Design**: Every architectural decision prioritizes reducing cognitive load, automating repetitive tasks, and creating unavoidable system interactions that bypass executive dysfunction.

**Resilient Processing**: Fault-tolerant email parsing pipeline with automatic retries, manual fallback mechanisms, and comprehensive audit logging ensures no financial document is lost.

## Key Technical Decisions

- **Monolithic Application**: Single deployable unit reduces deployment complexity and inter-service communication overhead
- **Email as Universal Hub**: IMAP/POP3 integration provides lowest-friction data ingestion without complex API integrations
- **Kubernetes Native**: Leverages existing Rancher 2 infrastructure with Helm charts for declarative deployment
- **Server-Side Rendering**: Nette/Latte templates with Alpine.js progressive enhancement for sub-2-second page loads
- **Event Sourcing Light**: Module communication via domain events without full CQRS complexity
