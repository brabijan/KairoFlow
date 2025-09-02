# KairoFlow Technical Architecture Document

## 1. Executive Summary

### System Overview

KairoFlow is a monolithic PHP 8.4+ application built on the Nette Framework, designed to combat financial chaos and work disorganization caused by ADHD through intelligent automation and friction-free interactions. The system operates as a Kubernetes-deployed service with persistent data storage in PostgreSQL, caching via Redis, and multi-channel API integrations.

### Architecture Philosophy

**Simplicity Over Complexity**: Monolithic architecture with modular organization to reduce operational overhead while maintaining clear boundaries. Event-driven communication between modules enables future scaling without immediate microservice complexity.

**ADHD-Driven Design**: Every architectural decision prioritizes reducing cognitive load, automating repetitive tasks, and creating unavoidable system interactions that bypass executive dysfunction.

**Resilient Processing**: Fault-tolerant email parsing pipeline with automatic retries, manual fallback mechanisms, and comprehensive audit logging ensures no financial document is lost.

### Key Technical Decisions

- **Monolithic Application**: Single deployable unit reduces deployment complexity and inter-service communication overhead
- **Email as Universal Hub**: IMAP/POP3 integration provides lowest-friction data ingestion without complex API integrations
- **Kubernetes Native**: Leverages existing Rancher 2 infrastructure with Helm charts for declarative deployment
- **Server-Side Rendering**: Nette/Latte templates with Alpine.js progressive enhancement for sub-2-second page loads
- **Event Sourcing Light**: Module communication via domain events without full CQRS complexity

## 2. System Architecture

### High-Level Architecture

```mermaid
graph TB
    subgraph "External Systems"
        EMAIL[Email Servers<br/>IMAP/POP3]
        GITLAB[GitLab API]
        SLACK[Slack API]
        CLOCKIFY[Clockify API]
        BANK[Banking Apps<br/>QR Scanner]
    end
    
    subgraph "Kubernetes Cluster"
        subgraph "Ingress Layer"
            INGRESS[NGINX Ingress<br/>SSL Termination]
        end
        
        subgraph "Application Layer"
            APP[KairoFlow App<br/>PHP 8.4/Nette]
            CRON[CronJob<br/>Email Processor]
        end
        
        subgraph "Data Layer"
            PG[(PostgreSQL 14+<br/>Main Database)]
            REDIS[(Redis<br/>Cache & Queue)]
        end
        
        subgraph "Storage Layer"
            PV[Persistent Volume<br/>File Storage]
        end
    end
    
    subgraph "User Interfaces"
        WEB[Web Dashboard<br/>Desktop/Mobile]
        EXTENSION[Browser Extension<br/>GitLab Blocker]
    end
    
    EMAIL -->|IMAP Fetch| CRON
    CRON -->|Process| APP
    APP <-->|Query/Store| PG
    APP <-->|Cache/Queue| REDIS
    APP <-->|Store Files| PV
    APP <-->|API Calls| GITLAB
    APP <-->|API Calls| SLACK
    APP <-->|API Calls| CLOCKIFY
    INGRESS -->|HTTPS| APP
    WEB -->|HTTPS| INGRESS
    EXTENSION -->|API| INGRESS
    BANK -->|Scan QR| WEB
```

### Component Architecture

