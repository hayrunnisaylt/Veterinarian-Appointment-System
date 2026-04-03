FROM php:8.2-apache

# MongoDB ve kütüphaneler
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Proje dosyalarını kopyala
COPY . /var/www/html/

# home.php dosyasını varsayılan yap
RUN echo "DirectoryIndex home.php" >> /etc/apache2/apache2.conf

# KRİTİK: Apache'nin 80 yerine Render'ın verdiği $PORT'u dinlemesini sağla
# Bu komut Apache konfigürasyonundaki 80 yazılarını Render'ın portuyla değiştirir
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Portu dışarıya aç (Render bunu otomatik yakalar)
EXPOSE 80

CMD ["apache2-foreground"]