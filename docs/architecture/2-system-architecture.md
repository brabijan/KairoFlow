# 2. System Architecture

## High-Level Architecture

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

## Component Architecture

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

## Module Boundaries

| Module | Responsibilities | Dependencies | Events Emitted |
|--------|-----------------|--------------|----------------|
| **Finance** | Payment management, QR generation, cash flow tracking | Email, Notification | PaymentCreated, PaymentCompleted, BufferUpdated |
| **Work** | Task tracking, GitLab/Slack sync, follow-up generation | Email, Notification | TaskCreated, TaskSynced, FollowUpDue |
| **Email** | IMAP fetching, parsing, classification | None | EmailReceived, EmailClassified, ParseFailed |
| **Notification** | Multi-channel alerts, ADHD strategies, streak tracking | All modules | NotificationSent, StreakUpdated, MorningCheckCompleted |
