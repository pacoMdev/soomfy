FROM php:8.2-fpm AS backend

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Etapa 1: Build del frontend con Node
FROM node:18 AS frontend

WORKDIR /app
COPY resources/js ./resources/js
COPY package*.json ./
RUN npm install
RUN npm run build


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


# Etapa final: combinar backend con assets compilados del frontend
FROM php:8.2-fpm

COPY --from=backend /usr/local/bin/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY --from=backend /var/www /var/www
COPY --from=frontend /app/public /var/www/public

WORKDIR /var/www

CMD ["entrypoint.sh"]