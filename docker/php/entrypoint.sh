#!/bin/sh
set -e

if [ -f /var/www/composer.json ]; then
  if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "[entrypoint] Installing PHP dependencies..."
    cd /var/www
    composer install --no-interaction --prefer-dist
  fi
fi

if [ "${GENERATE_OPENAPI:-0}" = "1" ]; then
  echo "[entrypoint] Generating OpenAPI..."
  cd /var/www
  composer openapi:generate || true
fi

if [ -f /var/www/frontend/package.json ]; then
  cd /var/www/frontend
  if [ ! -d node_modules ]; then
    echo "[entrypoint] Installing frontend dependencies..."
    if [ -f package-lock.json ]; then
      npm ci --no-audit --no-fund
    else
      npm install --no-audit --no-fund
    fi
  fi
  if [ ! -f /var/www/frontend/dist/index.html ]; then
    echo "[entrypoint] Building frontend..."
    npm run build
  fi
fi

exec php-fpm
