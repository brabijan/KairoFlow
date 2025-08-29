# KairoFlow Product Requirements Document (PRD)

## Goals and Background Context

### Goals

• Eliminate all late payment penalties within 2 months (currently 500-2000+ CZK/month)
• Build a weekly financial buffer starting at 500 CZK with 10% weekly growth
• Capture 95%+ of work tasks in GitLab within 24 hours of receipt
• Automate 100% of follow-up tasks from deployments
• Create an ADHD-friendly system requiring minimal active participation
• Enable systematic debt reduction (minimum 5% of monthly income)
• Provide emotionally neutral financial management without guilt or shame
• Achieve daily interaction streak of 80%+ through gamification

### Background Context

KairoFlow addresses the intersection of ADHD executive dysfunction and financial illiteracy that creates a destructive cycle of late payments, penalties, and mounting stress. Traditional financial and task management tools fail because they require consistent discipline and active maintenance - precisely what ADHD brains struggle with. This system takes a radically different approach: instead of fighting ADHD, it works around it through automation, multi-channel bypass strategies, and progressive habit building. By centralizing all inputs through a dedicated email hub and using AI-powered processing, the system eliminates the need for manual data entry while providing friction-free actions (2-click payments via QR codes) and unavoidable reminders that bypass procrastination.

### Change Log

| Date | Version | Description | Author |
|------|---------|-------------|--------|
| 2025-01-29 | 1.0 | Initial PRD creation based on project brief | PM John |

## Requirements

### Functional Requirements

• **FR1:** The system shall monitor a dedicated email inbox every minute for new financial documents, work tasks, and notifications
• **FR2:** The system shall automatically parse known email formats (bank statements, invoices) and extract payment details
• **FR3:** The system shall generate QR payment codes (SPAYD/EPC format) for all pending payments
• **FR4:** The system shall categorize payments as one-time/recurring and business/personal with automatic detection and manual fallback
• **FR5:** The system shall monitor Slack and email for new work tasks and compare against GitLab issues
• **FR6:** The system shall automatically generate follow-up tasks from commit messages containing fix/feat/BREAKING keywords
• **FR7:** The system shall display a financial dashboard showing all pending payments, deadlines, and account balances
• **FR8:** The system shall track cash flow by comparing expected income (from Clockify) with required expenses
• **FR9:** The system shall detect duplicate payments before QR generation and flag for manual review
• **FR10:** The system shall automatically generate weekly invoices from Clockify time tracking data
• **FR11:** The system shall implement at least 2 ADHD bypass strategies (morning GitLab blocking + visual urgency indicators)
• **FR12:** The system shall maintain an audit trail of important operations for debugging

### Non-Functional Requirements

• **NFR1:** Dashboard must load in under 2 seconds to maintain user engagement
• **NFR2:** Email processing cron job must complete within 30 seconds per run
• **NFR3:** QR code generation must complete within 500ms per code
• **NFR4:** System must maintain 99% uptime to support daily habit formation
• **NFR5:** All sensitive financial data must be encrypted at rest and in transit
• **NFR6:** System must be accessible via web browser on desktop and mobile (responsive design)
• **NFR7:** User interface must be intuitive enough for non-technical users without training
• **NFR8:** System must handle Czech language documents and banking formats
• **NFR9:** All integrations must gracefully handle API rate limits and failures
• **NFR10:** System must support 2FA authentication for security

## User Interface Design Goals

### Overall UX Vision

Create a calm, distraction-free interface that presents critical information without overwhelming ADHD users. Use visual hierarchy, color coding for urgency, and progressive disclosure to manage cognitive load while ensuring nothing important can be missed.

### Key Interaction Paradigms

• **Zero-friction actions:** Maximum 2 clicks from notification to completed action
• **Visual urgency indicators:** Color-coded deadlines (green→yellow→red) impossible to ignore
• **Batch operations:** Group similar tasks (e.g., show all QR codes on one screen)
• **Progressive disclosure:** Show only essential info first, details on demand
• **Gamification elements:** Streaks, progress bars, buffer "health bar"

### Core Screens and Views

• **Daily Dashboard:** Morning check screen with financial status and urgent items
• **Payment Queue:** List of pending payments with QR codes and deadlines  
• **Task Inbox:** Unprocessed work tasks from Slack/email not yet in GitLab
• **Cash Flow Visualizer:** Weekly income vs expenses with buffer tracking
• **Quick Actions:** One-click access to common operations (generate QRs, mark paid)
• **Settings:** Email configuration, category management, ADHD strategy toggles

### Accessibility: WCAG AA

Ensure sufficient color contrast, keyboard navigation, and screen reader compatibility for future expansion.

