# Etapa 1: Node + PHP + Composer para compilación y dependencias
FROM node:18-bullseye AS build

# Instala PHP y extensiones necesarias
RUN apt-get update && apt-get install -y \
    php php-cli php-mbstring php-xml php-bcmath php-curl php-zip php-mysql php-tokenizer php-fileinfo unzip curl git \
    && apt-get clean

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /app

# Copia el código
COPY . .

# Instala dependencias y compila Vite
RUN composer install --no-dev --optimize-autoloader && \
    npm install && \
    npm run build

# Etapa final: imagen mínima solo para producción
FROM php:8.2-cli

# Instala extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    php-mbstring php-xml php-bcmath php-curl php-zip php-mysql php-tokenizer php-fileinfo unzip curl git \
    && apt-get clean

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /app

# Copia el proyecto desde la etapa anterior
COPY --from=build /app /app

# Copia el script de inicio
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto (usado por php artisan serve)
EXPOSE 8000

# Define el script de inicio
CMD ["/entrypoint.sh"]
