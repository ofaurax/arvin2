FROM php:7.4-apache
COPY . /var/www/html/

RUN sed -i 's/80/1912/' /etc/apache2/ports.conf
RUN sed -i 's/80/1912/' /etc/apache2/sites-enabled/000-default.conf

