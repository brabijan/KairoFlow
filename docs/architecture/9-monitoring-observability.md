# 9. Monitoring & Observability

## Metrics Collection

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

## Logging Strategy

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

## Alert Configuration

| Alert | Condition | Severity | Action |
|-------|-----------|----------|--------|
| Email Processing Failed | > 3 consecutive failures | Critical | Page on-call |
| High Duplicate Rate | > 20% duplicates in 1 hour | Warning | Dashboard notification |
| API Rate Limit Near | > 80% of limit | Warning | Reduce polling frequency |
| Database Connection Pool | > 90% utilized | Critical | Scale replicas |
| Payment Overdue | Due date passed | Info | User notification |
| Streak Broken | No daily check | Info | Multi-channel reminder |
