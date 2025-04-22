# Usa una imagen oficial de PHP con extensiones necesarias
FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    bash \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    vim \
    nano \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Establece directorio de trabajo
WORKDIR /var/www

# Copia todos los archivos del proyecto
COPY . .

# Instala dependencias de PHP
RUN composer install --optimize-autoloader --no-dev

# Copia el archivo de entorno de ejemplo si no existe .env
RUN cp .env.example .env || true

# Genera la clave de aplicación
RUN php artisan key:generate || true

# Da permisos al storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expone el puerto 8080
EXPOSE 8080

# Comando por defecto: usa el servidor de Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080