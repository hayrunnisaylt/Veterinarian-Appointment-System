FROM php:8.2-apache

# MongoDB sürücüsünü kur
RUN apt-get update && apt-get install -y libssl-dev && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb

# Tüm dosyaları konteynere kopyala
COPY . /var/www/html/

# KRİTİK ADIM: api klasörünün içindekileri ana dizine taşı ve api klasörünü sil
# Böylece home.php direkt /var/www/html/home.php olur
RUN if [ -d "/var/www/html/api" ]; then \
    cp -r /var/www/html/api/* /var/www/html/ && \
    rm -rf /var/www/html/api; \
    fi

# Giriş dosyasını ayarla
RUN echo "DirectoryIndex home.php index.php" >> /etc/apache2/apache2.conf

# Port ayarı (Render zorunluluğu)
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Yetkilendirme (403/404 önlemek için)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

CMD ["apache2-foreground"]