```mermaid
graph LR
    subgraph "KairoFlow Monolith"
        subgraph "Presentation Layer"
            CTRL[Controllers]
            PRES[Presenters]
            FORMS[Forms]
            LATTE[Latte Templates]
        end
        
        subgraph "Application Layer"
            subgraph "Finance Module"
                FIN_SVC[Finance Service]
                PAY_SVC[Payment Service]
                QR_GEN[QR Generator]
            end
            
            subgraph "Work Module"
                TASK_SVC[Task Service]
                GITLAB_SVC[GitLab Service]
                SLACK_SVC[Slack Service]
            end
            
            subgraph "Email Module"
                EMAIL_SVC[Email Service]
                PARSER[Parser Engine]
                CLASS[Classifier]
            end
            
            subgraph "Notification Module"
                NOTIF_SVC[Notification Service]
                ADHD_ENG[ADHD Bypass Engine]
            end
        end
        
        subgraph "Domain Layer"
            ENTITIES[Doctrine Entities]
            EVENTS[Domain Events]
            VALUE[Value Objects]
        end
        
        subgraph "Infrastructure Layer"
            REPOS[Repositories]
            ADAPT[External Adapters]
            CACHE[Cache Manager]
            QUEUE[Queue Manager]
        end
    end
    
    CTRL --> FIN_SVC
    CTRL --> TASK_SVC
    FIN_SVC --> REPOS
    TASK_SVC --> REPOS
    EMAIL_SVC --> PARSER
    PARSER --> CLASS
    FIN_SVC --> QR_GEN
    TASK_SVC --> GITLAB_SVC
    TASK_SVC --> SLACK_SVC
    NOTIF_SVC --> ADHD_ENG
```

### Module Boundaries

| Module | Responsibilities | Dependencies | Events Emitted |
|--------|-----------------|--------------|----------------|
| **Finance** | Payment management, QR generation, cash flow tracking | Email, Notification | PaymentCreated, PaymentCompleted, BufferUpdated |
| **Work** | Task tracking, GitLab/Slack sync, follow-up generation | Email, Notification | TaskCreated, TaskSynced, FollowUpDue |
| **Email** | IMAP fetching, parsing, classification | None | EmailReceived, EmailClassified, ParseFailed |
| **Notification** | Multi-channel alerts, ADHD strategies, streak tracking | All modules | NotificationSent, StreakUpdated, MorningCheckCompleted |

## 3. Email Processing Pipeline

### Pipeline Architecture

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

### Email Parser Components

#### Bank Statement Parser

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

#### Classification Engine

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

### Duplicate Detection Algorithm

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

### Error Handling & Recovery

| Error Type | Recovery Strategy | Fallback | Notification |
|------------|------------------|----------|--------------|
| IMAP Connection Failed | Exponential backoff (1, 2, 4, 8 min) | Skip cycle | After 3 failures |
| Parse Failed | Queue for manual review | Manual categorization UI | Immediate |
| Duplicate Detected | Flag for user confirmation | Show both payments | Dashboard badge |
| Attachment Corrupted | Retry download 3x | Show email without attachment | Log warning |
| Rate Limit Hit | Wait until reset | Process remaining in next cycle | Log info |

## 4. API Integration Patterns

### Integration Architecture

```mermaid
graph TD
    subgraph "Adapter Layer"
        GITLAB_ADAPT[GitLab Adapter]
        SLACK_ADAPT[Slack Adapter]
        CLOCK_ADAPT[Clockify Adapter]
    end
    
    subgraph "Circuit Breaker"
        CB_GITLAB[GitLab CB]
        CB_SLACK[Slack CB]
        CB_CLOCK[Clockify CB]
    end
    
    subgraph "Cache Layer"
        CACHE_GL[GitLab Cache<br/>5 min TTL]
        CACHE_SL[Slack Cache<br/>2 min TTL]
        CACHE_CL[Clockify Cache<br/>10 min TTL]
    end
    
    subgraph "External APIs"
        GITLAB_API[GitLab API]
        SLACK_API[Slack API]
        CLOCK_API[Clockify API]
    end
    
    GITLAB_ADAPT --> CB_GITLAB
    CB_GITLAB --> CACHE_GL
    CACHE_GL --> GITLAB_API
    
    SLACK_ADAPT --> CB_SLACK
    CB_SLACK --> CACHE_SL
    CACHE_SL --> SLACK_API
    
    CLOCK_ADAPT --> CB_CLOCK
    CB_CLOCK --> CACHE_CL
    CACHE_CL --> CLOCK_API
```

### GitLab Integration

#### API Configuration

```yaml
gitlab:
  base_url: "https://gitlab.com/api/v4"
  token: "${GITLAB_TOKEN}"
  rate_limit: 600/min
  timeout: 10s
  retry:
    max_attempts: 3
    backoff: exponential
  projects:
    - id: 12345
      track_commits: true
      track_issues: true
```

