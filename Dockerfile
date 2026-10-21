# ── Build stage ────────────────────────────────────────────────────────────────
FROM php:8.2-cli AS base

# System dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    && docker-php-ext-install \
        zip \
        mbstring \
        pdo \
        pdo_sqlite \
        fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.10 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (layer cache — only re-runs install when deps change)
COPY composer.json composer.lock* ./

# Install PHP dependencies (no dev tools in prod image)
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy the rest of the application
COPY . .

# Create the SQLite database file
RUN mkdir -p database && touch database/database.sqlite

# Copy .env.example to .env if .env doesn't exist
RUN [ -f .env ] || cp .env.example .env

# Generate app key, run migrations and seed
RUN php artisan key:generate --force \
    && php artisan migrate --force \
    && php artisan db:seed --force

# Storage & cache permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose the Laravel dev server port
EXPOSE 8000

# Start Laravel's built-in server (fine for assessment/demo purposes)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
