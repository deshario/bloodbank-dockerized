FROM yiisoftware/yii2-php:7.1-apache

RUN mkdir -p /app

WORKDIR /app

VOLUME /app

ADD composer.json /app

RUN composer install

ADD . /app