# 5. Data Architecture

## Database Schema

```mermaid
erDiagram
    USERS ||--o{ PAYMENTS : owns
    USERS ||--o{ TASKS : owns
    USERS ||--o{ EMAILS : receives
    USERS ||--o{ NOTIFICATIONS : receives
    
    EMAILS ||--o{ PAYMENTS : generates
    EMAILS ||--o{ TASKS : generates
    EMAILS ||--o{ ATTACHMENTS : contains
    
    PAYMENTS ||--o{ QR_CODES : has
    PAYMENTS ||--o{ PAYMENT_HISTORY : tracks
    
    TASKS ||--o{ GITLAB_ISSUES : syncs
    TASKS ||--o{ FOLLOW_UPS : generates
    
    ACCOUNTS ||--o{ TRANSACTIONS : contains
    ACCOUNTS ||--o{ BALANCES : tracks
    
    USERS {
        uuid id PK
        string email UK
        string password_hash
        string totp_secret
        boolean two_fa_enabled
        timestamp last_login
        int streak_days
        timestamp created_at
    }
    
    EMAILS {
        uuid id PK
        uuid user_id FK
        string from_address
        string subject
        text body_text
        text body_html
        string message_id UK
        string classification
        float confidence_score
        string status
        timestamp received_at
        timestamp processed_at
    }
    
    PAYMENTS {
        uuid id PK
        uuid user_id FK
        uuid email_id FK
        decimal amount
        string currency
        string bank_account
        string variable_symbol
        string constant_symbol
        string specific_symbol
        date due_date
        string status
        string category
        boolean is_recurring
        timestamp paid_at
        timestamp created_at
    }
    
    QR_CODES {
        uuid id PK
        uuid payment_id FK
        string format
        text qr_data
        string image_path
        timestamp generated_at
    }
    
    TASKS {
        uuid id PK
        uuid user_id FK
        uuid email_id FK
        string source
        text description
        string gitlab_issue_id
        string slack_message_id
        string status
        timestamp captured_at
        timestamp synced_at
    }
```

## Caching Strategy

| Data Type | Cache TTL | Invalidation Trigger | Storage |
|-----------|-----------|---------------------|---------|
| Dashboard Summary | 30 seconds | Payment/Task change | Redis |
| GitLab Issues | 5 minutes | Manual sync | Redis |
| Slack Messages | 2 minutes | New message webhook | Redis |
| Clockify Hours | 10 minutes | Time entry change | Redis |
| QR Code Images | 1 hour | Payment completion | Redis + File |
| Bank Balances | Until next email | New bank email | Redis |
| User Session | 24 hours | Logout | Redis |

## Event Sourcing Light

```php
abstract class DomainEvent {
    public readonly string $aggregateId;
    public readonly Carbon $occurredAt;
    public readonly array $metadata;
}

class PaymentCreatedEvent extends DomainEvent {
    public function __construct(
        public readonly string $paymentId,
        public readonly float $amount,
        public readonly string $bankAccount,
        public readonly Carbon $dueDate
    ) {
        $this->aggregateId = $paymentId;
        $this->occurredAt = Carbon::now();
    }
}

class EventBus {
    private array $handlers = [];
    
    public function dispatch(DomainEvent $event): void {
        $eventClass = get_class($event);
        
        if (isset($this->handlers[$eventClass])) {
            foreach ($this->handlers[$eventClass] as $handler) {
                $handler($event);
            }
        }
        
        // Store in event log
        $this->eventStore->append($event);
    }
}
```
