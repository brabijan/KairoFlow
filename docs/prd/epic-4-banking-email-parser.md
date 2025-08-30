# Epic 4: Banking Email Parser

## Story 4.1: Czech Bank Format Parser

As a system,
I want to parse Czech bank email formats,
so that I can extract transaction data automatically.

### Acceptance Criteria

1: Parser for major Czech banks (ČSOB, KB, ČS, Fio, Moneta)
2: Extract account number, balance, transaction list
3: Parse transaction date, amount, description, counterparty
4: Handle both HTML and plain text formats
5: Store parsed data in structured database tables

## Story 4.2: Bank Statement Storage

As a system,
I want to store parsed bank statements,
so that I can track account balances over time.

### Acceptance Criteria

1: Database schema for accounts, transactions
2: Deduplication of transactions by bank reference
3: Running balance calculation
4: Link transactions to source email
5: Transaction categorization fields

## Story 4.3: Bank Parser Testing Interface

As a developer,
I want to test bank parsers with sample emails,
so that I can verify parsing accuracy.

### Acceptance Criteria

1: Upload or paste email content for testing
2: Show parsed output in structured format
3: Highlight parsing errors or missing fields
4: Save test cases for regression testing
5: Parser accuracy metrics dashboard