#### Task Sync Flow

```php
class GitLabSyncService {
    public function syncTasks(): SyncResult {
        // 1. Fetch recent Slack messages
        $slackTasks = $this->slackService->getRecentMentions();
        
        // 2. Fetch GitLab issues
        $gitlabIssues = $this->gitlabAdapter->getOpenIssues();
        
        // 3. Compare and find gaps
        $unmatchedTasks = $this->taskMatcher->findGaps(
            $slackTasks, 
            $gitlabIssues
        );
        
        // 4. Queue for user review
        foreach ($unmatchedTasks as $task) {
            $this->taskQueue->add($task);
        }
        
        return new SyncResult($unmatchedTasks);
    }
}
```

### Slack Integration

#### Message Monitoring

```php
class SlackMonitor {
    private const KEYWORDS = ['task', 'please', 'can you', 'need', 'urgent'];
    
    public function extractTasks(array $messages): array {
        $tasks = [];
        
        foreach ($messages as $message) {
            if ($this->isTask($message)) {
                $tasks[] = new Task(
                    source: 'slack',
                    content: $message['text'],
                    author: $message['user'],
                    timestamp: $message['ts'],
                    channel: $message['channel']
                );
            }
        }
        
        return $tasks;
    }
    
    private function isTask(array $message): bool {
        // Check for direct mentions
        if (str_contains($message['text'], '<@' . $this->userId . '>')) {
            return true;
        }
        
        // Check for task keywords
        foreach (self::KEYWORDS as $keyword) {
            if (stripos($message['text'], $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
```

### Clockify Integration

#### Weekly Invoice Generation

```php
class ClockifyInvoiceService {
    public function generateWeeklyInvoice(): Invoice {
        // 1. Fetch current week's time entries
        $entries = $this->clockifyAdapter->getTimeEntries(
            start: Carbon::now()->startOfWeek(),
            end: Carbon::now()->endOfWeek()
        );
        
        // 2. Group by project and calculate totals
        $projects = $this->groupByProject($entries);
        
        // 3. Generate invoice
        $invoice = new Invoice();
        foreach ($projects as $project) {
            $invoice->addLine(
                description: $project->name,
                hours: $project->totalHours,
                rate: $project->hourlyRate,
                amount: $project->totalHours * $project->hourlyRate
            );
        }
        
        // 4. Create PDF
        $pdf = $this->pdfGenerator->generate($invoice);
        
        // 5. Send to client
        $this->emailService->send($invoice->client->email, $pdf);
        
        return $invoice;
    }
}
```

### Circuit Breaker Implementation

```php
class CircuitBreaker {
    private int $failureThreshold = 5;
    private int $timeout = 60; // seconds
    private int $failureCount = 0;
    private ?Carbon $lastFailure = null;
    private string $state = 'CLOSED'; // CLOSED, OPEN, HALF_OPEN
    
    public function call(callable $operation) {
        if ($this->state === 'OPEN') {
            if ($this->shouldAttemptReset()) {
                $this->state = 'HALF_OPEN';
            } else {
                throw new CircuitOpenException();
            }
        }
        
        try {
            $result = $operation();
            $this->onSuccess();
            return $result;
        } catch (\Exception $e) {
            $this->onFailure();
            throw $e;
        }
    }
}
```

## 5. Data Architecture

### Database Schema

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

### Caching Strategy

| Data Type | Cache TTL | Invalidation Trigger | Storage |
|-----------|-----------|---------------------|---------|
| Dashboard Summary | 30 seconds | Payment/Task change | Redis |
| GitLab Issues | 5 minutes | Manual sync | Redis |
| Slack Messages | 2 minutes | New message webhook | Redis |
| Clockify Hours | 10 minutes | Time entry change | Redis |
| QR Code Images | 1 hour | Payment completion | Redis + File |
| Bank Balances | Until next email | New bank email | Redis |
| User Session | 24 hours | Logout | Redis |

