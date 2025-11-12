FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli \
    && echo "display_errors=On\nerror_reporting=E_ALL" > /usr/local/etc/php/conf.d/docker.ini

# Copy app source
COPY . /var/www/html/

EXPOSE 80

# Note: This container expects a MySQL server reachable from inside the container.
# If your MySQL runs on the host machine, on macOS you can use host.docker.internal.
# The app tries localhost first, and falls back to host.docker.internal automatically.