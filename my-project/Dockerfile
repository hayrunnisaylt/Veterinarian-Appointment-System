# PHP ve Apache içeren resmi imajı kullan
FROM php:8.2-apache

# Gerekli sistem kütüphanelerini kur ve MongoDB sürücüsünü yükle
RUN apt-get update && apt-get install -y \
    libssl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Apache'nin rewrite modunu aç (URL yönlendirmeleri için)
RUN a2enmod rewrite

# Proje dosyalarını Docker içindeki web dizinine kopyala
COPY . /var/www/html/

# Render'ın verdiği portu Apache'ye tanıt
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Apache'yi başlat
CMD ["apache2-foreground"]