# 4. API Integration Patterns

## Integration Architecture

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

## GitLab Integration

### API Configuration

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

### Task Sync Flow

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

## Slack Integration

### Message Monitoring

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

## Clockify Integration

### Weekly Invoice Generation

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

## Circuit Breaker Implementation

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