### Branding

Clean, minimal design with calming colors. No aggressive red alerts. Use subtle animations for positive feedback. Emoji usage minimal and purposeful (✓ for completed, ⚠️ for warnings).

### Target Device and Platforms: Web Responsive

Desktop-first design optimized for morning routine on laptop/desktop. Fully responsive for mobile checking throughout the day.

## Technical Assumptions

### Repository Structure: Monorepo

Single repository containing all code, configurations, and Kubernetes manifests for simplified deployment and version management.

### Service Architecture

**Monolithic application with modular organization** - Single deployable unit with clear module boundaries (Finance, Work, Notifications). Event-driven communication between modules. No microservices in MVP to reduce complexity.

### Testing Requirements

**Unit + Integration testing focus** - Unit tests for business logic, integration tests for email parsing and API interactions. Manual testing for UI/UX flows. No E2E automation in MVP.

### Additional Technical Assumptions and Requests

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

## Epic List

1. **Epic 1: Project Foundation & Authentication** - Základní skeleton aplikace s Nette, databází, K8s deployment a přihlášením

2. **Epic 2: Email Infrastructure & Collection** - IMAP napojení, ukládání emailů, základní rozhraní pro prohlížení

3. **Epic 3: Email Classification & Categorization** - Automatická klasifikace typů emailů, ruční kategorizace, správa pravidel

4. **Epic 4: Banking Email Parser** - Parsování bankovních výpisů a notifikací z českých bank

5. **Epic 5: Invoice & Bill Processing** - Parsování faktur, upomínek a platebních předpisů

6. **Epic 6: QR Payment Generation** - Generování SPAYD/EPC QR kódů, detekce duplicit, batch zobrazení

7. **Epic 7: Financial Dashboard** - Přehled plateb, deadliny, vizuální urgence indikátory

8. **Epic 8: Cash Flow Tracking** - Clockify integrace, týdenní fakturace, buffer kalkulace

9. **Epic 9: GitLab/Slack Integration** - Monitoring úkolů, porovnání s GitLab issues

10. **Epic 10: Follow-up Automation** - Generování follow-up tasků z commitů, reminder engine

11. **Epic 11: ADHD Bypass Strategies** - Morning block, gamifikace, streak tracking

12. **Epic 12: Optimizations & Polish** - Performance tuning, UX vylepšení, mobilní responzivita

## Epic 1: Project Foundation & Authentication

*Establish the core application infrastructure with deployment pipeline and secure user authentication*

### Story 1.1: Project Setup & Repository Initialization

As a developer,
I want to initialize the Nette project structure with all necessary configurations,
so that I have a working foundation for the application.

#### Acceptance Criteria

1: Nette project created with latest stable version and Contributte packages configured
2: PostgreSQL and Redis connections configured via environment variables
3: Basic folder structure follows Nette conventions (app/, www/, temp/, log/, migrations/)
4: Docker container builds successfully with all PHP extensions
5: Composer dependencies installed and autoloading works
6: Health check endpoint responds at /health returning 200 OK
7: .gitignore properly configured for Nette project

### Story 1.2: Kubernetes Deployment Configuration

As a DevOps engineer,
I want to create Helm charts for the application,
so that I can deploy to the Rancher cluster reliably.

#### Acceptance Criteria

1: Helm chart created with configurable values.yaml
2: Deployment includes app container, PostgreSQL, and Redis
3: Ingress configured with HTTPS termination
4: Persistent volumes for database and file storage
5: Liveness and readiness probes configured to /health
6: Secrets management for sensitive configurations
7: Successfully deploys to K8s namespace

### Story 1.3: Database Schema & Migrations

As a developer,
I want to set up the initial database schema with user tables,
so that I can implement authentication.

#### Acceptance Criteria

1: Doctrine entities created for User with email, password hash, 2FA fields
2: Database migrations created and versioned
3: Migration runner integrated into deployment process
4: User table includes created_at, updated_at timestamps
5: Indexes created for email (unique) and performance fields

### Story 1.4: Authentication System

As a user,
I want to securely log into the system,
so that I can access my financial data safely.

#### Acceptance Criteria

1: Login page with email/password fields
2: Password hashing using bcrypt or better
3: Session management with secure cookies
4: Logout functionality clears session
5: 2FA setup page (TOTP) with QR code generation
6: 2FA verification on login when enabled
7: Remember me option with secure token

### Story 1.5: Basic Layout & Navigation

As a user,
I want a consistent layout with navigation,
so that I can easily move through the application.

#### Acceptance Criteria

