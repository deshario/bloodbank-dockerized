FROM yiisoftware/yii2-php:7.1-apache

RUN mkdir -p /app

RUN apt-get update && apt-get install vim nano

WORKDIR /app

VOLUME /app

COPY composer.json /app

COPY composer.lock /app

RUN composer install

ADD . /app