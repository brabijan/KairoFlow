# Base stage with all PHP extensions
FROM php:8.4-fpm-alpine AS base

# Install build dependencies and runtime libraries
RUN apk add --no-cache \
    # Build dependencies
    $PHPIZE_DEPS \
    autoconf \
    g++ \
    make \
    # Runtime libraries
    postgresql-dev \
    postgresql-libs \
    icu-dev \
    icu-libs \
    libzip-dev \
    libzip \
    libpng-dev \
    libpng \
    libjpeg-turbo-dev \
    libjpeg-turbo \
    freetype-dev \
    freetype \
    libsodium-dev \
    libsodium \
    fcgi \
    git \
    unzip

# Configure and install PHP extensions (except pcntl which may interfere with PECL)
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    gd \
    opcache \
    bcmath \
    sodium

# Install PECL extensions first (before pcntl)
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install pcntl last as it may interfere with PECL
RUN docker-php-ext-install pcntl

# Clean up build dependencies (keep runtime libraries)
RUN apk del \
    autoconf \
    g++ \
    make \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libsodium-dev \
    icu-dev \
    libzip-dev \
    && rm -rf /tmp/pear

# Create www-data user with UID 1000
RUN adduser -D -u 1000 -g 1000 www-data || true

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www/html

# Builder stage for application
FROM base AS builder

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies with update for PHP 8.4 compatibility
RUN composer update \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-progress \
    --no-interaction \
    --with-all-dependencies

# Copy application code
COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Generate Doctrine proxies
RUN php bin/console orm:generate-proxies || true

# Create required directories
RUN mkdir -p var/tmp/session var/tmp/recordings var/tmp/transcriptions var/log www/uploads temp log \
    && chown -R www-data:www-data var www/uploads temp log \
    && chmod -R 755 var www/uploads \
    && chmod -R 777 temp log

# Production stage
FROM base AS production

# Copy application from builder
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD FCGI_CONNECT=/var/run/php-fpm.sock fcgi-client || exit 1

# Switch to www-data user
USER www-data

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm", "-F"]

# Development stage
FROM base AS development

# Re-install build tools for development
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    linux-headers

# Xdebug installation skipped due to PECL compatibility issues
# TODO: Add Xdebug installation once PECL issues are resolved

# Set development PHP configuration
RUN { \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'display_errors=On'; \
        echo 'display_startup_errors=On'; \
        echo 'error_reporting=E_ALL'; \
    } > /usr/local/etc/php/conf.d/development.ini

# Install Composer for development
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .

# Install dependencies with dev dependencies and update for PHP 8.4 compatibility
RUN composer update \
    --optimize-autoloader \
    --no-interaction \
    --no-progress \
    --with-all-dependencies

# Create required directories
RUN mkdir -p var/tmp/session var/tmp/recordings var/tmp/transcriptions var/log www/uploads temp log \
    && chmod -R 777 var www/uploads temp log

# For development, we don't switch to www-data to allow file editing
EXPOSE 9000

CMD ["php-fpm", "-F"]