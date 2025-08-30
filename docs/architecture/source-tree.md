# KairoFlow Source Tree Structure

## Project Root Structure

```
KairoFlow/
├── app/                        # Application core
│   ├── Bootstrap.php          # Application bootstrap
│   ├── Model/                 # Business logic (Nette standard)
│   ├── Module/                # Presenters and components
│   ├── Core/                  # Core classes and services
│   └── Entity/                # Doctrine entities
├── bin/                       # CLI scripts
│   └── console               # Contributte Console entry point
├── config/                    # Nette configuration
│   ├── common.neon           # Common configuration
│   ├── local.neon            # Local overrides (git-ignored)
│   ├── services.neon         # DI container services
│   └── extensions.neon       # Nettrine & Contributte extensions
├── migrations/                # Nettrine migrations
│   └── Version*.php          # Migration files
├── fixtures/                  # Nettrine fixtures
│   └── DataFixtures/         # Fixture classes
├── docker/                    # Docker configuration
│   ├── nginx/               # Nginx configs
│   ├── php/                 # PHP-FPM configs
│   └── supervisor/          # Process manager configs
├── docs/                     # Documentation (BMAD)
│   ├── architecture/        # Architecture docs
│   ├── prd/                # Sharded PRD files
│   ├── stories/            # User stories
│   ├── epics/              # Epic definitions
│   └── qa/                 # QA results
├── log/                     # Application logs (git-ignored)
├── public/                  # Web root
│   ├── index.php           # Entry point
│   ├── css/                # Compiled CSS
│   ├── js/                 # JavaScript files
│   ├── images/             # Static images
│   └── uploads/            # User uploads (git-ignored)
├── temp/                    # Temporary files (git-ignored)
│   ├── cache/              # Application cache
│   ├── sessions/           # File-based sessions
│   └── proxies/            # Doctrine proxies
├── tests/                   # Test suites
│   ├── Unit/               # Unit tests
│   ├── Integration/        # Integration tests
│   ├── Fixtures/           # Test fixtures
│   └── bootstrap.php       # Test bootstrap
├── vendor/                  # Composer dependencies (git-ignored)
├── .bmad-core/             # BMAD method files
├── .docker/                # Docker volumes (git-ignored)
├── .env.example            # Environment variables example
├── .gitignore             # Git ignore rules
├── composer.json          # PHP dependencies
├── composer.lock          # Locked dependencies
├── docker-compose.yml     # Local development setup
├── Dockerfile             # Production container
├── Makefile              # Common commands
├── package.json          # Node dependencies (for assets)
├── phpstan.neon          # PHPStan configuration
├── phpunit.xml           # PHPUnit configuration
├── postcss.config.js     # PostCSS for Tailwind
├── tailwind.config.js    # Tailwind configuration
├── psalm.xml              # Psalm configuration
├── ecs.php                # Easy Coding Standard config
└── README.md             # Project documentation
```

## Detailed Application Structure

### app/ Directory (Application Core)

