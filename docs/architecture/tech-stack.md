# KairoFlow Technology Stack

## Core Technologies

### Backend Framework
**PHP 8.4+ with Nette Framework**
- **Version**: PHP 8.4+ with Nette 3.2+
- **Why PHP**: Mature ecosystem, excellent for rapid development, strong typing with PHP 8+
- **Why Nette**: Czech framework with excellent DI container, security by default, powerful debugging tools
- **Key Features Used**:
  - Dependency Injection Container
  - Tracy Debugger for development
  - Latte templating engine
  - Security features (CSRF, XSS protection)
  - Forms with built-in validation

### Database
**PostgreSQL 14+**
- **Primary Database**: Relational data storage
- **Why PostgreSQL**: 
  - JSONB support for flexible schema
  - Full-text search capabilities
  - Advanced indexing options
  - Window functions for analytics
  - Reliable ACID compliance
- **Key Features Used**:
  - JSONB for email metadata
  - Full-text search for task content
  - Partial indexes for performance
  - Table partitioning for emails by date

### ORM & Database Access
**Nettrine (Doctrine ORM 3.x for Nette)**
- **Package**: `contributte/doctrine-orm` (Nettrine)
- **Why Nettrine**: 
  - Official Contributte integration for Doctrine
  - Perfect Nette DI container integration
  - Automatic entity discovery
  - Built-in Tracy panel
  - Console commands support
- **Related packages**:
  - `contributte/doctrine-dbal` - DBAL integration
  - `contributte/doctrine-annotations` - Annotations support
  - `contributte/doctrine-migrations` - Migration support
  - `contributte/doctrine-fixtures` - Data fixtures
  - `contributte/doctrine-cache` - Cache integration
- **Migration Tool**: Nettrine Migrations

### Caching & Session Storage
**Redis 7+**
- **Use Cases**:
  - Session storage
  - Application cache
  - Job queue for background tasks
  - Rate limiting
  - Real-time notification pub/sub
- **Configuration**:
  - Persistent storage enabled
  - AOF persistence for durability
  - Separate databases for different concerns

### Frontend Technologies

#### Templating
**Latte 3.x**
- **Why Latte**: 
  - Context-aware escaping
  - Compile-time checking
  - Excellent IDE support
  - Clean syntax
- **Features**:
  - Auto-escaping for security
  - Macros for common patterns
  - Layout inheritance
  - Filters for data transformation

#### JavaScript Framework
**Alpine.js 3.x**
- **Why Alpine**: 
  - Minimal overhead (15kb)
  - Perfect for progressive enhancement
  - Works great with server-rendered HTML
  - Simple learning curve
- **Use Cases**:
  - Interactive forms
  - Dynamic UI components
  - AJAX interactions
  - Real-time updates

#### CSS Framework
**Tailwind CSS 3.x**
- **Why Tailwind**:
  - Utility-first approach
  - Small production builds
  - Consistent design system
  - Easy responsive design
- **Configuration**:
  - JIT mode enabled
  - Custom color palette for ADHD-friendly UI
  - Component extraction for common patterns

### Email Processing

#### IMAP/SMTP Libraries
**PHPMailer + PHP-IMAP**
- **PHPMailer**: Sending emails with attachment support
- **PHP-IMAP**: Reading and parsing email from IMAP servers
- **Features**:
  - OAuth2 authentication support
  - Attachment handling
  - HTML/Text parsing
  - Header analysis

### External API Clients

#### HTTP Client
**Guzzle 7.x**
- **Why Guzzle**:
  - PSR-7 compliant
  - Middleware system
  - Async requests support
  - Built-in retry logic
- **Use Cases**:
  - GitLab API integration
  - Slack API integration  
  - Clockify API integration
  - Webhook handling

### Security

#### Authentication
**Nette Security + TOTP**
- **Nette Security**: Built-in authentication/authorization
- **TOTP Library**: spomky-labs/otphp for 2FA
- **Features**:
  - Password hashing with bcrypt
  - Role-based access control
  - Session management
  - TOTP two-factor authentication

#### Encryption
**Sodium (libsodium)**
- **Use Cases**:
  - Encrypting sensitive data at rest
  - API token encryption
  - Email credential storage
