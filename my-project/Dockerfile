FROM php:8.2-apache

# MongoDB sürücüsü
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# PHP dosyalarını işlemesi için Apache modüllerini zorla aç
RUN a2enmod rewrite
RUN a2enmod php8.2 || true

# Dosyaları kopyala
COPY . /var/www/html/

# KRİTİK: Apache'ye .php uzantılı dosyaları PHP motoruna göndermesini söyle
RUN echo "<FilesMatch \.php$>\n\
    SetHandler application/x-httpd-php\n\
</FilesMatch>" >> /etc/apache2/apache2.conf

# index/index.html önceliği
RUN echo "DirectoryIndex index/index.html index.html home.php" > /etc/apache2/conf-available/docker-php.conf && \
    a2enconf docker-php

# Port ve yetki ayarları
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]