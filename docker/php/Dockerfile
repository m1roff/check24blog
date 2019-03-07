FROM miroff/yii2-php7.2-fpm

# Environment settings
ENV PHP_USER_ID=33 \
    PHP_ENABLE_XDEBUG=0 \
    PATH=/app:/app/vendor/bin:/root/.composer/vendor/bin:$PATH \
    TERM=linux \
    VERSION_PRESTISSIMO_PLUGIN=^0.3.7 \
    COMPOSER_ALLOW_SUPERUSER=1

# Add configuration files
COPY image-files/ /


# Add GITHUB_API_TOKEN support for composer
RUN chmod 700 \
        /usr/local/bin/docker-php-entrypoint \
        /usr/local/bin/composer

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer.phar \
        --install-dir=/usr/local/bin && \
    composer clear-cache


RUN mkdir -p /usr/share/man/man1/
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get -y install \
            xauth \
            supervisor \
            procps \
            net-tools \
        --no-install-recommends && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*



COPY www.conf /usr/local/etc/php-fpm.d/
#COPY supervisor.d /etc/supervisor/conf.d/
COPY runapp.sh /runapp.sh
RUN chmod +x /runapp.sh

# Application environment
WORKDIR /app

EXPOSE 9000
CMD ["/runapp.sh"]