```
app/
├── Bootstrap.php              # Application initialization
├── Model/                     # Business logic (Nette standard)
│   ├── Finance/              # Finance domain
│   │   ├── PaymentFacade.php      # Main entry point (Nette pattern)
│   │   ├── PaymentRepository.php  # Nettrine repository
│   │   ├── QrCodeService.php
│   │   └── DuplicateDetector.php
│   ├── Work/                 # Work domain
│   │   ├── TaskFacade.php
│   │   ├── TaskRepository.php
│   │   ├── GitLabService.php
│   │   └── SlackService.php
│   ├── Email/                # Email domain
│   │   ├── EmailFacade.php
│   │   ├── EmailRepository.php
│   │   ├── Parser/
│   │   │   ├── ParserInterface.php
│   │   │   ├── CsobParser.php
│   │   │   └── KbParser.php
│   │   └── ClassificationEngine.php
│   └── Notification/         # Notification domain
│       ├── NotificationFacade.php
│       ├── AdhdBypassEngine.php
│       └── StreakTracker.php
├── Entity/                    # Doctrine entities (Nettrine)
│   ├── Payment.php
│   ├── Transaction.php
│   ├── Account.php
│   ├── Task.php
│   ├── Email.php
│   ├── Attachment.php
│   └── Notification.php
├── ValueObject/              # Value objects
│   ├── Money.php
│   ├── BankAccount.php
│   ├── VariableSymbol.php
│   ├── EmailAddress.php
│   └── TaskPriority.php
├── Event/                     # Domain events
│   ├── PaymentCreatedEvent.php
│   ├── PaymentCompletedEvent.php
│   ├── TaskCreatedEvent.php
│   └── EmailProcessedEvent.php
├── Core/                      # Core classes
│   ├── BasePresenter.php
│   ├── BaseRepository.php    # Extends Nettrine EntityRepository
│   ├── BaseFacade.php
│   ├── EventDispatcher.php
│   └── Exceptions/
│       ├── DomainException.php
│       ├── ValidationException.php
│       └── InfrastructureException.php
├── External/                  # External API integrations
│   ├── GitLab/
│   │   ├── GitLabClient.php
│   │   └── GitLabAdapter.php
│   ├── Slack/
│   │   ├── SlackClient.php
│   │   └── SlackAdapter.php
│   ├── Clockify/
│   │   ├── ClockifyClient.php
│   │   └── ClockifyAdapter.php
│   └── CircuitBreaker/
│       └── CircuitBreaker.php
├── Module/                    # Presenters and UI (Nette standard)
│   ├── Admin/                # Admin module
│   │   ├── BaseAdminPresenter.php
│   │   └── DashboardPresenter.php
│   ├── Api/                  # API module
│   │   ├── BaseApiPresenter.php
│   │   ├── V1/
│   │   │   ├── PaymentPresenter.php
│   │   │   └── TaskPresenter.php
│   │   └── WebhookPresenter.php
│   ├── Front/                # Front module
│   │   ├── BaseFrontPresenter.php
│   │   ├── Error/
│   │   │   ├── Error4xxPresenter.php
│   │   │   └── Error5xxPresenter.php
│   │   ├── HomepagePresenter.php
│   │   ├── PaymentPresenter.php
│   │   ├── TaskPresenter.php
│   │   ├── EmailPresenter.php
│   │   ├── SignPresenter.php
│   │   └── templates/
│   │       ├── @layout.latte
│   │       ├── Homepage/
│   │       │   └── default.latte
│   │       ├── Payment/
│   │       │   ├── default.latte
│   │       │   └── detail.latte
│   │       ├── Task/
│   │       │   ├── default.latte
│   │       │   └── sync.latte
│   │       └── Sign/
│   │           ├── in.latte
│   │           └── out.latte
│   └── Component/            # Reusable components
│       ├── PaymentGrid/
│       │   ├── PaymentGridFactory.php
│       │   ├── PaymentGrid.php
│       │   └── PaymentGrid.latte
│       ├── TaskList/
│       │   ├── TaskListFactory.php
│       │   ├── TaskList.php
│       │   └── TaskList.latte
│       └── Forms/
│           ├── SignInFormFactory.php
│           └── PaymentFormFactory.php
└── Command/                  # Console commands (Contributte)
    ├── EmailProcessCommand.php
    ├── PaymentCheckCommand.php
    ├── TaskSyncCommand.php
    └── MigrationCommand.php
```

### Configuration Files

```
config/
├── common.neon              # Common configuration
│   # - parameters
│   # - application
│   # - security
│   # - forms
│   # - latte
├── extensions.neon          # Contributte & Nettrine extensions
│   # - nettrine.annotations
│   # - nettrine.dbal
│   # - nettrine.orm
│   # - nettrine.migrations
│   # - nettrine.fixtures
│   # - contributte.console
│   # - contributte.monolog
│   # - contributte.middlewares
├── services.neon           # Service definitions
│   # - facades
│   # - repositories
│   # - external services
│   # - component factories
└── local.neon              # Local overrides (git-ignored)
    # - debug mode
    # - local database
    # - development services
```

### Database Structure

```
migrations/                  # Nettrine migrations
├── Version20240101000001.php  # Initial schema
├── Version20240102000001.php  # Add payments table
└── Version20240103000001.php  # Add indexes

fixtures/
└── DataFixtures/           # Nettrine fixtures
    ├── UserFixtures.php
    ├── PaymentFixtures.php
    └── TaskFixtures.php
```

### Test Structure

```
tests/
├── Unit/                  # Unit tests
│   ├── Domain/
│   │   ├── Finance/
│   │   │   ├── PaymentServiceTest.php
│   │   │   └── QrCodeGeneratorTest.php
│   │   └── Email/
│   │       ├── CsobParserTest.php
│   │       └── ClassificationEngineTest.php
│   └── Infrastructure/
│       └── CircuitBreakerTest.php
├── Integration/          # Integration tests
│   ├── EmailProcessingTest.php
│   ├── PaymentFlowTest.php
│   └── GitLabSyncTest.php
├── Fixtures/            # Test data
│   ├── emails/
│   │   ├── csob-statement.eml
│   │   └── invoice.eml
│   └── data/
│       └── test-payments.json
└── bootstrap.php        # Test initialization
```

