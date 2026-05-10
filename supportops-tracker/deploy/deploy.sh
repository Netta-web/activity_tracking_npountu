#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────
# SupportOps Tracker — VPS Deployment Script
# Run as the deploy user (NOT root) after first-time server setup.
# Usage:  bash deploy.sh
# ─────────────────────────────────────────────────────────────────
set -euo pipefail

APP_DIR="/var/www/supportops"
PHP="/usr/bin/php8.4"
COMPOSER="/usr/local/bin/composer"

echo "==> Pulling latest code..."
git -C "$APP_DIR" pull origin main

echo "==> Installing production dependencies (no dev)..."
"$COMPOSER" install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --working-dir="$APP_DIR"

echo "==> Running database migrations..."
"$PHP" "$APP_DIR/artisan" migrate --force

echo "==> Caching config / routes / views..."
"$PHP" "$APP_DIR/artisan" config:cache
"$PHP" "$APP_DIR/artisan" route:cache
"$PHP" "$APP_DIR/artisan" view:cache
"$PHP" "$APP_DIR/artisan" event:cache
"$PHP" "$APP_DIR/artisan" optimize

echo "==> Setting file permissions..."
sudo chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
sudo chmod -R 775               "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

echo "==> Reloading PHP-FPM..."
sudo systemctl reload php8.4-fpm

echo "==> Done. Visit your domain to verify."
