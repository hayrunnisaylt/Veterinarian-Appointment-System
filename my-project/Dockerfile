FROM php:8.2-apache

# MongoDB ve kütüphaneler
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Proje dosyalarını kopyala
COPY . /var/www/html/

# KRİTİK ADIM: home.php dosyasını varsayılan index dosyası yap
RUN echo "DirectoryIndex home.php" >> /etc/apache2/apache2.conf

# Port ayarı
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

CMD ["apache2-foreground"]