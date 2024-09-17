# Use uma imagem base com PHP e Apache
FROM php:8.0-apache

# Instala as dependências necessárias, caso precise do gd, imagick, curl, etc.
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Instale o imagick, se necessário
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Copie os arquivos da sua aplicação para a pasta do Apache
COPY . /var/www/html/

# Define a porta do container
EXPOSE 80

# Inicia o Apache
CMD ["apache2-foreground"]
