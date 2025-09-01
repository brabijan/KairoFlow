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
    bcmath

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
    && chown -R www-data:www-data var www/uploads \
    && chmod -R 755 var www/uploads

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD FCGI_CONNECT=/var/run/php-fpm.sock fcgi-client || exit 1

# Switch to www-data user
USER www-data

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm", "-F"]