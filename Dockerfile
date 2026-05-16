FROM php:8.1-apache

# 1. Instalar dependencias del sistema y extensiones esenciales de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd mbstring bcmath xml

# 2. Instalar Node.js y NPM
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# 3. Activar el módulo rewrite de Apache
RUN a2enmod rewrite

# 4. Copiar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar el directorio base de la app
WORKDIR /var/www/html

# 6. Copiar absolutamente todo el código del proyecto
COPY . .

# 7. Asignar dueños y permisos de una vez para evitar bloqueos de Node
RUN chown -R www-data:www-data /var/www/html

# 8. Instalar dependencias de PHP (Laravel)
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --optimize-autoloader

# 9. INSTALACIÓN Y COMPILACIÓN DE ASSETS
RUN npm install

# Forzar a Vite a compilar usando los archivos limpios del contenedor
RUN npm run build

# COMODÍN DE DIAGNÓSTICO: Esto nos pintará en los logs de Render si el archivo realmente se creó
RUN ls -la public/build || echo "ALERTA: La carpeta build sigue vacía"

# 10. Asegurar permisos totales sobre todo lo generado
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# 11. Redireccionar Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Exponer el puerto
EXPOSE 80

# 12. Comando de arranque: Limpieza absoluta
CMD php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && apache2-foreground