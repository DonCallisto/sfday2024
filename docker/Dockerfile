FROM phpdockerio/php:8.3-cli

MAINTAINER Samuele "DonCallisto" Lilli <samuele.lilli@madisoft.it>

RUN apt-get update && apt-get install -y \
    git \
    sqlite3 \
    php-sqlite3 \
    php8.3-fpm

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN mkdir /user \
    && useradd -d /user -s /bin/bash user \
    && chown user /user

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

ENTRYPOINT ["/bin/bash"]

USER user