### Event Sourcing Light

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

## 6. Kubernetes Deployment

### Deployment Architecture

```yaml
apiVersion: v1
kind: Namespace
metadata:
  name: kairoflow
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: kairoflow-app
  namespace: kairoflow
spec:
  replicas: 2
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 1
      maxUnavailable: 0
  selector:
    matchLabels:
      app: kairoflow
  template:
    metadata:
      labels:
        app: kairoflow
    spec:
      containers:
      - name: app
        image: kairoflow:latest
        ports:
        - containerPort: 80
        env:
        - name: DATABASE_URL
          valueFrom:
            secretKeyRef:
              name: kairoflow-secrets
              key: database-url
        - name: REDIS_URL
          valueFrom:
            secretKeyRef:
              name: kairoflow-secrets
              key: redis-url
        resources:
          requests:
            memory: "256Mi"
            cpu: "250m"
          limits:
            memory: "512Mi"
            cpu: "500m"
        livenessProbe:
          httpGet:
            path: /health
            port: 80
          initialDelaySeconds: 30
          periodSeconds: 10
        readinessProbe:
          httpGet:
            path: /ready
            port: 80
          initialDelaySeconds: 5
          periodSeconds: 5
        volumeMounts:
        - name: file-storage
          mountPath: /app/www/uploads
      volumes:
      - name: file-storage
        persistentVolumeClaim:
          claimName: kairoflow-files
```

### CronJob Configuration

```yaml
apiVersion: batch/v1
kind: CronJob
metadata:
  name: email-processor
  namespace: kairoflow
spec:
  schedule: "*/1 * * * *"  # Every minute
  concurrencyPolicy: Forbid  # Prevent overlapping runs
  successfulJobsHistoryLimit: 3
  failedJobsHistoryLimit: 3
  jobTemplate:
    spec:
      activeDeadlineSeconds: 50  # Kill if runs > 50 seconds
      template:
        spec:
          containers:
          - name: processor
            image: kairoflow:latest
            command: ["php", "bin/console", "email:process"]
            env:
            - name: DATABASE_URL
              valueFrom:
                secretKeyRef:
                  name: kairoflow-secrets
                  key: database-url
            resources:
              requests:
                memory: "128Mi"
                cpu: "100m"
              limits:
                memory: "256Mi"
                cpu: "200m"
          restartPolicy: OnFailure
```

### Helm Chart Structure

```yaml
# values.yaml
replicaCount: 2

image:
  repository: kairoflow
  tag: latest
  pullPolicy: IfNotPresent

service:
  type: ClusterIP
  port: 80

ingress:
  enabled: true
  className: nginx
  annotations:
    cert-manager.io/cluster-issuer: letsencrypt-prod
    nginx.ingress.kubernetes.io/ssl-redirect: "true"
  hosts:
    - host: kairoflow.carpiftw.cz
      paths:
        - path: /
          pathType: Prefix
  tls:
    - secretName: kairoflow-tls
      hosts:
        - kairoflow.carpiftw.cz

postgresql:
  enabled: true
  auth:
    database: kairoflow
    username: kairoflow
  persistence:
    enabled: true
    size: 10Gi

redis:
  enabled: true
  auth:
    enabled: true
  persistence:
    enabled: true
    size: 1Gi

persistence:
  enabled: true
  size: 5Gi
  storageClass: standard

autoscaling:
  enabled: false  # Manual scaling for cost control
  minReplicas: 2
  maxReplicas: 4
  targetCPUUtilizationPercentage: 80

monitoring:
  enabled: true
  prometheus:
    enabled: true
  grafana:
    enabled: true
```

### Health Check Implementation

