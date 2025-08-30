# Requirements

## Functional Requirements

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

## Non-Functional Requirements

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
