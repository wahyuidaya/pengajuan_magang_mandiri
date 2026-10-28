#!/bin/bash
set -e

# Sesuaikan port Apache jika hosting memberikan variabel $PORT (seperti di Render)
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
fi

php artisan storage:link || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Jalankan migrasi database otomatis saat pertama deploy
php artisan migrate --force || true

exec apache2-foreground
