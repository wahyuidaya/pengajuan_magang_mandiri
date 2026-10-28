FROM php:8.2-apache

# Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang dibutuhkan Laravel, DomPDF, & MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Ambil Composer resmi terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Direktori kerja
WORKDIR /var/www/html

# Salin seluruh source code proyek
COPY . .

# Konfigurasi Apache Document Root ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Install dependencies dan build aset frontend
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Atur hak akses direktori storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Salin entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["/usr/local/bin/docker-entrypoint.sh"]
