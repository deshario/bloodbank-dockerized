FROM yiisoftware/yii2-php:7.1-apache

# RUN apt-get update && apt-get install vim

RUN mkdir -p /app

WORKDIR /app

# ADD composer.json /app

# ADD composer.lock /app

# RUN composer install

VOLUME /app

ADD . /app

RUN composer install