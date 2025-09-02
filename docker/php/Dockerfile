# Build stage
FROM php:8.4-fpm-alpine AS builder

# Install build dependencies
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libsodium-dev \
    git \
    unzip

# Configure and install PHP extensions
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
    sodium \
    pcntl

# Install Redis extension via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-progress \
    --no-interaction

# Copy application code
COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Generate Doctrine proxies
RUN php bin/console orm:generate-proxies || true

# Production stage
FROM php:8.4-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    postgresql-libs \
    icu-libs \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    libsodium \
    fcgi

# Install PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Create www-data user with UID 1000
RUN adduser -D -u 1000 -g 1000 www-data || true

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www/html

# Copy application from builder
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Create required directories
RUN mkdir -p var/tmp/session var/tmp/recordings var/tmp/transcriptions var/log www/uploads \
    && chown -R www-data:www-data var www/uploads temp log \
    && chmod -R 755 var www/uploads \
    && chmod -R 777 temp log

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD FCGI_CONNECT=/var/run/php-fpm.sock fcgi-client || exit 1

# Switch to www-data user
USER www-data

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm", "-F"]

# Development stage (optional - can be used with --target=development)
FROM builder AS development

# Install linux headers for Xdebug
RUN apk add --no-cache linux-headers

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug
RUN { \
        echo 'xdebug.mode=debug'; \
        echo 'xdebug.client_host=host.docker.internal'; \
        echo 'xdebug.start_with_request=yes'; \
    } > /usr/local/etc/php/conf.d/xdebug.ini

# Set development PHP configuration
RUN { \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'display_errors=On'; \
        echo 'display_startup_errors=On'; \
        echo 'error_reporting=E_ALL'; \
    } > /usr/local/etc/php/conf.d/development.ini

WORKDIR /var/www/html

# For development, we don't switch to www-data to allow file editing
EXPOSE 9000

CMD ["php-fpm", "-F"]