```php
class HealthController extends BaseController {
    public function health(): JsonResponse {
        // Basic liveness check
        return new JsonResponse(['status' => 'ok']);
    }
    
    public function ready(): JsonResponse {
        $checks = [];
        
        // Database check
        try {
            $this->database->query('SELECT 1');
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'failed';
            return new JsonResponse($checks, 503);
        }
        
        // Redis check
        try {
            $this->redis->ping();
            $checks['redis'] = 'ok';
        } catch (\Exception $e) {
            $checks['redis'] = 'failed';
            return new JsonResponse($checks, 503);
        }
        
        // Email server check (non-blocking)
        try {
            $this->emailService->testConnection();
            $checks['email'] = 'ok';
        } catch (\Exception $e) {
            $checks['email'] = 'degraded';
            // Don't fail readiness for email
        }
        
        return new JsonResponse($checks);
    }
}
```

## 7. Security Architecture

### Authentication & Authorization

```mermaid
sequenceDiagram
    participant User
    participant Browser
    participant App
    participant Redis
    participant DB
    
    User->>Browser: Enter credentials
    Browser->>App: POST /login
    App->>DB: Verify credentials
    DB-->>App: User data
    
    alt 2FA Enabled
        App->>Browser: Request TOTP
        Browser->>User: Show TOTP input
        User->>Browser: Enter TOTP
        Browser->>App: Submit TOTP
        App->>App: Verify TOTP
    end
    
    App->>Redis: Create session
    App->>Browser: Set secure cookie
    Browser->>App: Subsequent requests
    App->>Redis: Verify session
    Redis-->>App: Session data
    App->>Browser: Authorized response
```

### Data Encryption

| Data Type | At Rest | In Transit | Method |
|-----------|---------|------------|--------|
| Passwords | ✓ | ✓ | bcrypt (cost=12) |
| Email Credentials | ✓ | ✓ | AES-256-GCM |
| API Tokens | ✓ | ✓ | AES-256-GCM |
| Bank Account Numbers | ✓ | ✓ | AES-256-GCM |
| Session Data | ✓ | ✓ | Redis encrypted |
| File Attachments | ✗ | ✓ | HTTPS only |
| QR Code Images | ✗ | ✓ | HTTPS only |

### Secret Management

```yaml
apiVersion: v1
kind: Secret
metadata:
  name: kairoflow-secrets
  namespace: kairoflow
type: Opaque
stringData:
  database-url: "postgresql://user:pass@postgres:5432/kairoflow"
  redis-url: "redis://:password@redis:6379/0"
  gitlab-token: "glpat-xxxxxxxxxxxxxxxxxxxx"
  slack-token: "xoxb-xxxxxxxxxxxxxxxxxxxx"
  clockify-key: "xxxxxxxxxxxxxxxxxxxxxxxxxx"
  encryption-key: "base64:xxxxxxxxxxxxxxxxxxxxxxxxxxx"
  app-secret: "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
```

## 8. Performance Optimization

### Query Optimization

```php
class PaymentRepository {
    public function getUrgentPayments(User $user): array {
        return $this->createQueryBuilder('p')
            ->select('p', 'q')  // Eager load QR codes
            ->leftJoin('p.qrCodes', 'q')
            ->where('p.user = :user')
            ->andWhere('p.status = :status')
            ->andWhere('p.dueDate <= :threshold')
            ->setParameter('user', $user)
            ->setParameter('status', PaymentStatus::PENDING)
            ->setParameter('threshold', Carbon::now()->addDays(3))
            ->orderBy('p.dueDate', 'ASC')
            ->getQuery()
            ->enableResultCache(30)  // Cache for 30 seconds
            ->getResult();
    }
}
```

### Frontend Performance

```javascript
// Alpine.js lazy loading for heavy components
document.addEventListener('alpine:init', () => {
    Alpine.data('qrGrid', () => ({
        payments: [],
        loading: false,
        
        async init() {
            // Load QR codes only when visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadQR(entry.target.dataset.paymentId);
                    }
                });
            });
            
            this.$refs.grid.querySelectorAll('.qr-placeholder').forEach(el => {
                observer.observe(el);
            });
        },
        
        async loadQR(paymentId) {
            // Fetch QR code on demand
            const response = await fetch(`/api/qr/${paymentId}`);
            const qrData = await response.json();
            this.payments[paymentId] = qrData;
        }
    }));
});
```

### Database Indexing

