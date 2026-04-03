FROM php:8.2-apache

# MongoDB ve gerekli kütüphaneler
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Apache mod_rewrite'ı aktif et
RUN a2enmod rewrite

# Dosyaları kopyala
COPY . /var/www/html/

# KRİTİK AYAR: Apache'ye ana dosyanın api klasöründe olduğunu söyleyelim
# Hem kök dizini hem de api klasörünü kontrol etmesini sağlıyoruz
RUN echo "DirectoryIndex api/home.php home.php index.php" >> /etc/apache2/apache2.conf

# Port ayarı (Render için)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Dosya izinlerini düzelt (403 hatasını önlemek için)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]