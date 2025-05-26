FROM php:8.1-apache

# Instala dependências e Composer
RUN apt-get update && apt-get install -y \
    git unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilita o mod_rewrite
RUN a2enmod rewrite

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_mysql

# Copia os arquivos do projeto
COPY . /var/www/html

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Define diretório de trabalho
WORKDIR /var/www/html

# Garante que .htaccess será interpretado
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Instala dependências do Composer, se houver
RUN if [ -f "composer.json" ]; then composer install --no-dev; fi
