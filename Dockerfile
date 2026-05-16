FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git \
    libpq-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd mbstring bcmath xml

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar solo los archivos de dependencias primero (mejor cache)
COPY package.json package-lock.json ./
RUN npm ci

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-plugins --no-scripts \
    --prefer-dist --no-dev --optimize-autoloader

# Copiar el resto del proyecto
COPY . .

# ⚠️ Clave: definir APP_ENV antes del build de Vite
ENV APP_ENV=production
ENV NODE_ENV=production

# Build de assets
RUN npm run build

# Verificación — si falla, el build de Docker falla también (te avisa)
RUN test -f /var/www/html/public/build/manifest.json \
    || (echo "ERROR: manifest.json no fue generado" && exit 1)

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

EXPOSE 80

CMD php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear \
    && php artisan route:clear \
    && apache2-foreground