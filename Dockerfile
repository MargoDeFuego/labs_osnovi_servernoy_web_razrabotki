FROM php:8.2-apache

# Включаем mod_rewrite (часто нужен для CMS/фреймворков)
RUN a2enmod rewrite

# Устанавливаем расширения PHP, если нужны (например mysqli, pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Копируем код сайта внутрь контейнера (если не используешь volume)
# COPY src/ /var/www/html/