- **Why Sodium**: Modern, secure, built into PHP

### File Processing

#### QR Code Generation
**Endroid/QR-Code 5.x**
- **Features**:
  - Multiple format support (PNG, SVG)
  - Custom styling options
  - Logo embedding
  - High error correction

#### PDF Generation
**TCPDF or mPDF**
- **Use Cases**:
  - Invoice generation
  - Report exports
  - Payment summaries
- **Features**:
  - HTML to PDF conversion
  - Unicode support
  - Custom fonts

### Development Tools

#### Code Quality
**PHPStan + Easy Coding Standard (ECS)**
- **PHPStan**: Static analysis (level 8)
  - With `phpstan/phpstan-nette` extension
  - With `phpstan/phpstan-doctrine` extension
- **ECS**: Code style enforcement (Contributte standard)
  - Package: `contributte/qa`
  - Combines PHP CS Fixer + PHP_CodeSniffer
- **Psalm**: Additional type checking
  - With doctrine plugin
- **Rector**: Automated refactoring
  - Package: `rector/rector`

#### Testing
**PHPUnit 10.x + Nette Tester**
- **PHPUnit**: Unit and integration tests
- **Nette Tester**: Simpler test cases
- **Mockery**: Mocking framework
- **Faker**: Test data generation

#### Debugging
**Tracy Debugger**
- **Features**:
  - Error screen with stack trace
  - Debugger bar
  - Logging capabilities
  - AJAX request debugging
- **Configuration**:
  - Enabled in development
  - Email notifications in production

### Container & Orchestration

#### Containerization
**Docker + Docker Compose**
- **Base Image**: php:8.4-fpm-alpine
- **Multi-stage builds** for smaller images
- **Services**:
  - PHP-FPM application
  - Nginx web server
  - PostgreSQL database
  - Redis cache
  - MailHog for dev email testing

#### Orchestration
**Kubernetes 1.28+ with Helm 3.x**
- **Rancher 2**: Existing K8s management
- **Helm Charts**: Declarative deployment
- **Features**:
  - Rolling updates
  - Health checks
  - Secret management
  - Persistent volumes

#### Web Server
**Nginx 1.24+**
- **Configuration**:
  - FastCGI for PHP-FPM
  - Static file serving
  - Gzip compression
  - Security headers
  - Rate limiting

### Monitoring & Logging

#### Application Monitoring
**Prometheus + Grafana**
- **Metrics Collection**: Custom PHP metrics
- **Dashboards**: Real-time monitoring
- **Alerts**: Prometheus AlertManager

#### Logging
**Contributte Monolog**
- **Package**: `contributte/monolog`
- **Why**: Full Monolog integration with Nette
- **Handlers**:
  - File logging (rotating)
  - Syslog for production
  - Slack notifications for errors
  - Tracy integration
- **Structured Logging**: JSON format
- **Features**:
  - Automatic Tracy bridge
  - Request ID tracking
  - Context processors

#### Error Tracking
**Sentry (optional)**
- **Features**:
  - Real-time error alerts
  - Performance monitoring
  - Release tracking
- **Alternative**: Tracy logger with email notifications

### Background Jobs

#### Console Commands
**Contributte Console**
- **Package**: `contributte/console`
- **Why**: Symfony Console integration for Nette
- **Features**:
  - All Doctrine/Nettrine commands included
  - Custom command creation
  - Cron integration
  - Helper sets

#### Queue System
**Redis + Contributte Scheduler (optional)**
- **Package**: `contributte/scheduler` (if needed)
- **Features**:
  - Cron expression support
  - Job chaining
  - Parallel execution
- **Alternative**: Simple Redis queue with console commands

#### Cron Jobs
**Kubernetes CronJob + Contributte Console**
- **Tasks**:
  - `bin/console app:email:process` (every minute)
  - `bin/console app:invoice:generate` (weekly)
  - `bin/console app:cleanup` (daily)
  - `bin/console orm:schema:validate` (daily check)

### Contributte Extensions

