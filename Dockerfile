FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar archivos del proyecto
COPY . /var/www

# Copiar los assets del frontend ya compilados
# COPY --from=frontend /app/public /var/www/public

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-dev

# Asignar permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# ⬇️ AQUI pones estas líneas para usar tu entrypoint personalizado
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Usar el entrypoint
CMD ["entrypoint.sh"]