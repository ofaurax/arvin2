FROM php:7.4-apache
COPY . /var/www/html/

RUN sed -i 's/80/1912/' /etc/apache2/ports.conf && sed -i 's/80/1912/' /etc/apache2/sites-enabled/000-default.conf

# Add test token
RUN touch private/tokens/20350515-testtoken.txt

# Add test dirs
RUN mkdir -p private/docs/A001/ && mkdir -p private/docs/G057/cors
RUN touch private/docs/G057/clarinette1.pdf
RUN touch private/docs/G057/cors/cor1.pdf && touch private/docs/G057/cors/cor2.pdf