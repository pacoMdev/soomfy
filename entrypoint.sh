#!/bin/bash

# Salir si ocurre un error
set -e

# Esperar a que el servicio de base de datos esté disponible (opcional)
# echo "Esperando base de datos..."
# until nc -z -v -w30 mysql 3306; do
#   echo "Esperando..."
#   sleep 1
# done

# Ejecuta migraciones si es necesario
php artisan migrate --force

# Opcional: limpiar y cachear config y rutas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Iniciar servidor PHP (esto depende de tu infraestructura)
# Si usas Laravel Octane o quieres usar el servidor embebido:
# php artisan serve --host=0.0.0.0 --port=8080

# Si usas PHP-FPM (lo más común con nginx)
php artisan serve --host=0.0.0.0 --port=8080