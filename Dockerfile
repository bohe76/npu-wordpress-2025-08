FROM wordpress:latest

RUN a2enmod rewrite

COPY .htaccess /var/www/html/.htaccess