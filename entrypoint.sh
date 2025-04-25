#!/usr/bin/env bash
set -e

echo "🚀 Iniciando deploy en Railway..."

# 1. Instalar dependencias PHP
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader

# 2. Ejecutar migraciones
echo "📄 Ejecutando migraciones..."
php artisan migrate --force

# 3. Ejecutar seeders (ignora errores si ya están)
echo "🌱 Lanzando seeders..."
php artisan db:seed --force || echo "✋ Seeders ya aplicados, continúo..."

# 4. Crear enlace simbólico para /storage
echo "🔗 Creando enlace simbólico de storage..."
php artisan storage:link

# 5. Compilar assets frontend
echo "🎨 Compilando assets con Vite..."
npm install
npm run build

# 6. Limpiar y cachear configuración
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

# 7. Levantar servidor Laravel
echo "🌐 Levantando servidor Laravel en puerto ${PORT}..."
php artisan serve --host=0.0.0.0 --port="${PORT}"
