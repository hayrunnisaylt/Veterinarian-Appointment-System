FROM php:8.2-apache

# MongoDB ve gerekli kütüphaneler
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# 1. Dosyaları kopyala
COPY . /var/www/html/

# 2. KRİTİK: api klasörünün içindeki her şeyi bir üst dizine (html) taşı
# Böylece home.php direkt /var/www/html/home.php olacak
RUN cp -r /var/www/html/api/* /var/www/html/ || true

# 3. Giriş dosyasını tanımla
RUN echo "DirectoryIndex home.php index.php" >> /etc/apache2/apache2.conf

# 4. Port ayarı (Render için)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 5. İzinleri en geniş yetkiyle güncelle (403'ü bitirmek için)
RUN chmod -R 777 /var/www/html

CMD ["apache2-foreground"]