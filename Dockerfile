# Imagen base con PHP, Composer y extensiones necesarias
FROM laravelsail/php83-composer AS build

# Instalar Node.js (para Vite/Vue)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get update && \
    apt-get install -y nodejs

# Crear carpeta de app
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP y Node
RUN composer install --optimize-autoloader --no-dev \
 && npm install \
 && npm run build

# Dar permisos
RUN chmod -R 775 storage bootstrap/cache

# Crear symlink de storage
RUN php artisan storage:link || true

# Exponer puerto (por si lo corres local)
EXPOSE 8080

# Comando de arranque
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}