```sql
-- Critical performance indexes
CREATE INDEX idx_payments_user_status_due ON payments(user_id, status, due_date);
CREATE INDEX idx_emails_user_status_received ON emails(user_id, status, received_at);
CREATE INDEX idx_tasks_user_source_status ON tasks(user_id, source, status);
CREATE INDEX idx_transactions_account_date ON transactions(account_id, transaction_date);

-- Unique constraints for duplicate prevention
CREATE UNIQUE INDEX idx_payments_unique_vs ON payments(variable_symbol, bank_account, due_date)
    WHERE status = 'pending';
CREATE UNIQUE INDEX idx_emails_message_id ON emails(message_id);
CREATE UNIQUE INDEX idx_tasks_slack_message ON tasks(slack_message_id)
    WHERE slack_message_id IS NOT NULL;
```

## 9. Monitoring & Observability

### Metrics Collection

```yaml
Prometheus Metrics:
  - kairoflow_emails_processed_total
  - kairoflow_payments_created_total
  - kairoflow_qr_codes_generated_total
  - kairoflow_tasks_synced_total
  - kairoflow_api_requests_duration_seconds
  - kairoflow_cron_execution_duration_seconds
  - kairoflow_duplicate_payments_detected_total
  - kairoflow_streak_days_current
  - kairoflow_buffer_amount_czk
```

### Logging Strategy

```php
class StructuredLogger {
    public function logEmailProcessed(Email $email, array $context = []): void {
        $this->logger->info('Email processed', array_merge([
            'email_id' => $email->getId(),
            'from' => $email->getFrom(),
            'classification' => $email->getClassification(),
            'confidence' => $email->getConfidenceScore(),
            'processing_time_ms' => $context['processing_time'] ?? null,
            'attachments_count' => count($email->getAttachments()),
        ], $context));
    }
    
    public function logPaymentCreated(Payment $payment): void {
        $this->logger->info('Payment created', [
            'payment_id' => $payment->getId(),
            'amount' => $payment->getAmount(),
            'due_date' => $payment->getDueDate()->toIso8601String(),
            'days_until_due' => $payment->getDaysUntilDue(),
            'is_duplicate' => $payment->isDuplicate(),
        ]);
    }
}
```

### Alert Configuration

| Alert | Condition | Severity | Action |
|-------|-----------|----------|--------|
| Email Processing Failed | > 3 consecutive failures | Critical | Page on-call |
| High Duplicate Rate | > 20% duplicates in 1 hour | Warning | Dashboard notification |
| API Rate Limit Near | > 80% of limit | Warning | Reduce polling frequency |
| Database Connection Pool | > 90% utilized | Critical | Scale replicas |
| Payment Overdue | Due date passed | Info | User notification |
| Streak Broken | No daily check | Info | Multi-channel reminder |

## 10. Development & Testing

### Development Environment

```yaml
# docker-compose.yml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8080:80"
    volumes:
      - .:/app
      - ./temp:/app/temp
      - ./log:/app/log
    environment:
      - NETTE_DEBUG=1
      - DATABASE_URL=postgresql://kairoflow:kairoflow@postgres:5432/kairoflow
      - REDIS_URL=redis://redis:6379
    depends_on:
      - postgres
      - redis
      - mailhog

  postgres:
    image: postgres:14-alpine
    environment:
      - POSTGRES_DB=kairoflow
      - POSTGRES_USER=kairoflow
      - POSTGRES_PASSWORD=kairoflow
    volumes:
      - postgres_data:/var/lib/postgresql/data
    ports:
      - "5432:5432"

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"

  mailhog:
    image: mailhog/mailhog
    ports:
      - "1025:1025"
      - "8025:8025"

volumes:
  postgres_data:
```

### Testing Strategy