#### API Development
**Contributte API**
- **Package**: `contributte/api`
- **Features**:
  - RESTful routing
  - Request/Response handling
  - OpenAPI documentation
  - Content negotiation

#### PSR Standards
**Contributte PSR**
- **Packages**:
  - `contributte/psr7-http-message` - PSR-7
  - `contributte/middlewares` - PSR-15
  - `contributte/event-dispatcher` - PSR-14
  - `contributte/cache` - PSR-6/PSR-16

#### Additional Contributte
- **contributte/forms-multiplier** - Dynamic forms
- **contributte/mail** - Enhanced mailing
- **contributte/tracy** - Tracy extensions
- **contributte/utils** - Utility functions
- **contributte/di** - DI helpers

### External Services

#### Version Control
**GitLab**
- **API Integration**: Issues, commits, MRs
- **Webhooks**: Real-time updates
- **CI/CD**: GitLab CI for deployment

#### Communication
**Slack**
- **API Features**:
  - Message monitoring
  - Task extraction
  - Notifications
- **Bot User**: For sending messages

#### Time Tracking
**Clockify**
- **API Features**:
  - Time entry retrieval
  - Project mapping
  - Invoice data generation

### Development Environment

#### Local Development
**Docker Compose**
```yaml
services:
  app:
    image: kairoflow-dev
    volumes:
      - .:/app
    environment:
      - NETTE_DEBUG=1
      - NETTE_ENV=development
  
  postgres:
    image: postgres:14-alpine
    environment:
      - POSTGRES_DB=kairoflow
      - POSTGRES_USER=kairoflow
      - POSTGRES_PASSWORD=kairoflow
    
  redis:
    image: redis:7-alpine
    
  mailhog:
    image: mailhog/mailhog
```

#### Nette Configuration for Dev
```neon
# config/local.neon
parameters:
    debugMode: true
    consoleMode: %consoleMode%

tracy:
    email: dev@example.com
    showLocation: true

nettrine.orm:
    configuration:
        autoGenerateProxyClasses: true
```

#### IDE Support
**PhpStorm or VS Code**
- **PhpStorm**: Full Nette support
- **VS Code Extensions**:
  - PHP Intelephense
  - Nette Latte
  - Alpine.js IntelliSense
  - Tailwind CSS IntelliSense

### Architecture Decisions

#### Monolithic vs Microservices
**Decision**: Start with modular monolith
- **Rationale**:
  - Faster initial development
  - Easier deployment
  - Lower operational overhead
  - Clear module boundaries for future extraction

#### Server-Side vs Client-Side Rendering
**Decision**: Server-side with progressive enhancement
- **Rationale**:
  - Better SEO (if needed)
  - Faster initial load
  - Works without JavaScript
  - Simpler state management

#### SQL vs NoSQL
**Decision**: PostgreSQL with JSONB where needed
- **Rationale**:
  - ACID compliance critical for financial data
  - Relational model fits domain
  - JSONB provides flexibility
  - Single database reduces complexity

### Performance Targets

| Metric | Target | Measurement |
|--------|--------|-------------|
| Page Load | < 2 seconds | 95th percentile |
| API Response | < 200ms | Average |
| Email Processing | < 30 seconds | Per email |
| QR Generation | < 100ms | Per code |
| Database Query | < 50ms | 95th percentile |

### Scalability Considerations

#### Current Limits
- **Users**: Designed for 1-100 users
- **Emails**: 1000/day processing capacity  
- **Payments**: 10,000/month
- **Storage**: 10GB database, 5GB files

#### Growth Path
1. **Phase 1**: Single server (current)
2. **Phase 2**: Horizontal scaling with K8s
3. **Phase 3**: Database read replicas
4. **Phase 4**: Service extraction if needed

### Security Standards

#### Compliance
- **GDPR**: Data privacy for EU users
- **PCI DSS**: Not required (no card processing)
- **OWASP**: Top 10 protection

#### Security Measures
- HTTPS everywhere
- CSRF protection
- XSS prevention via Latte
- SQL injection prevention via Doctrine
- Rate limiting
- Input validation
- Encrypted sensitive data

### Backup & Recovery

