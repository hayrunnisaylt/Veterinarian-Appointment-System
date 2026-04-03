FROM php:8.2-apache

# MongoDB sürücüsü ve gerekli bağımlılıklar
RUN apt-get update && apt-get install -y \
    libssl-dev \
    unzip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Proje dosyalarını kopyala
COPY . /var/www/html/

# Port ayarı (Render'ın dinamik portu için)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Apache'yi başlat
CMD ["apache2-foreground"]