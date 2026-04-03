FROM php:8.2-apache

# MongoDB sürücüsü kurulumu
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Proje dosyalarını kopyala
COPY . /var/www/html/

# KRİTİK: index/index.html dosyasını varsayılan giriş noktası yapıyoruz
# Apache'ye önce index/index.html, sonra diğerlerini kontrol etmesini söylüyoruz
RUN echo "DirectoryIndex İndex/index.html index.html home.php" > /etc/apache2/conf-available/docker-php.conf && \
    a2enconf docker-php

# Port ayarı (Render için)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Yetkilendirme
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]