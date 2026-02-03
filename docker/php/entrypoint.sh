#!/bin/sh
set -e

if [ -f /var/www/composer.json ]; then
  echo "[entrypoint] Installing PHP dependencies..."
  cd /var/www
  composer install --no-interaction --prefer-dist
fi

if [ -f /var/www/frontend/package.json ]; then
  echo "[entrypoint] Building frontend..."
  cd /var/www/frontend
  if [ -f package-lock.json ]; then
    npm ci --no-audit --no-fund
  else
    npm install --no-audit --no-fund
  fi
  npm run build
fi

exec php-fpm