```php
// Unit Test Example
class PaymentServiceTest extends TestCase {
    public function testDuplicateDetection(): void {
        $existingPayment = $this->createPayment([
            'amount' => 1000,
            'variable_symbol' => '123456',
            'due_date' => Carbon::now()->addDays(5),
        ]);
        
        $newPayment = $this->createPayment([
            'amount' => 1000,
            'variable_symbol' => '123456',
            'due_date' => Carbon::now()->addDays(6),
        ]);
        
        $isDuplicate = $this->paymentService->isDuplicate($newPayment);
        
        $this->assertTrue($isDuplicate);
    }
}

// Integration Test Example
class EmailProcessingTest extends IntegrationTestCase {
    public function testBankStatementProcessing(): void {
        $email = $this->loadFixture('csob_statement.eml');
        
        $this->emailProcessor->process($email);
        
        $this->assertDatabaseHas('emails', [
            'classification' => 'bank_statement',
            'status' => 'processed',
        ]);
        
        $this->assertDatabaseHas('transactions', [
            'amount' => 15000,
            'description' => 'Invoice payment',
        ]);
    }
}
```

## 11. Migration & Rollback Strategy

### Database Migrations

```php
class CreatePaymentsTable extends AbstractMigration {
    public function up(Schema $schema): void {
        $table = $schema->createTable('payments');
        $table->addColumn('id', 'uuid');
        $table->addColumn('user_id', 'uuid');
        $table->addColumn('email_id', 'uuid', ['notnull' => false]);
        $table->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2]);
        $table->addColumn('currency', 'string', ['length' => 3, 'default' => 'CZK']);
        $table->addColumn('bank_account', 'string', ['length' => 50]);
        $table->addColumn('variable_symbol', 'string', ['length' => 10, 'notnull' => false]);
        $table->addColumn('due_date', 'date');
        $table->addColumn('status', 'string', ['length' => 20]);
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('updated_at', 'datetime');
        
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id', 'status', 'due_date']);
        $table->addForeignKeyConstraint('users', ['user_id'], ['id']);
    }
    
    public function down(Schema $schema): void {
        $schema->dropTable('payments');
    }
}
```

### Deployment Strategy

```mermaid
graph LR
    A[Build & Test] --> B[Push to Registry]
    B --> C[Update Staging]
    C --> D{Tests Pass?}
    D -->|Yes| E[Blue-Green Deploy]
    D -->|No| F[Rollback]
    E --> G[Health Checks]
    G --> H{Healthy?}
    H -->|Yes| I[Switch Traffic]
    H -->|No| F
    I --> J[Monitor Metrics]
    J --> K{Issues?}
    K -->|Yes| F
    K -->|No| L[Complete]
```

## 12. Disaster Recovery

### Backup Strategy

```yaml
Backup Schedule:
  Database:
    - Full: Daily at 02:00 UTC
    - Incremental: Every 6 hours
    - Retention: 30 days
    
  Files:
    - Full: Weekly
    - Incremental: Daily
    - Retention: 90 days
    
  Redis:
    - Snapshot: Every hour
    - Retention: 24 hours

Recovery Targets:
  RPO (Recovery Point Objective): 6 hours
  RTO (Recovery Time Objective): 2 hours
```

### Failure Scenarios

| Scenario | Impact | Recovery Plan | Estimated Recovery Time |
|----------|--------|--------------|------------------------|
| Database Failure | Complete outage | Restore from backup + replay events | 2 hours |
| Redis Failure | Loss of cache/sessions | Restart with empty cache, users re-login | 15 minutes |
| Email Server Down | No new emails processed | Queue for later processing | No data loss |
| K8s Node Failure | Reduced capacity | Pods reschedule automatically | 5 minutes |
| Complete Cluster Failure | Full outage | Restore from backup to new cluster | 4-6 hours |

## 13. Technical Debt & Future Considerations

### Identified Technical Debt

1. **Email Parser Fragility**: Hard-coded patterns for bank emails will break when formats change
   - *Mitigation*: Implement versioned parsers with fallback chains
   
2. **Monolithic Coupling**: Modules communicate directly rather than through events
   - *Mitigation*: Gradually introduce event bus for inter-module communication
   
3. **Manual Duplicate Detection**: Rule-based detection will miss edge cases
   - *Mitigation*: Phase 2 - ML-based duplicate detection
   
