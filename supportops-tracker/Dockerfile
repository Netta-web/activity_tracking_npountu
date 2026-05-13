FROM php:8.4-cli-bookworm

# System dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
        git curl zip unzip \
        libzip-dev libpng-dev libxml2-dev libonig-dev \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring zip xml bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# Node.js 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer 2
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY supportops-tracker/ .

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

RUN npm install

RUN npm run build

# Ensure storage directories exist and are writable
RUN mkdir -p \
        storage/logs \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# All artisan commands run at container start so they have access to
# runtime environment variables (APP_KEY, DB_*, etc.)
CMD php artisan package:discover --ansi \
    && php artisan storage:link --force \
    && php artisan migrate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
