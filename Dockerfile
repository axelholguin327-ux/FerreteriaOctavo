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

# Copiar TODO el proyecto de una sola vez
COPY . .

ENV APP_ENV=production
ENV NODE_ENV=production

# Instalar dependencias Node Y buildear en un solo paso
RUN npm install --include=dev && ./node_modules/.bin/vite build

# Debug: mostrar qué generó Vite
RUN echo "=== Contenido de public/build ===" \
    && ls -la /var/www/html/public/build/ \
    && echo "=== manifest.json ===" \
    && cat /var/www/html/public/build/manifest.json

RUN composer install --no-interaction --no-plugins --no-scripts \
    --prefer-dist --no-dev --optimize-autoloader

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