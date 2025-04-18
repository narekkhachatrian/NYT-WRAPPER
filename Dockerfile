FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential git curl unzip vim libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    # ---------------------------------------------------------------
    # Core PHP extensions that really need compilation
    # ---------------------------------------------------------------
    && docker-php-ext-install -j$(nproc) \
         gd intl mbstring pdo_mysql zip shmop \
    # ---------------------------------------------------------------
    # PECL extensions
    # ---------------------------------------------------------------
    && pecl install redis \
    && docker-php-ext-enable redis \
    # ---------------------------------------------------------------
    # cleanup
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www
RUN chown -R www-data:www-data /var/www

USER www-data

EXPOSE 9080
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
