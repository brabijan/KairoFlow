# Epic 2: Email Infrastructure & Collection

## Story 2.1: Email Account Configuration

As a user,
I want to configure my dedicated email account for monitoring,
so that the system can collect my financial emails.

### Acceptance Criteria

1: Settings page for email configuration (IMAP server, port, username, password)
2: Encrypted storage of email credentials in database
3: Test connection button with success/error feedback
4: Support for common providers (Gmail, Seznam, etc.) with presets
5: SSL/TLS options configurable

## Story 2.2: IMAP Email Fetcher

As a system,
I want to connect to IMAP and fetch new emails,
so that I can process incoming financial documents.

### Acceptance Criteria

1: IMAP connection using configured credentials
2: Fetch only unread emails or emails since last check
3: Mark emails as read after successful processing
4: Handle attachments (PDF, images)
5: Store raw email with headers in database
6: Error handling for connection failures with retry logic

## Story 2.3: Cron Job for Email Monitoring

As a system,
I want to check for new emails every minute,
so that users get near real-time processing.

### Acceptance Criteria

1: Cron job configured to run every minute
2: Prevents concurrent runs with lock mechanism
3: Logs execution time and email count
4: Graceful handling of IMAP timeouts
5: K8s CronJob manifest included in Helm chart
6: Monitoring of cron job failures

## Story 2.4: Email Viewer Interface

As a user,
I want to see all collected emails in a list,
so that I can review what's been captured.

### Acceptance Criteria

1: Paginated list of emails with sender, subject, date
2: Click to view full email content
3: Status indicator (processed/pending/error)
4: Search by sender, subject, date range
5: Mark for reprocessing functionality
6: Attachment indicator and download links
