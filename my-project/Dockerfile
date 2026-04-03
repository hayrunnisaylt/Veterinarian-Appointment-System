FROM php:8.2-apache

# MongoDB sürücüsünü kur
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Tüm dosyaları (home.php, db.php vb.) Apache'nin kök dizinine kopyala
COPY . /var/www/html/

# home.php dosyasını varsayılan ana sayfa yap
RUN echo "DirectoryIndex home.php index.php" >> /etc/apache2/apache2.conf

# Render port ayarı
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Yetkilendirme
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]