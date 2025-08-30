# Epic 3: Email Classification & Categorization

## Story 3.1: Email Type Detection

As a system,
I want to automatically classify email types,
so that I can route them for appropriate processing.

### Acceptance Criteria

1: Pattern matching for known senders (banks, utilities, etc.)
2: Classification into types: bank_statement, invoice, reminder, work_task, other
3: Confidence score for classification
4: Store classification with email record
5: Rules engine for sender-based classification

## Story 3.2: Manual Classification Interface

As a user,
I want to manually classify unrecognized emails,
so that the system learns my patterns.

### Acceptance Criteria

1: Queue of unclassified emails
2: Dropdown to select email type
3: Additional fields for payment emails (amount, due date, account)
4: Save classification and update email record
5: Bulk classification for multiple similar emails

## Story 3.3: Classification Rules Management

As a user,
I want to create rules for automatic classification,
so that repeated senders are handled automatically.

### Acceptance Criteria

1: Rules list with sender pattern, email type mapping
2: Add/edit/delete rules interface
3: Test rule against existing emails
4: Priority ordering for rules
5: Import/export rules for backup
