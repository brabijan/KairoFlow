# KairoFlow Coding Standards

## PHP/Nette Framework Standards

### General Principles
- **Type Safety**: Use PHP 8.4+ type declarations and strict types
- **PSR Compliance**: Follow PSR-12 for code style, PSR-4 for autoloading
- **Domain-Driven Design**: Organize code by business domain, not technical layers
- **Dependency Injection**: Use Nette DI container for all dependencies
- **Immutability**: Prefer immutable value objects where appropriate

### PHP Code Style
```php
<?php
declare(strict_types=1);

namespace App\Finance\Service;

use App\Finance\Entity\Payment;
use App\Finance\ValueObject\Money;
use Nette\Utils\DateTime;

final class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $repository,
        private readonly QrCodeGenerator $qrGenerator,
        private readonly EventDispatcher $dispatcher,
    ) {}
    
    public function createPayment(
        Money $amount,
        string $bankAccount,
        DateTime $dueDate,
    ): Payment {
        $payment = new Payment(
            amount: $amount,
            bankAccount: $bankAccount,
            dueDate: $dueDate,
        );
        
        $this->repository->persist($payment);
        $this->dispatcher->dispatch(new PaymentCreatedEvent($payment));
        
        return $payment;
    }
}
```

### Naming Conventions
- **Classes**: PascalCase - `PaymentService`, `EmailParser`
- **Methods**: camelCase - `calculateTotal`, `parseEmail`
- **Properties**: camelCase - `$bankAccount`, `$dueDate`
- **Constants**: UPPER_SNAKE_CASE - `MAX_RETRIES`, `DEFAULT_TIMEOUT`
- **Files**:
  - Classes: Match class name - `PaymentService.php`
  - Config: lowercase with hyphens - `common.neon`, `database-config.neon`
  - Templates: camelCase - `paymentList.latte`

### Nette Framework Standards

#### Presenter Structure
```php
<?php
declare(strict_types=1);

namespace App\UI\Payment;

use App\UI\BasePresenter;
use App\Finance\Service\PaymentService;
use Nette\Application\UI\Form;

final class PaymentPresenter extends BasePresenter
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {
        parent::__construct();
    }
    
    public function renderDefault(int $page = 1): void
    {
        $this->template->payments = $this->paymentService->findPending(
            user: $this->getUser()->getId(),
            page: $page,
        );
        $this->template->page = $page;
    }
    
    public function actionComplete(string $id): void
    {
        $payment = $this->paymentService->find($id);
        
        if (!$payment) {
            $this->error('Payment not found', 404);
        }
        
        $this->paymentService->markComplete($payment);
        $this->flashMessage('Payment marked as complete', 'success');
        $this->redirect('default');
    }
    
    protected function createComponentPaymentForm(): Form
    {
        $form = new Form;
        
        $form->addText('amount', 'Amount')
            ->setRequired()
            ->addRule($form::FLOAT, 'Amount must be a number');
            
        $form->addText('bankAccount', 'Bank Account')
            ->setRequired()
            ->addRule($form::PATTERN, 'Invalid account format', '[0-9-/]+');
            
        $form->addSubmit('send', 'Create Payment');
        
        $form->onSuccess[] = [$this, 'paymentFormSucceeded'];
        
        return $form;
    }
}
```

#### Latte Template Standards
```latte
{* app/UI/Payment/templates/default.latte *}
{block content}
<div class="payment-list">
    <h1 n:block="title">Pending Payments</h1>
    
    {if $payments->count() > 0}
        <div class="grid" n:foreach="$payments as $payment">
            <div class="payment-card">
                <h3>{$payment->getDescription()}</h3>
                <p class="amount">{$payment->getAmount()|money}</p>
                <p class="due-date">
                    Due: {$payment->getDueDate()|date:'j.n.Y'}
                </p>
                
                {if $payment->hasQrCode()}
                    <img src="{link qr $payment->getId()}" 
                         alt="QR Code" 
                         loading="lazy">
                {/if}
                
                <a n:href="complete $payment->getId()" 
                   class="btn btn-primary"
                   data-confirm="Mark payment as complete?">
                    Mark Complete
                </a>
            </div>
        </div>
    {else}
        <p class="empty-state">No pending payments</p>
    {/if}
    
    {control paginator}
</div>
{/block}
```