### Public Assets

```
public/
├── index.php           # Application entry point
├── css/
│   ├── app.css        # Compiled Tailwind CSS
│   └── app.css.map    # Source map
├── js/
│   ├── app.js         # Main JavaScript
│   ├── alpine.js      # Alpine.js library
│   └── components/    # JS components
│       ├── qr-scanner.js
│       └── task-filter.js
├── images/
│   ├── logo.svg
│   └── icons/         # UI icons
└── uploads/           # User uploads (git-ignored)
    ├── qr-codes/      # Generated QR codes
    └── attachments/   # Email attachments
```

### Docker Configuration

```
docker/
├── nginx/
│   ├── nginx.conf     # Main Nginx config
│   └── site.conf      # Site configuration
├── php/
│   ├── php.ini        # PHP configuration
│   ├── php-fpm.conf   # PHP-FPM config
│   └── opcache.ini    # OPcache settings
└── supervisor/
    ├── supervisord.conf
    └── workers/
        ├── email-processor.conf
        └── queue-worker.conf
```

### CLI Commands

```
bin/
└── console             # Contributte Console
    # Nettrine commands:
    # - orm:schema:create
    # - orm:schema:update
    # - migrations:migrate
    # - migrations:diff
    # - fixtures:load
    # 
    # Application commands:
    # - app:email:process
    # - app:payment:check
    # - app:task:sync
    # - app:cache:clear
```

## File Naming Conventions

### PHP Files
- **Classes**: PascalCase matching class name
- **Interfaces**: PascalCase with "Interface" suffix
- **Traits**: PascalCase with "Trait" suffix
- **Config**: lowercase with hyphens

### Templates
- **Latte**: camelCase.latte
- **Layouts**: @layout.latte
- **Components**: PascalCase.latte

### Assets
- **CSS/JS**: lowercase with hyphens
- **Images**: lowercase with hyphens

## Module Organization (Nette Standard)

Each domain in Model/ follows this structure:
- **Facade**: Main entry point (Nette pattern)
- **Repository**: Nettrine EntityRepository
- **Service**: Specific business logic
- **Entity**: Doctrine entities in app/Entity/
- **ValueObject**: Immutable types in app/ValueObject/

## Key Directories Explained

### Model Layer (`app/Model/`)
Contains business logic organized by domains following Nette conventions. Each domain has a Facade as the main entry point.

### Entity Layer (`app/Entity/`)
Doctrine entities managed by Nettrine ORM. Separated from Model for cleaner architecture.

### Module Layer (`app/Module/`)
Presentation layer with presenters organized by modules (Admin, Api, Front). Templates are co-located with presenters.

### Configuration (`config/`)
Nette configuration files using NEON format. Supports environment-specific overrides.

### Public Root (`public/`)
Web-accessible files. Only index.php and static assets should be here.

### Temporary Files (`temp/`)
Cache, sessions, and compiled templates. Must be writable by web server.

## Security Considerations

### Git-Ignored Directories
- `/vendor/` - Dependencies
- `/temp/` - Temporary files
- `/log/` - Application logs
- `/public/uploads/` - User uploads
- `/.docker/` - Docker volumes
- `/config/local.neon` - Local configuration

### Permissions
```bash
# Production permissions
chmod 755 app bin config database docker docs tests
chmod 777 temp log
chmod 755 public
chmod 777 public/uploads
```

## Development Workflow

### Adding New Features (Nette/Nettrine)
1. Create Doctrine entity in `app/Entity/`
2. Generate migration with `bin/console migrations:diff`
3. Create repository extending EntityRepository in `app/Model/{Domain}/`
4. Create facade in `app/Model/{Domain}/`
5. Register facade and repository in `config/services.neon`
6. Add presenter in `app/Module/{Module}/`
7. Create templates in module's `templates/` folder
8. Write tests in `tests/`

### Service Registration Example
```neon
services:
    - App\Model\Finance\PaymentFacade
    - App\Model\Finance\PaymentRepository
    - App\Module\Component\PaymentGrid\PaymentGridFactory
```

This source tree structure provides clear separation of concerns, follows DDD principles, and maintains the modular monolith architecture defined in the main architecture document.