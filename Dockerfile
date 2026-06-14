# STAGE 1: Backend Dependencies (Composer)
FROM composer:2.6 as backend-vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts --no-autoloader

# STAGE 2: Final Production Image (PHP + Apache)
FROM php:8.2-apache

# Install extension sistem & driver pdo_mysql untuk koneksi Laravel ke MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Aktifkan mod_rewrite Apache agar routing web Laravel (.htaccess) berfungsi
RUN a2enmod rewrite

# Ubah DocumentRoot Apache agar langsung mengarah ke folder /public milik Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy seluruh source code projek Laravel (termasuk file .blade.php dan assets di public)
COPY . .

# Ambil folder vendor PHP hasil download dari Stage 1
COPY --from=backend-vendor /app/vendor/ /var/www/html/vendor/

# Optimasi autoload Composer untuk mode produksi
RUN composer dump-autoload --no-interaction --optimize

# Set permission hak akses agar server Apache bisa menulis ke folder storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80