1: Base Latte template with header, navigation, content area
2: Responsive layout using Tailwind CSS
3: Navigation menu with placeholders for future sections
4: User info display in header when logged in
5: Alpine.js integrated for interactive components
6: Loading states for async operations

## Epic 2: Email Infrastructure & Collection

### Story 2.1: Email Account Configuration

As a user,
I want to configure my dedicated email account for monitoring,
so that the system can collect my financial emails.

#### Acceptance Criteria

1: Settings page for email configuration (IMAP server, port, username, password)
2: Encrypted storage of email credentials in database
3: Test connection button with success/error feedback
4: Support for common providers (Gmail, Seznam, etc.) with presets
5: SSL/TLS options configurable

### Story 2.2: IMAP Email Fetcher

As a system,
I want to connect to IMAP and fetch new emails,
so that I can process incoming financial documents.

#### Acceptance Criteria

1: IMAP connection using configured credentials
2: Fetch only unread emails or emails since last check
3: Mark emails as read after successful processing
4: Handle attachments (PDF, images)
5: Store raw email with headers in database
6: Error handling for connection failures with retry logic

### Story 2.3: Cron Job for Email Monitoring

As a system,
I want to check for new emails every minute,
so that users get near real-time processing.

#### Acceptance Criteria

1: Cron job configured to run every minute
2: Prevents concurrent runs with lock mechanism
3: Logs execution time and email count
4: Graceful handling of IMAP timeouts
5: K8s CronJob manifest included in Helm chart
6: Monitoring of cron job failures

### Story 2.4: Email Viewer Interface

As a user,
I want to see all collected emails in a list,
so that I can review what's been captured.

#### Acceptance Criteria

1: Paginated list of emails with sender, subject, date
2: Click to view full email content
3: Status indicator (processed/pending/error)
4: Search by sender, subject, date range
5: Mark for reprocessing functionality
6: Attachment indicator and download links

## Epic 3: Email Classification & Categorization

### Story 3.1: Email Type Detection

As a system,
I want to automatically classify email types,
so that I can route them for appropriate processing.

#### Acceptance Criteria

1: Pattern matching for known senders (banks, utilities, etc.)
2: Classification into types: bank_statement, invoice, reminder, work_task, other
3: Confidence score for classification
4: Store classification with email record
5: Rules engine for sender-based classification

### Story 3.2: Manual Classification Interface

As a user,
I want to manually classify unrecognized emails,
so that the system learns my patterns.

#### Acceptance Criteria

1: Queue of unclassified emails
2: Dropdown to select email type
3: Additional fields for payment emails (amount, due date, account)
4: Save classification and update email record
5: Bulk classification for multiple similar emails

### Story 3.3: Classification Rules Management

As a user,
I want to create rules for automatic classification,
so that repeated senders are handled automatically.

#### Acceptance Criteria

1: Rules list with sender pattern, email type mapping
2: Add/edit/delete rules interface
3: Test rule against existing emails
4: Priority ordering for rules
5: Import/export rules for backup

## Epic 4: Banking Email Parser

### Story 4.1: Czech Bank Format Parser

As a system,
I want to parse Czech bank email formats,
so that I can extract transaction data automatically.

#### Acceptance Criteria

1: Parser for major Czech banks (ČSOB, KB, ČS, Fio, Moneta)
2: Extract account number, balance, transaction list
3: Parse transaction date, amount, description, counterparty
4: Handle both HTML and plain text formats
5: Store parsed data in structured database tables

### Story 4.2: Bank Statement Storage

As a system,
I want to store parsed bank statements,
so that I can track account balances over time.

#### Acceptance Criteria

1: Database schema for accounts, transactions
2: Deduplication of transactions by bank reference
3: Running balance calculation
4: Link transactions to source email
5: Transaction categorization fields

### Story 4.3: Bank Parser Testing Interface

As a developer,
I want to test bank parsers with sample emails,
so that I can verify parsing accuracy.

#### Acceptance Criteria

1: Upload or paste email content for testing
2: Show parsed output in structured format
3: Highlight parsing errors or missing fields
4: Save test cases for regression testing
5: Parser accuracy metrics dashboard

## Epic 5: Invoice & Bill Processing

### Story 5.1: Invoice Data Extraction

As a system,
I want to extract payment details from invoices,
so that I can create payment records.

#### Acceptance Criteria

1: Extract vendor name, amount, due date, bank account
2: Parse variable symbol, constant symbol, specific symbol
3: Detect currency (CZK, EUR, USD)
4: Handle common invoice formats (text patterns)
5: Create payment record in database

### Story 5.2: Payment Deadline Tracking

As a user,
I want to see all upcoming payment deadlines,
so that I can prioritize payments.

