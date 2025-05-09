#!/bin/bash

set -e

if [ ! -f .env ]; then
    echo "📝 .env file does not exist. Copying .env.example to .env..."
    cp .env.example .env
else
    echo "📝 .env file already exists. Skipping copy."
fi

echo "⚙️ Update composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

if ! grep -q '^APP_KEY=' .env || [ -z "$(grep '^APP_KEY=' .env | cut -d'=' -f2)" ]; then
    echo "🔑 APP_KEY is empty. Generating new key..."
    php artisan key:generate --force
else
    echo "🔑 APP_KEY already set. Skipping key generation."
fi

echo "📦 Running Laravel optimizations..."
php artisan optimize

echo "📦 Running Laravel migrations..."
php artisan migrate

echo "🔧 Running Laravel tests..."
php artisan test --testsuite=Unit

echo "🌎 Starting Apache..."
service apache2 restart

echo "🧵 Starting Supervisord..."
exec /usr/bin/supervisord -n
