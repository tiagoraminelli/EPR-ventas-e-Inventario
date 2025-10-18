FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Copiar archivos del proyecto
COPY . /var/www/html

# Configuración del Apache
WORKDIR /var/www/html
