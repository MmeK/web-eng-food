FROM public.ecr.aws/docker/library/php:fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN pecl install xdebug && docker-php-ext-enable xdebug