### Error Handling

#### Exception Classes
```php
<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;

class DomainException extends Exception
{
    public function __construct(
        string $message,
        private readonly ?array $context = null,
        int $code = 0,
        ?Exception $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
    
    public function getContext(): ?array
    {
        return $this->context;
    }
}

class ValidationException extends DomainException
{
    public function __construct(
        string $message,
        private readonly array $errors = [],
    ) {
        parent::__construct($message, ['errors' => $errors]);
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}

class PaymentDuplicateException extends DomainException
{
    public function __construct(
        private readonly Payment $existing,
        private readonly Payment $duplicate,
    ) {
        parent::__construct(
            'Duplicate payment detected',
            [
                'existing_id' => $existing->getId(),
                'duplicate_data' => $duplicate->toArray(),
            ]
        );
    }
}
```

#### Error Handling in Presenters
```php
public function actionProcess(string $id): void
{
    try {
        $payment = $this->paymentService->process($id);
        $this->flashMessage('Payment processed successfully', 'success');
        $this->redirect('detail', $payment->getId());
        
    } catch (PaymentNotFoundException $e) {
        $this->error('Payment not found', 404);
        
    } catch (PaymentDuplicateException $e) {
        $this->flashMessage('Duplicate payment detected', 'warning');
        $this->redirect('review', $e->getExisting()->getId());
        
    } catch (ValidationException $e) {
        foreach ($e->getErrors() as $error) {
            $this->flashMessage($error, 'error');
        }
        $this->redirect('this');
        
    } catch (\Exception $e) {
        $this->logger->error('Payment processing failed', [
            'payment_id' => $id,
            'error' => $e->getMessage(),
        ]);
        $this->flashMessage('An error occurred. Please try again.', 'error');
        $this->redirect('this');
    }
}
```

### Testing Standards

#### PHPUnit Test Structure
```php
<?php
declare(strict_types=1);

namespace Tests\Unit\Finance;

use App\Finance\Service\PaymentService;
use App\Finance\Entity\Payment;
use App\Finance\ValueObject\Money;
use Tests\TestCase;

final class PaymentServiceTest extends TestCase
{
    private PaymentService $service;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = $this->container->getByType(PaymentService::class);
    }
    
    public function testCreatePaymentWithValidData(): void
    {
        // Arrange
        $amount = new Money(1000, 'CZK');
        $bankAccount = '123456789/0800';
        $dueDate = new DateTime('+7 days');
        
        // Act
        $payment = $this->service->createPayment(
            $amount,
            $bankAccount,
            $dueDate
        );
        
        // Assert
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($amount, $payment->getAmount());
        $this->assertEquals($bankAccount, $payment->getBankAccount());
    }
    
    /**
     * @dataProvider invalidPaymentDataProvider
     */
    public function testCreatePaymentThrowsValidationException(
        Money $amount,
        string $bankAccount,
        string $expectedError
    ): void {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($expectedError);
        
        $this->service->createPayment(
            $amount,
            $bankAccount,
            new DateTime('+7 days')
        );
    }
    
    public function invalidPaymentDataProvider(): array
    {
        return [
            'negative amount' => [
                new Money(-100, 'CZK'),
                '123456789/0800',
                'Amount must be positive',
            ],
            'invalid account' => [
                new Money(1000, 'CZK'),
                'invalid',
                'Invalid bank account format',
            ],
        ];
    }
}
```

#### Nette Tester Example
```php
<?php
declare(strict_types=1);

use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

final class EmailParserTest extends TestCase
{
    private EmailParser $parser;
    
    protected function setUp(): void
    {
        $this->parser = new EmailParser();
    }
    
    public function testParseCsobBankStatement(): void
    {
        $email = file_get_contents(__DIR__ . '/fixtures/csob-statement.eml');
        
        $result = $this->parser->parse($email);
        
        Assert::equal('bank_statement', $result->getType());
        Assert::equal(15000.50, $result->getBalance());
        Assert::contains('123456789/0300', $result->getAccount());
    }
}

(new EmailParserTest())->run();
```

