FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ \
    libpng-dev libxml2-dev libfreetype6-dev libjpeg62-turbo-dev libwebp-dev libzip-dev \
    libonig-dev libxslt-dev;

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN docker-php-ext-configure sysvsem --enable-sysvsem
RUN docker-php-ext-configure sysvshm --enable-sysvshm
RUN docker-php-ext-configure sysvmsg --enable-sysvmsg
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip xsl sysvsem sysvshm sysvmsg

RUN pecl install apcu && docker-php-ext-enable apcu

#Debut ajout perso
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV UPLOAD_MAX_FILESIZE=20M
ENV POST_MAX_SIZE = 21M
ENV PHP_MEMORY_LIMIT=3072M

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN find /tmp -type d -exec chmod -v 1777 {} +

#fin ajout perso
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN git config --global user.email "daric.desbonnes@ekino.com" \
    && git config --global user.name "Daric DESBONNES"

WORKDIR /var/www/