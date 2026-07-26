# ---------- Frontend ----------
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .

RUN npm run build


# ---------- Backend ----------
FROM php:8.3-apache

WORKDIR /var/www/html

# Dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libsqlite3-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install \
        mbstring \
        pdo \
        pdo_sqlite \
        bcmath \
        zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . .

# Copiar assets compilados por Vite
COPY --from=frontend /app/public/build ./public/build

# Composer en producción
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Apache debe servir public/
RUN sed -ri \
    -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Permitir .htaccess
RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Permisos de Laravel
RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Puerto HTTP
EXPOSE 80

CMD ["apache2-foreground"]