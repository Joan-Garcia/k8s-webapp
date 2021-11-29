FROM php:8.0.11-apache

#ENV DB_HOST=host.docker.internal
#ENV DB_USER=root
#ENV DB_PASS=root
#ENV DB_NAME=bienes_raices

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy project files to container
COPY . /var/www/html

# Grant permissions to this directory
RUN chmod 777 imagenes

# Run in port 80
EXPOSE 80