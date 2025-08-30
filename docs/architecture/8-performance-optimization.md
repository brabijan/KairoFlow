# 8. Performance Optimization

## Query Optimization

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

## Frontend Performance

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

## Database Indexing

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
