FROM php:7.4-apache

ENV LC_ALL C.UTF-8
ENV LANG de_DE.UTF-8
ENV LANGUAGE de_DE.UTF-8
ENV PHP_INI_DIR /usr/local/etc/php

RUN mkdir -p $PHP_INI_DIR/conf.d

RUN apt-get update

#Install some utils 
RUN apt-get install -y apt-utils mariadb-client libzip-dev libpng-dev 

#Install additional php packages
RUN docker-php-ext-install pdo_mysql \
	&& docker-php-ext-install gd

#Install nodejs and npm
RUN apt-get install -y nodejs npm libnss3-dev libasound-dev libgbm-dev zip dos2unix

#Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Configurate apache
COPY .docker/web/config/vhost.conf /etc/apache2/sites-enabled/000-default.conf
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf \
	&& a2enmod rewrite
	
#Copy startup script
COPY .docker/web/startup.sh /
RUN dos2unix /startup.sh

#Set php.ini
COPY .docker/web/config/php.ini $PHP_INI_DIR/php.ini


#Prepare app data
#See .dockerignore for excludes
#Some folders/files are overridden by the composer file
COPY . /var/www

#run composer to install php dependecies
RUN composer install --no-scripts --working-dir=/var/www/resources

#run npm to install dependencies and run build scripts
RUN npm install --verbose --prefix /var/www/resources/library/puppeteer
	
RUN chown -R www-data:www-data /var/www/html/

CMD ["sh", "/startup.sh"]