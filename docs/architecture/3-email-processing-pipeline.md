# 3. Email Processing Pipeline

## Pipeline Architecture

```mermaid
sequenceDiagram
    participant CRON as CronJob
    participant IMAP as Email Server
    participant FETCH as Email Fetcher
    participant PARSE as Parser Engine
    participant CLASS as Classifier
    participant PROC as Processor
    participant DB as Database
    participant QUEUE as Redis Queue
    participant NOTIF as Notifier
    
    CRON->>FETCH: Trigger (every minute)
    FETCH->>IMAP: Connect & Fetch Unread
    IMAP-->>FETCH: Email Messages
    FETCH->>DB: Store Raw Email
    FETCH->>QUEUE: Queue for Processing
    
    loop For Each Email
        QUEUE->>PARSE: Get Next Email
        PARSE->>CLASS: Identify Type
        CLASS-->>PARSE: Type + Confidence
        
        alt Known Format
            PARSE->>PROC: Process Structured
            PROC->>DB: Store Parsed Data
            PROC->>NOTIF: Trigger Notifications
        else Unknown Format
            PARSE->>QUEUE: Queue for Manual
            PARSE->>NOTIF: Alert User
        end
        
        PARSE->>IMAP: Mark as Read
        PARSE->>DB: Update Status
    end
```

## Email Parser Components

### Bank Statement Parser

```php
interface BankParserInterface {
    public function supports(Email $email): bool;
    public function parse(Email $email): BankStatement;
    public function getConfidence(): float;
}

class CSOBParser implements BankParserInterface {
    // Pattern matching for ČSOB email format
    private const PATTERNS = [
        'balance' => '/Zůstatek:\s*([\d\s,]+)\s*CZK/',
        'account' => '/Účet:\s*([\d\-\/]+)/',
        'transaction' => '/(\d{2}\.\d{2}\.\d{4})\s+(.*?)\s+([\-\+]?[\d\s,]+)/'
    ];
}
```

### Classification Engine

```yaml
Classification Rules:
  - sender: "*@csob.cz"
    type: bank_statement
    parser: CSOBParser
    confidence: 0.95
    
  - sender: "*@kb.cz"
    type: bank_statement
    parser: KBParser
    confidence: 0.95
    
  - subject_contains: "faktura|invoice"
    type: invoice
    parser: InvoiceParser
    confidence: 0.8
    
  - subject_contains: "upomínka|reminder"
    type: payment_reminder
    parser: ReminderParser
    confidence: 0.85
```

## Duplicate Detection Algorithm

```mermaid
flowchart TD
    A[New Payment] --> B{Check VS + Amount}
    B -->|Match Found| C{Within 7 days?}
    C -->|Yes| D[Flag as Duplicate]
    C -->|No| E{Check Account + Amount}
    E -->|Match Found| F{Within 7 days?}
    F -->|Yes| D
    F -->|No| G[Create Payment]
    B -->|No Match| E
    E -->|No Match| G
    D --> H[Queue for Review]
    G --> I[Generate QR Code]
```

## Error Handling & Recovery

| Error Type | Recovery Strategy | Fallback | Notification |
|------------|------------------|----------|--------------|
| IMAP Connection Failed | Exponential backoff (1, 2, 4, 8 min) | Skip cycle | After 3 failures |
| Parse Failed | Queue for manual review | Manual categorization UI | Immediate |
| Duplicate Detected | Flag for user confirmation | Show both payments | Dashboard badge |
| Attachment Corrupted | Retry download 3x | Show email without attachment | Log warning |
| Rate Limit Hit | Wait until reset | Process remaining in next cycle | Log info |
