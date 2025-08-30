# Epic 1: Project Foundation & Authentication

*Establish the core application infrastructure with deployment pipeline and secure user authentication*

## Story 1.1: Project Setup & Repository Initialization

As a developer,
I want to initialize the Nette project structure with all necessary configurations,
so that I have a working foundation for the application.

### Acceptance Criteria

1: Nette project created with latest stable version and Contributte packages configured
2: PostgreSQL and Redis connections configured via environment variables
3: Basic folder structure follows Nette conventions (app/, www/, temp/, log/, migrations/)
4: Docker container builds successfully with all PHP extensions
5: Composer dependencies installed and autoloading works
6: Health check endpoint responds at /health returning 200 OK
7: .gitignore properly configured for Nette project

## Story 1.2: Kubernetes Deployment Configuration

As a DevOps engineer,
I want to create Helm charts for the application,
so that I can deploy to the Rancher cluster reliably.

### Acceptance Criteria

1: Helm chart created with configurable values.yaml
2: Deployment includes app container, PostgreSQL, and Redis
3: Ingress configured with HTTPS termination
4: Persistent volumes for database and file storage
5: Liveness and readiness probes configured to /health
6: Secrets management for sensitive configurations
7: Successfully deploys to K8s namespace

## Story 1.3: Database Schema & Migrations

As a developer,
I want to set up the initial database schema with user tables,
so that I can implement authentication.

### Acceptance Criteria

1: Doctrine entities created for User with email, password hash, 2FA fields
2: Database migrations created and versioned
3: Migration runner integrated into deployment process
4: User table includes created_at, updated_at timestamps
5: Indexes created for email (unique) and performance fields

## Story 1.4: Authentication System

As a user,
I want to securely log into the system,
so that I can access my financial data safely.

### Acceptance Criteria

1: Login page with email/password fields
2: Password hashing using bcrypt or better
3: Session management with secure cookies
4: Logout functionality clears session
5: 2FA setup page (TOTP) with QR code generation
6: 2FA verification on login when enabled
7: Remember me option with secure token

## Story 1.5: Basic Layout & Navigation

As a user,
I want a consistent layout with navigation,
so that I can easily move through the application.

### Acceptance Criteria

1: Base Latte template with header, navigation, content area
2: Responsive layout using Tailwind CSS
3: Navigation menu with placeholders for future sections
4: User info display in header when logged in
5: Alpine.js integrated for interactive components
6: Loading states for async operations
