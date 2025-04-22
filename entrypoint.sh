#!/bin/bash

echo "➡️ Instalando dependencias de Node..."
npm install

echo "🎨 Compilando frontend Vue..."
npm run build

echo "📦 Instalando dependencias de PHP..."
composer update
composer install --optimize-autoloader --no-dev

echo "⚙️ Ejecutando comandos de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link || true

echo "🚀 Iniciando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}