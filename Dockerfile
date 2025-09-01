FROM php:8.2-apache

# Instala as extensões necessárias do PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite

# Copia o código para o container (opcional, pois já usamos volume no compose)
# COPY . /var/www/html

# Define o diretório de trabalho
WORKDIR /var/www/html
