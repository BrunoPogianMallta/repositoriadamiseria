FROM php:8.1-apache

# Instala dependências do sistema e Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilita módulos do Apache
RUN a2enmod rewrite

# Instala extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Copia os arquivos do projeto
COPY . /var/www/

# Instala dependências do Composer (se existirem)
WORKDIR /var/www
RUN if [ -f "composer.json" ]; then composer install --no-dev; fi
RUN echo "AddType application/javascript .js" >> /etc/apache2/mods-available/mime.conf

# Ajusta permissões
RUN chown -R www-data:www-data /var/www