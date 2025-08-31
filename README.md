# KairoFlow

ADHD-optimized financial and task management system built with PHP 8.4, Nette Framework, PostgreSQL, and Redis.

## Requirements

- PHP 8.4+
- Docker and Docker Compose
- Composer 2.x

## Quick Start

### Using Docker (Recommended)

1. Clone the repository:
```bash
git clone https://github.com/yourusername/kairoflow.git
cd kairoflow
```

2. Copy the local configuration:
```bash
cp config/local.neon.example config/local.neon
```

3. Start the Docker containers:
```bash
docker-compose up -d
```

4. Install dependencies:
```bash
docker-compose exec app composer install
```

5. Run database migrations:
```bash
docker-compose exec app bin/console migrations:migrate
```

6. Access the application:
- Application: http://localhost:8080
- MailHog (email testing): http://localhost:8025
- Health check: http://localhost:8080/health

### Local Development (Without Docker)

1. Install PHP 8.4 with required extensions:
   - pdo_pgsql
   - redis
   - opcache
   - mbstring
   - sodium
   - intl
   - zip
   - gd

2. Install PostgreSQL 14+ and Redis 7+

3. Copy and configure local settings:
```bash
cp config/local.neon.example config/local.neon
# Edit config/local.neon with your database and Redis credentials
```

4. Install dependencies:
```bash
composer install
```

5. Run migrations:
```bash
bin/console migrations:migrate
```

6. Start the development server:
```bash
php -S localhost:8000 -t public
```

## Environment Variables

The application uses these environment variables (set in docker-compose.yml or your environment):

| Variable | Description | Default |
|----------|-------------|---------|
| `NETTE_DEBUG` | Enable debug mode (1 or 0) | 0 |
| `NETTE_ENV` | Environment (development, production, test) | production |
| `DATABASE_DSN` | PostgreSQL connection string | pgsql:host=localhost;dbname=kairoflow |
| `DATABASE_USER` | Database username | kairoflow |
| `DATABASE_PASSWORD` | Database password | - |
| `REDIS_HOST` | Redis server host | localhost |
| `REDIS_PORT` | Redis server port | 6379 |
| `REDIS_PASSWORD` | Redis password (if auth enabled) | - |

## Project Structure

```
KairoFlow/
├── app/                    # Application code
│   ├── Bootstrap.php       # Application bootstrap
│   ├── Model/              # Business logic (facades, repositories, services)
│   ├── Module/             # Presenters and UI components
│   ├── Entity/             # Doctrine entities
│   ├── Core/               # Core classes
│   └── Command/            # Console commands
├── config/                 # Configuration files
│   ├── common.neon         # Common configuration
│   ├── extensions.neon     # Nettrine & Contributte extensions
│   ├── services.neon       # Service definitions
│   └── local.neon          # Local overrides (git-ignored)
├── migrations/             # Database migrations
├── public/                 # Web root
├── tests/                  # Test suites
├── docker/                 # Docker configuration
└── docs/                   # Documentation
```

## Console Commands

Run commands using the console script:

```bash
# In Docker
docker-compose exec app bin/console <command>

# Local
bin/console <command>
```

Available commands:
- `migrations:migrate` - Run database migrations
- `migrations:diff` - Generate migration from entity changes
- `orm:schema:validate` - Validate database schema
- `app:email:process` - Process incoming emails
- `app:task:sync` - Synchronize tasks with external services

## Testing

Run the test suite:

```bash
# Unit tests
composer test

# With coverage
composer test -- --coverage-html tests/coverage

# PHPStan static analysis
composer phpstan

# Code style check
composer cs

# Fix code style
composer cs-fix
```

## Development Tools

### Tracy Debugger

Tracy is enabled in development mode. Access the debug bar at the bottom of each page for:
- SQL queries
- Performance profiling
- Error logging
- Memory usage
- Request details

### Database Management

Create a new migration:
```bash
docker-compose exec app bin/console migrations:diff
```

Execute migrations:
```bash
docker-compose exec app bin/console migrations:migrate
```

### Email Testing

MailHog captures all emails sent in development. Access the web UI at http://localhost:8025

## API Endpoints

### Health Check

`GET /health`

Returns system health status:
```json
{
  "status": "healthy",
  "timestamp": 1693526400,
  "checks": {
    "database": {
      "status": "healthy",
      "message": "Database connection successful"
    },
    "redis": {
      "status": "healthy",
      "message": "Redis connection successful"
    },
    "disk_space": {
      "status": "healthy",
      "message": "Disk space OK: 45% used",
      "percent_used": 45
    },
    "php": {
      "status": "healthy",
      "version": "8.4.0",
      "required": "^8.4"
    }
  }
}
```

## Troubleshooting

### Container won't start

Check logs:
```bash
docker-compose logs app
```

### Database connection errors

1. Verify PostgreSQL is running:
```bash
docker-compose ps postgres
```

2. Check connection settings in `config/local.neon`

3. Test connection:
```bash
docker-compose exec postgres psql -U kairoflow -d kairoflow
```

### Redis connection errors

1. Verify Redis is running:
```bash
docker-compose ps redis
```

2. Test connection:
```bash
docker-compose exec redis redis-cli ping
```

### Permission errors

Fix permissions for temp and log directories:
```bash
chmod -R 777 temp/ log/
```

### Composer memory limit

If composer runs out of memory:
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

## Production Deployment

1. Build production image:
```bash
docker build -t kairoflow:latest --target production .
```

2. Set environment variables for production

3. Run migrations:
```bash
docker exec kairoflow bin/console migrations:migrate --no-interaction
```

4. Clear caches:
```bash
docker exec kairoflow bin/console cache:clear
```

## Contributing

1. Create a feature branch from `main`
2. Follow PSR-12 coding standards
3. Write tests for new features
4. Ensure all tests pass
5. Submit a pull request

## License

Proprietary - All rights reserved

## Support

For issues and questions, please use the GitHub issue tracker.