# 7. Security Architecture

## Authentication & Authorization

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

## Data Encryption

| Data Type | At Rest | In Transit | Method |
|-----------|---------|------------|--------|
| Passwords | ✓ | ✓ | bcrypt (cost=12) |
| Email Credentials | ✓ | ✓ | AES-256-GCM |
| API Tokens | ✓ | ✓ | AES-256-GCM |
| Bank Account Numbers | ✓ | ✓ | AES-256-GCM |
| Session Data | ✓ | ✓ | Redis encrypted |
| File Attachments | ✗ | ✓ | HTTPS only |
| QR Code Images | ✗ | ✓ | HTTPS only |

## Secret Management

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