#### Backup Strategy
- **Database**: Daily full, 6-hour incremental
- **Files**: Weekly full, daily incremental
- **Redis**: Hourly snapshots
- **Retention**: 30 days

#### Disaster Recovery
- **RPO**: 6 hours
- **RTO**: 2 hours
- **Strategy**: Restore from backup + event replay

### Cost Optimization

#### Infrastructure Costs (Estimated Monthly)
- **Kubernetes Cluster**: $50-100
- **Database Storage**: $10-20
- **File Storage**: $5-10
- **Monitoring**: $10-20
- **Total**: ~$75-150/month

#### Cost Saving Measures
- No auto-scaling (manual control)
- Single region deployment
- Compressed backups
- Efficient caching strategy

### Composer Packages Summary

```json
{
  "require": {
    "php": "^8.4",
    "nette/application": "^3.2",
    "nette/bootstrap": "^3.2",
    "nette/caching": "^3.3",
    "nette/database": "^3.2",
    "nette/di": "^3.2",
    "nette/forms": "^3.2",
    "nette/http": "^3.3",
    "nette/mail": "^4.0",
    "nette/robot-loader": "^4.0",
    "nette/security": "^3.2",
    "nette/utils": "^4.0",
    "latte/latte": "^3.0",
    "tracy/tracy": "^2.10",
    "contributte/console": "^0.10",
    "contributte/doctrine-orm": "^0.9",
    "contributte/doctrine-dbal": "^0.9",
    "contributte/doctrine-migrations": "^0.5",
    "contributte/doctrine-fixtures": "^0.6",
    "contributte/monolog": "^0.6",
    "contributte/middlewares": "^0.10",
    "contributte/psr7-http-message": "^0.9",
    "contributte/event-dispatcher": "^0.9",
    "contributte/di": "^0.5",
    "contributte/utils": "^0.6",
    "contributte/cache": "^0.7",
    "guzzlehttp/guzzle": "^7.5",
    "endroid/qr-code": "^5.0",
    "spomky-labs/otphp": "^11.2"
  },
  "require-dev": {
    "phpstan/phpstan": "^1.10",
    "phpstan/phpstan-nette": "^1.0",
    "phpstan/phpstan-doctrine": "^1.3",
    "phpunit/phpunit": "^10.5",
    "mockery/mockery": "^1.6",
    "nette/tester": "^2.5",
    "contributte/qa": "^0.4",
    "rector/rector": "^1.0",
    "psalm/plugin-doctrine": "^2.9"
  }
}
```

### Migration Path

#### From Prototype to Production
1. Switch `debugMode` to false in config
2. Configure Tracy for production
3. Run `bin/console orm:schema:validate`
4. Enable OPcache
5. Configure proper logging with Monolog
6. Set up monitoring
7. Security audit with `composer audit`

#### Technology Upgrades
- **PHP**: Follow stable releases (8.4 → 8.5)
- **Nette**: Stay on latest stable
- **Contributte**: Follow compatibility matrix
- **Nettrine**: Sync with Doctrine releases
- **PostgreSQL**: LTS versions only
- **Redis**: Stable releases

### Documentation & Resources

#### Official Documentation
- [Nette Framework](https://nette.org/en/)
- [Contributte Packages](https://contributte.org/)
- [Nettrine Documentation](https://contributte.org/packages/contributte/doctrine-orm.html)
- [Doctrine ORM](https://www.doctrine-project.org/)
- [Alpine.js](https://alpinejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/)

#### Key Contributte Resources
- [Contributte Examples](https://github.com/contributte/examples)
- [Nettrine Demo](https://github.com/contributte/doctrine-orm)
- [Console Integration](https://contributte.org/packages/contributte/console.html)
- [Middleware Documentation](https://contributte.org/packages/contributte/middlewares.html)

#### Project-Specific Docs
- API Documentation: OpenAPI/Swagger
- Database Schema: auto-generated ERD
- Deployment Guide: Kubernetes manifests
- Development Setup: Docker Compose

This technology stack provides a solid, production-ready foundation for KairoFlow while maintaining simplicity and developer productivity. The choices prioritize stability, security, and the specific needs of ADHD users requiring reliable, fast, and friction-free interactions.