#!/usr/bin/env bash
set -e

echo "🚀 Iniciando deploy en Railway..."

# 1. Instalar dependencias PHP (sólo si no viene cacheado)
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

# 2. Ejecutar migraciones
echo "📄 Ejecutando migraciones..."
php artisan migrate --force

# 3. Ejecutar seeders (ignora errores de existentes)
echo "🌱 Lanzando seeders..."
php artisan db:seed --force || echo "✋ Seeders ya aplicados, continúo..."

# 4. Crear enlace simbólico para /storage
echo "🔗 Creando enlace simbólico de storage..."
php artisan storage:link

php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

# 5. Compilar assets
echo "🎨 Compilando assets con Vite..."
npm install && npm run build

# 6. Levantar el servidor
echo "🌐 Levantando servidor Laravel en producción..."
php -S 0.0.0.0:$PORT -t public
