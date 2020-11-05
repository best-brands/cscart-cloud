FROM php:7.4-fpm-alpine3.12

# Install packages
RUN set -xe \
    && apk --no-cache add nginx pcre-dev supervisor curl bash less git wkhtmltopdf libpng-dev libxslt-dev libzip libzip-dev \
    && docker-php-ext-install bcmath exif gd mysqli opcache soap sockets xsl zip \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f redis  \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && rm -rf /usr/share/php \
    && rm -rf /tmp/* \
    && apk del  .phpize-deps \
    && docker-php-ext-enable opcache redis soap \
    && docker-php-source delete \
    && apk del libpng-dev libxslt-dev libzip-dev pcre-dev \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . /var/www

RUN set -xe \
    && cp -r /var/www/config/nginx.conf /etc/nginx/nginx.conf \
    && cp -r /var/www/config/fpm-pool.conf "$PHP_INI_DIR/php-fpm.d/zzz_custom.conf" \
    && cp -r /var/www/config/php.ini "$PHP_INI_DIR/conf.d/zzz_custom.ini" \
    && mkdir -p /etc/supervisor/conf.d && cp -r /var/www/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf \
    && php /var/www/builder/installer.php -d /var/www/cscart \
    && chown -R www-data:www-data /var/www

WORKDIR /var/www
VOLUME /var/www/cscart/images
VOLUME /var/www/cscart/var/files

EXPOSE 80 443 465 587

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]