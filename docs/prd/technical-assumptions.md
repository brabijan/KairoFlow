# Technical Assumptions

## Repository Structure: Monorepo

Single repository containing all code, configurations, and Kubernetes manifests for simplified deployment and version management.

## Service Architecture

**Monolithic application with modular organization** - Single deployable unit with clear module boundaries (Finance, Work, Notifications). Event-driven communication between modules. No microservices in MVP to reduce complexity.

## Testing Requirements

**Unit + Integration testing focus** - Unit tests for business logic, integration tests for email parsing and API interactions. Manual testing for UI/UX flows. No E2E automation in MVP.

## Additional Technical Assumptions and Requests

• **Language/Framework:** PHP 8.4+ with Nette Framework (latest version)
• **ORM:** Doctrine via Contributte/Doctrine packages
• **Database:** PostgreSQL 14+ for main storage, Redis for caching/queues
• **Frontend:** Server-side rendering with Nette/Latte templates, Alpine.js for interactivity, Tailwind CSS
• **Deployment:** Kubernetes (existing Rancher 2 cluster) with Helm charts
• **Container:** Docker for all services with health checks for K8s probes
• **Email Processing:** IMAP/POP3 via Contributte packages, cron job every minute
• **Integrations:** GitLab REST API, Slack Web API, Clockify REST API
• **QR Standards:** SPAYD/EPC format for Czech banking compatibility
• **Security:** HTTPS only (Ingress termination), K8s secrets for sensitive config
• **No SPA in MVP:** Traditional server-rendered pages for simplicity
• **No LLM in MVP:** Manual categorization with simple pattern matching