#### Acceptance Criteria

1: List of payments sorted by due date
2: Visual indicators for urgency (days remaining)
3: Overdue payments highlighted
4: Filter by paid/unpaid status
5: Total amount due calculation

### Story 5.3: Duplicate Payment Detection

As a system,
I want to detect potential duplicate payments,
so that users don't pay twice.

#### Acceptance Criteria

1: Check for same amount + variable symbol within 7 days
2: Check for same amount + bank account within 7 days
3: Flag duplicates for manual review
4: Allow user to confirm or dismiss duplicate warning
5: Link related payments in database

## Epic 6: QR Payment Generation

### Story 6.1: SPAYD QR Code Generator

As a user,
I want to generate QR codes for payments,
so that I can pay with two clicks in my banking app.

#### Acceptance Criteria

1: Generate SPAYD format QR codes for CZK payments
2: Include all payment details (account, amount, VS, message)
3: QR code image generation in PNG format
4: Validate bank account format (Czech standards)
5: Error handling for invalid payment data

### Story 6.2: Batch QR Display

As a user,
I want to see all pending payment QRs on one screen,
so that I can quickly process multiple payments.

#### Acceptance Criteria

1: Grid layout showing multiple QR codes
2: Payment details under each QR
3: Mark as paid button for each
4: Print-friendly layout option
5: Mobile responsive design for scanning

### Story 6.3: Payment Status Management

As a user,
I want to mark payments as completed,
so that I can track what's been paid.

#### Acceptance Criteria

1: Mark payment as paid with timestamp
2: Optional note field for payment reference
3: Paid payments move to history
4: Undo payment marking within 24 hours
5: Payment history with search and filters

## Epic 7: Financial Dashboard

### Story 7.1: Dashboard Overview Page

As a user,
I want to see my financial status at a glance,
so that I know my current situation immediately.

#### Acceptance Criteria

1: Current account balances from latest statements
2: Count and total of unpaid bills
3: Next 3 upcoming deadlines
4: Week's required vs available funds
5: Auto-refresh every 5 minutes

### Story 7.2: Visual Urgency Indicators

As a user with ADHD,
I want clear visual cues for urgency,
so that I can't ignore important deadlines.

#### Acceptance Criteria

1: Color coding: green (7+ days), yellow (3-6 days), red (<3 days)
2: Overdue items with pulsing animation
3: Progress bars for time remaining
4: Large, clear deadline text
5: Sort by urgency by default

### Story 7.3: Daily Summary View

As a user,
I want a morning dashboard check,
so that I start each day informed.

#### Acceptance Criteria

1: Today's required actions summary
2: Yesterday's completed payments
3: This week's cash flow status
4: Streak counter for daily checks
5: Motivational message based on status

## Epic 8: Cash Flow Tracking

### Story 8.1: Clockify Integration

As a system,
I want to connect to Clockify API,
so that I can track expected income.

#### Acceptance Criteria

1: Clockify API key configuration
2: Fetch current week's tracked hours
3: Calculate expected income based on hourly rate
4: Store weekly income projections
5: Handle multiple projects/rates

### Story 8.2: Weekly Invoice Generation

As a freelancer,
I want automatic weekly invoice generation,
so that I maintain consistent cash flow.

#### Acceptance Criteria

1: Generate invoice from Clockify data every Friday
2: PDF invoice with all required fields
3: Send to configured client emails
4: Track invoice sent status
5: Invoice template customization

### Story 8.3: Buffer Calculation Engine

As a user,
I want to see my financial buffer status,
so that I can build emergency funds.

#### Acceptance Criteria

1: Calculate weekly surplus/deficit
2: Show buffer amount and target
3: Growth rate tracking (target 10% weekly)
4: Predict weeks until target reached
5: Visual "health bar" representation

### Story 8.4: Income vs Expenses Analysis

As a user,
I want to see income vs expenses,
so that I understand my cash flow.

#### Acceptance Criteria

1: Weekly income vs expenses chart
2: Category breakdown of expenses
3: Trend analysis over past weeks
4: Projected next week balance
5: Alerts for projected shortfalls

## Epic 9: GitLab/Slack Integration

### Story 9.1: Slack Message Monitor

As a system,
I want to monitor Slack for work tasks,
so that nothing gets forgotten.

#### Acceptance Criteria

1: Slack API token configuration
2: Monitor configured channels for messages
3: Extract tasks from direct messages and mentions
4: Store Slack tasks with source link
5: Mark tasks as processed

### Story 9.2: GitLab Issue Sync

As a system,
I want to compare Slack tasks with GitLab,
so that I can identify ungrecorded work.

