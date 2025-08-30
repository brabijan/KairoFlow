# Epic 5: Invoice & Bill Processing

## Story 5.1: Invoice Data Extraction

As a system,
I want to extract payment details from invoices,
so that I can create payment records.

### Acceptance Criteria

1: Extract vendor name, amount, due date, bank account
2: Parse variable symbol, constant symbol, specific symbol
3: Detect currency (CZK, EUR, USD)
4: Handle common invoice formats (text patterns)
5: Create payment record in database

## Story 5.2: Payment Deadline Tracking

As a user,
I want to see all upcoming payment deadlines,
so that I can prioritize payments.

### Acceptance Criteria

1: List of payments sorted by due date
2: Visual indicators for urgency (days remaining)
3: Overdue payments highlighted
4: Filter by paid/unpaid status
5: Total amount due calculation

## Story 5.3: Duplicate Payment Detection

As a system,
I want to detect potential duplicate payments,
so that users don't pay twice.

### Acceptance Criteria

1: Check for same amount + variable symbol within 7 days
2: Check for same amount + bank account within 7 days
3: Flag duplicates for manual review
4: Allow user to confirm or dismiss duplicate warning
5: Link related payments in database
