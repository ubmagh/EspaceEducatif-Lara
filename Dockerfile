FROM ubuntu:20.04

COPY . /usr/src/app
WORKDIR /usr/src/app
EXPOSE 8000

USER root
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update
RUN apt-get -y install software-properties-common
RUN add-apt-repository ppa:ondrej/php


# Install selected extensions and other stuff
RUN apt-get update -y \
    && apt-get upgrade -y \
    && apt-get -y --no-install-recommends install git wget apt-utils libxml2-dev gnupg apt-transport-https 

RUN apt-get update
RUN apt-get -y install curl 
RUN apt install -y php7.4 php7.4-cli php7.4-fpm php7.4-json php7.4-common php7.4-mysql php7.4-http php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php-pear php7.4-bcmath

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools
RUN apt-get install -y --fix-missing php-pear php7.4-raphf php7.4-dev wget unixodbc-dev 
RUN pecl install sqlsrv pdo_sqlsrv  raphf propro pecl_http


RUN echo "extension=sqlsrv.so" > /etc/php/7.4/mods-available/sqlsrv.ini && echo "extension=pdo_sqlsrv.so" > /etc/php/7.4/mods-available/pdo_sqlsrv.ini 
RUN echo "extension=raphf.so" > /etc/php/7.4/mods-available/http.ini && echo "extension=propro.so" > /etc/php/7.4/mods-available/http.ini && echo "extension=http.so" > /etc/php/7.4/mods-available/http.ini
RUN phpenmod -v 7.4 sqlsrv pdo_sqlsrv


RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

RUN composer install --no-interaction 
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

CMD [ "php", "-t", "/usr/src/app/public", "-S", "0.0.0.0:8000" ]