#### Acceptance Criteria

1: GitLab API token configuration
2: Fetch issues from configured projects
3: Match Slack tasks to GitLab issues
4: Identify unmatched tasks
5: Daily sync with change detection

### Story 9.3: Task Gap Interface

As a user,
I want to see tasks not in GitLab,
so that I can record them properly.

#### Acceptance Criteria

1: List of unmatched Slack/email tasks
2: One-click create GitLab issue
3: Bulk issue creation
4: Mark as "not a task" option
5: Task age and source display

## Epic 10: Follow-up Automation

### Story 10.1: Commit Message Scanner

As a system,
I want to scan commit messages,
so that I can generate follow-up tasks.

#### Acceptance Criteria

1: Monitor GitLab commits via webhook or polling
2: Detect keywords: fix, feat, BREAKING, deploy
3: Calculate follow-up date based on keyword
4: Create follow-up task record
5: Link to original commit

### Story 10.2: Follow-up Task Generator

As a system,
I want to create follow-up reminders,
so that deployments are verified.

#### Acceptance Criteria

1: Generate task 3 days after "deploy" commits
2: Include commit link and description
3: Assign to committer
4: Set appropriate priority
5: Integrate with notification system

### Story 10.3: Follow-up Dashboard

As a user,
I want to see pending follow-ups,
so that I don't forget post-deployment checks.

#### Acceptance Criteria

1: List of pending follow-up tasks
2: Mark as complete functionality
3: Snooze for 24 hours option
4: Link to original work
5: Overdue follow-up alerts

## Epic 11: ADHD Bypass Strategies

### Story 11.1: Morning GitLab Block

As a user with ADHD,
I want GitLab blocked until I check finances,
so that I can't avoid the task.

#### Acceptance Criteria

1: Browser extension that intercepts GitLab
2: Redirect to finance dashboard
3: Unlock after dashboard interaction
4: Daily reset at midnight
5: Emergency bypass option (logged)

### Story 11.2: Streak Tracking & Gamification

As a user,
I want to see my daily check streak,
so that I'm motivated to maintain habits.

#### Acceptance Criteria

1: Track daily dashboard visits
2: Display current streak prominently
3: Best streak record
4: Visual celebration at milestones
5: Weekly summary of achievements

### Story 11.3: Multi-Channel Notifications

As a user with ADHD,
I want multiple reminder channels,
so that I can't ignore important items.

#### Acceptance Criteria

1: Email notifications for urgent items
2: Browser notifications when dashboard open
3: Optional SMS integration setup
4: Escalating reminder frequency
5: Customizable quiet hours

### Story 11.4: Physical Display Integration

As a user,
I want physical reminders,
so that digital blindness doesn't affect me.

#### Acceptance Criteria

1: API endpoint for display devices
2: Simple status for e-ink display
3: QR code display mode
4: Update frequency configuration
5: Raspberry Pi setup documentation

## Epic 12: Optimizations & Polish

### Story 12.1: Performance Optimization

As a user,
I want fast page loads,
so that I don't lose focus.

#### Acceptance Criteria

1: Dashboard loads under 2 seconds
2: Database query optimization
3: Redis caching implementation
4: Lazy loading for images
5: Performance monitoring dashboard

### Story 12.2: Mobile Responsiveness

As a user,
I want to use the app on my phone,
so that I can check finances anywhere.

#### Acceptance Criteria

1: Responsive layout for all pages
2: Touch-friendly buttons and links
3: Swipe gestures for navigation
4: QR codes sized for mobile screens
5: Tested on iOS and Android browsers

### Story 12.3: Error Recovery & Logging

As a developer,
I want comprehensive error handling,
so that issues can be diagnosed quickly.

#### Acceptance Criteria

1: Structured logging for all operations
2: Error recovery for API failures
3: User-friendly error messages
4: Debug mode with detailed traces
5: Log rotation and cleanup

### Story 12.4: Backup & Data Export

As a user,
I want to backup my data,
so that I don't lose financial history.

#### Acceptance Criteria

1: Manual backup trigger
2: Automated daily backups
3: Export data as CSV/JSON
4: Restore from backup interface
5: Backup retention policy (30 days)

## Checklist Results Report

*To be populated after checklist execution*

## Next Steps

### UX Expert Prompt

Review the KairoFlow PRD and create detailed wireframes for the ADHD-optimized financial dashboard, focusing on visual urgency indicators and zero-friction payment flows.

### Architect Prompt

Review the KairoFlow PRD and create the technical architecture document, emphasizing the email processing pipeline, Kubernetes deployment strategy, and integration patterns for GitLab/Slack/Clockify APIs.