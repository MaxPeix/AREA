#--------------------------------------------------------------------------
# Image Setup
#--------------------------------------------------------------------------
FROM php:8.1-fpm

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

#--------------------------------------------------------------------------
# Software's Installation
#--------------------------------------------------------------------------
# Installing tools and PHP extensions using "apt", "docker-php", "pecl", and "git".

# Install "curl", "libmemcached-dev", "libpq-dev", "libjpeg-dev",
# "libpng-dev", "libfreetype6-dev", "libssl-dev", "libmcrypt-dev",
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            curl \
            libmemcached-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev \
            unzip \
            p7zip-full \
            zip \
            git; \
    rm -rf /var/lib/apt/lists/*

# Install the PHP pdo_mysql extension
RUN docker-php-ext-install pdo_mysql; \
    # Install the PHP pdo_pgsql extension
    docker-php-ext-install pdo_pgsql; \
    # Install the PHP gd library
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Laravel application files into the container
COPY . /var/www/html

# Install Laravel dependencies
RUN composer install

# Expose the port that Laravel will run on
EXPOSE 8000

# Start Laravel application
CMD php artisan serve --host=0.0.0.0 --port=8000