### Security Standards

#### Input Validation
- Always validate and sanitize user input
- Use Nette Forms validation rules
- Use Nettrine entity validation
- Never trust client-side validation alone

#### Authentication & Authorization
```php
<?php
declare(strict_types=1);

namespace App\Core;

use Nette\Security\SimpleIdentity;
use Nette\Security\Authenticator;
use Nette\Security\AuthenticationException;

final class UserAuthenticator implements Authenticator
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasher $hasher,
    ) {}
    
    public function authenticate(string $user, string $password): SimpleIdentity
    {
        $user = $this->userRepository->findByEmail($user);
        
        if (!$user || !$this->hasher->verify($password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }
        
        return new SimpleIdentity(
            $user->id,
            $user->roles,
            ['email' => $user->email]
        );
    }
}
```

### Performance Standards

#### Optimization Priorities
1. **Database Queries**: Use Nettrine query builder efficiently
2. **Caching**: Redis for sessions, Nette Cache for data
3. **Lazy Loading**: Doctrine proxy classes
4. **Tracy Profiling**: Use Tracy bar in development

#### Query Optimization
```php
// Use query builder for complex queries
$qb = $this->paymentRepository->createQueryBuilder('p');
$qb->select('p', 'u')  // Eager load relations
   ->leftJoin('p.user', 'u')
   ->where('p.status = :status')
   ->setParameter('status', Payment::STATUS_PENDING)
   ->setMaxResults(10)
   ->getQuery()
   ->enableResultCache(60);  // Cache for 60 seconds
```

#### Nette Caching
```php
use Nette\Caching\Cache;
use Nette\Caching\Storage;

final class PaymentFacade
{
    private Cache $cache;
    
    public function __construct(
        Storage $storage,
        private readonly PaymentRepository $repository,
    ) {
        $this->cache = new Cache($storage, 'payments');
    }
    
    public function getPayments(): array
    {
        return $this->cache->load('all-payments', function () {
            return $this->repository->findAll();
        });
    }
}
```

### Documentation Standards

#### PHPDoc Comments
```php
/**
 * Calculates the remaining budget for the current period
 * 
 * @param Budget $budget The budget configuration
 * @param Expense[] $expenses Array of expenses in the period
 * @return Money The remaining budget amount
 * @throws ValidationException If budget period is invalid
 */
public function calculateRemainingBudget(
    Budget $budget,
    array $expenses
): Money {
    // Implementation
}
```

#### Nette Annotations/Attributes
```php
use Nette\Application\Attributes\Requires;

/**
 * Payment management presenter
 * @property-read PaymentFacade $paymentFacade
 */
#[Requires(siteMode: 'https')]
final class PaymentPresenter extends BasePresenter
{
    // Presenter code
}
```

#### README Requirements
- Clear setup instructions
- Environment variable documentation
- API endpoint documentation
- Deployment guidelines

### Git Standards

#### Commit Messages
```bash
# Format: type(scope): description

feat(auth): add OAuth2 integration
fix(tasks): resolve sorting issue in task list
docs(api): update API documentation
refactor(facades): simplify PaymentFacade
test(nettrine): add repository tests
chore(composer): update Contributte packages
```

#### Branch Naming
- Feature: `feature/task-management`
- Bugfix: `fix/budget-calculation`
- Hotfix: `hotfix/critical-auth-issue`

### Code Review Checklist
- [ ] PHP 8.4 types are properly defined
- [ ] Nette DI services are registered
- [ ] Facades follow Nette pattern
- [ ] Nettrine entities have proper mappings
- [ ] Error handling is comprehensive
- [ ] Tests are included (PHPUnit/Nette Tester)
- [ ] Security considerations addressed
- [ ] Tracy debugging considered
- [ ] Performance impact assessed
- [ ] Documentation updated
- [ ] PSR-12 compliance verified
- [ ] Contributte packages used where applicable
- [ ] Latte templates are XSS-safe
- [ ] Forms use CSRF protection