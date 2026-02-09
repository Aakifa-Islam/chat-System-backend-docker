# PHP-FPM official image use karein
FROM php:8.2-fpm

# System dependencies install karein
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libssl-dev

# PHP extensions install karein
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# MongoDB extension install karein (Zaroori step)
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Composer ko copy karein
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Working directory set karein
WORKDIR /var/www

# Permissions set karein
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]