4. **No API Versioning**: External integrations have no version management
   - *Mitigation*: Implement API gateway with version routing

### Scalability Considerations

```mermaid
graph TD
    A[Current: Monolith] --> B{Growth Triggers}
    B -->|>100 users| C[Extract Email Processing]
    B -->|>1000 emails/day| D[Dedicated Parser Service]
    B -->|>10000 payments/month| E[Separate Payment Service]
    
    C --> F[Microservices Architecture]
    D --> F
    E --> F
    
    F --> G[Service Mesh]
    G --> H[Multi-Region Deployment]
```

### Future Architecture Evolution

**Phase 2 (3-6 months)**:
- LLM integration for intelligent parsing
- WebSocket for real-time updates
- GraphQL API for flexible client queries

**Phase 3 (6-12 months)**:
- Event sourcing for complete audit trail
- CQRS for read/write separation
- Async job processing with dedicated workers

**Phase 4 (12+ months)**:
- Multi-tenancy for SaaS offering
- Kubernetes operator for automated operations
- Edge deployment for global latency optimization

## 14. Conclusion

This architecture provides a robust foundation for KairoFlow that balances simplicity with extensibility. The monolithic approach with modular organization allows rapid development while maintaining clear boundaries for future service extraction. The email-centric processing pipeline with multiple fallback mechanisms ensures reliability, while the Kubernetes deployment strategy provides operational excellence.

Key architectural wins:
- **ADHD-Optimized**: Every design decision reduces cognitive load
- **Fault Tolerant**: Multiple recovery mechanisms at each layer
- **Observable**: Comprehensive metrics and structured logging
- **Evolvable**: Clear module boundaries enable gradual migration to microservices
- **Performant**: Strategic caching and optimized queries ensure sub-2-second loads

The system is designed to start simple, prove value quickly, and evolve based on real usage patterns rather than premature optimization.

---

## Appendix A: Technology Stack Summary

| Layer | Technology | Version | Purpose |
|-------|------------|---------|---------|
| Language | PHP | 8.4+ | Primary application language |
| Framework | Nette | 3.2+ | Web framework |
| ORM | Nettrine (Doctrine) | 0.9+ | Database abstraction |
| Database | PostgreSQL | 14+ | Primary data store |
| Cache | Redis | 7+ | Session & cache storage |
| Console | Contributte Console | 0.10+ | CLI commands |
| Logging | Contributte Monolog | 0.6+ | Structured logging |
| Middleware | Contributte Middlewares | 0.10+ | PSR-15 support |
| Frontend | Alpine.js | 3.x | Interactive components |
| CSS | Tailwind | 3.x | Utility-first styling |
| Container | Docker | Latest | Application packaging |
| Orchestration | Kubernetes | 1.28+ | Container orchestration |
| Deployment | Helm | 3.x | K8s package management |
| Monitoring | Prometheus | Latest | Metrics collection |

## Appendix B: API Endpoints

| Endpoint | Method | Purpose | Auth Required |
|----------|--------|---------|---------------|
| `/health` | GET | Liveness probe | No |
| `/ready` | GET | Readiness probe | No |
| `/login` | POST | User authentication | No |
| `/logout` | POST | End session | Yes |
| `/dashboard` | GET | Main dashboard | Yes |
| `/payments` | GET | Payment list | Yes |
| `/payments/{id}/qr` | GET | Generate QR code | Yes |
| `/payments/{id}/complete` | POST | Mark as paid | Yes |
| `/tasks` | GET | Task inbox | Yes |
| `/tasks/{id}/sync` | POST | Create GitLab issue | Yes |
| `/api/webhook/gitlab` | POST | GitLab webhooks | Token |
| `/api/webhook/slack` | POST | Slack events | Token |

---

This technical architecture document provides a comprehensive blueprint for building KairoFlow with emphasis on the email processing pipeline, Kubernetes deployment, and API integration patterns as requested. The architecture is specifically optimized for ADHD users while maintaining technical excellence and operational reliability.