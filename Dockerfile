# Multi-stage build for production
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libsodium-dev \
    autoconf \
    g++ \
    make \
    git \
    bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        opcache \
        intl \
        zip \
        gd \
        sodium \
        pcntl \
        bcmath

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure OPcache for production
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=256'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.revalidate_freq=60'; \
        echo 'opcache.validate_timestamps=0'; \
        echo 'opcache.save_comments=1'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP
RUN { \
        echo 'memory_limit=256M'; \
        echo 'upload_max_filesize=50M'; \
        echo 'post_max_size=50M'; \
        echo 'max_execution_time=300'; \
        echo 'max_input_time=300'; \
    } > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /app

# Development stage
FROM base AS development

# Install linux headers for Xdebug
RUN apk add --no-cache linux-headers

# Enable Xdebug for development
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug
RUN { \
        echo 'xdebug.mode=debug'; \
        echo 'xdebug.client_host=host.docker.internal'; \
        echo 'xdebug.start_with_request=yes'; \
    } > /usr/local/etc/php/conf.d/xdebug.ini

# Disable OPcache timestamp validation for development
RUN sed -i 's/opcache.validate_timestamps=0/opcache.validate_timestamps=1/' /usr/local/etc/php/conf.d/opcache.ini

# Production build stage
FROM base AS production

# Copy application files
COPY . /app

# Install dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Set proper permissions
RUN chown -R www-data:www-data /app/temp /app/log \
    && chmod -R 755 /app \
    && chmod -R 777 /app/temp /app/log

USER www-data

EXPOSE 9000

CMD ["php-fpm"]