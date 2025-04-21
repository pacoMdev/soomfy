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
# Crear archivo .env desde variables de entorno definidas en Render
RUN echo "APP_NAME=${APP_NAME}" > .env && \
    echo "APP_ENV=${APP_ENV}" >> .env && \
    echo "APP_KEY=${APP_KEY}" >> .env && \
    echo "APP_DEBUG=${APP_DEBUG}" >> .env && \
    echo "APP_URL=${APP_URL}" >> .env && \
    echo "CACHE_DRIVER=file" >> .env && \
    echo "LOG_CHANNEL=${LOG_CHANNEL}" >> .env && \
    echo "LOG_DEPRECATIONS_CHANNEL=${LOG_DEPRECATIONS_CHANNEL}" >> .env && \
    echo "LOG_LEVEL=${LOG_LEVEL}" >> .env && \
    echo "DB_CONNECTION=${DB_CONNECTION}" >> .env && \
    echo "DB_HOST=${DB_HOST}" >> .env && \
    echo "DB_PORT=${DB_PORT}" >> .env && \
    echo "DB_DATABASE=${DB_DATABASE}" >> .env && \
    echo "DB_USERNAME=${DB_USERNAME}" >> .env && \
    echo "DB_PASSWORD=${DB_PASSWORD}" >> .env && \
    echo "BROADCAST_DRIVER=${BROADCAST_DRIVER}" >> .env && \
    echo "FILESYSTEM_DISK=${FILESYSTEM_DISK}" >> .env && \
    echo "QUEUE_CONNECTION=${QUEUE_CONNECTION}" >> .env && \
    echo "SESSION_DRIVER=${SESSION_DRIVER}" >> .env && \
    echo "SESSION_LIFETIME=${SESSION_LIFETIME}" >> .env && \
    echo "MEMCACHED_HOST=${MEMCACHED_HOST}" >> .env && \
    echo "REDIS_HOST=${REDIS_HOST}" >> .env && \
    echo "REDIS_PASSWORD=${REDIS_PASSWORD}" >> .env && \
    echo "REDIS_PORT=${REDIS_PORT}" >> .env && \
    echo "MAIL_MAILER=${MAIL_MAILER}" >> .env && \
    echo "MAIL_HOST=${MAIL_HOST}" >> .env && \
    echo "MAIL_PORT=${MAIL_PORT}" >> .env && \
    echo "MAIL_USERNAME=${MAIL_USERNAME}" >> .env && \
    echo "MAIL_PASSWORD=${MAIL_PASSWORD}" >> .env && \
    echo "MAIL_ENCRYPTION=${MAIL_ENCRYPTION}" >> .env && \
    echo "MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}" >> .env && \
    echo "MAIL_FROM_NAME=${MAIL_FROM_NAME}" >> .env && \
    echo "AWS_ACCESS_KEY_ID=${AWS_ACCESS_KEY_ID}" >> .env && \
    echo "AWS_SECRET_ACCESS_KEY=${AWS_SECRET_ACCESS_KEY}" >> .env && \
    echo "AWS_DEFAULT_REGION=${AWS_DEFAULT_REGION}" >> .env && \
    echo "AWS_BUCKET=${AWS_BUCKET}" >> .env && \
    echo "AWS_USE_PATH_STYLE_ENDPOINT=${AWS_USE_PATH_STYLE_ENDPOINT}" >> .env && \
    echo "PUSHER_APP_ID=${PUSHER_APP_ID}" >> .env && \
    echo "PUSHER_APP_KEY=${PUSHER_APP_KEY}" >> .env && \
    echo "PUSHER_APP_SECRET=${PUSHER_APP_SECRET}" >> .env && \
    echo "PUSHER_HOST=${PUSHER_HOST}" >> .env && \
    echo "PUSHER_PORT=${PUSHER_PORT}" >> .env && \
    echo "PUSHER_SCHEME=${PUSHER_SCHEME}" >> .env && \
    echo "PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}" >> .env && \
    echo "VITE_PUSHER_APP_KEY=${VITE_PUSHER_APP_KEY}" >> .env && \
    echo "VITE_PUSHER_HOST=${VITE_PUSHER_HOST}" >> .env && \
    echo "VITE_PUSHER_PORT=${VITE_PUSHER_PORT}" >> .env && \
    echo "VITE_PUSHER_SCHEME=${VITE_PUSHER_SCHEME}" >> .env && \
    echo "VITE_PUSHER_APP_CLUSTER=${VITE_PUSHER_APP_CLUSTER}" >> .env && \
    echo "GOOGLE_API_KEY=${GOOGLE_API_KEY}" >> .env && \
    echo "GOOGLE_CLIENT_ID=${GOOGLE_CLIENT_ID}" >> .env && \
    echo "GOOGLE_CLIENT_SECRET=${GOOGLE_CLIENT_SECRET}" >> .env && \
    echo "GOOGLE_CALLBACK_REDIRECT=${GOOGLE_CALLBACK_REDIRECT}" >> .env && \
    echo "STRIPE_PUBLIC_KEY=${STRIPE_PUBLIC_KEY}" >> .env && \
    echo "STRIPE_SECRET_KEY=${STRIPE_SECRET_KEY}" >> .env && \
    echo "VITE_STRIPE_PUBLIC_KEY=${VITE_STRIPE_PUBLIC_KEY}" >> .env && \
    echo "VITE_FIREBASE_APIKEY=${VITE_FIREBASE_APIKEY}" >> .env && \
    echo "VITE_FIREBASE_AUTHDOMAIN=${VITE_FIREBASE_AUTHDOMAIN}" >> .env && \
    echo "VITE_FIREBASE_PROJECTID=${VITE_FIREBASE_PROJECTID}" >> .env && \
    echo "VITE_FIREBASE_STORAGEBUCKET=${VITE_FIREBASE_STORAGEBUCKET}" >> .env && \
    echo "VITE_FIREBASE_MESSAGINGSENDERID=${VITE_FIREBASE_MESSAGINGSENDERID}" >> .env && \
    echo "VITE_FIREBASE_APPID=${VITE_FIREBASE_APPID}" >> .env
# Generar la clave de Laravel
# Limpiar caché de configuración antes de generar la clave
RUN php artisan config:clear && php artisan key:generate

# Configurar permisos
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Exponer el puerto 10000 Render default expose
EXPOSE 10000

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000 || tail -f /dev/null
