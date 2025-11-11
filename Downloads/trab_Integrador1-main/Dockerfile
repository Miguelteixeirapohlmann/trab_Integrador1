FROM php:8.2-apache

# Instala dependências do sistema e bibliotecas para extensões PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
	git \
	unzip \
	libzip-dev \
	libpng-dev \
	libjpeg-dev \
	libfreetype6-dev \
	libonig-dev \
	libicu-dev \
	zip \
	&& rm -rf /var/lib/apt/lists/*

# Configura e instala extensões PHP necessárias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) pdo pdo_mysql mysqli mbstring zip gd intl

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite

# Instala Composer globalmente
ENV COMPOSER_ALLOW_SUPERUSER=1 \
	COMPOSER_HOME=/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Criar diretório para sessões PHP
RUN mkdir -p /var/www/html/sessions && \
    chown -R www-data:www-data /var/www/html/sessions && \
    chmod 777 /var/www/html/sessions

# Ajustar configurações de sessão do PHP
RUN echo "session.save_path = '/var/www/html/sessions'" >> /usr/local/etc/php/conf.d/sessions.ini && \
    echo "session.gc_maxlifetime = 86400" >> /usr/local/etc/php/conf.d/sessions.ini && \
    echo "session.cookie_lifetime = 86400" >> /usr/local/etc/php/conf.d/sessions.ini

# Ajustes de permissão (garante que www-data consiga escrever em volumes onde necessário)
RUN chown -R www-data:www-data /var/www/html || true

# Exponha porta padrão do Apache
EXPOSE 80

# Entrypoint padrão (o Apache já é iniciado pela imagem base)
