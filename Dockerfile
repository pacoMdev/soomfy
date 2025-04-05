FROM php:8.2-fpm

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de la aplicación
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP y Node
RUN composer install --no-dev --optimize-autoloader
RUN apt-get install -y build-essential
RUN rm -f package-lock.json
RUN npm cache clean --force
RUN npm --verbose install || { echo "❌ Error en npm install"; exit 1; }
RUN npm run build || { echo "❌ Error en npm run build"; exit 1; }

# Configurar variables de entorno
RUN printenv | grep -v "no_proxy" > .env

# Generar la clave de Laravel
RUN php artisan key:generate

# Configurar permisos
RUN chmod -R 777 storage bootstrap/cache

# Exponer el puerto 8000
EXPOSE 8000

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
