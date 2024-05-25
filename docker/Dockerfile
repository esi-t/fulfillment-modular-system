FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    apt-transport-https \
    gnupg2 \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    supervisor \
    unixodbc-dev

# Add Microsoft repository and install ODBC driver and tools
RUN apt-get update && apt-get install -y gnupg2 curl apt-transport-https && \
    curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list && \
    apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql17 unixodbc-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install SQLSRV and PDO_SQLSRV extensions
RUN pecl config-set php_ini /usr/local/etc/php/php.ini \
    && pecl install sqlsrv pdo_sqlsrv \
    && printf "; priority=20\nextension=sqlsrv.so\n" > /usr/local/etc/php/conf.d/sqlsrv.ini \
    && printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /usr/local/etc/php/conf.d/pdo_sqlsrv.ini

# Install Supervisor
RUN mkdir -p /var/log/supervisor

# Copy Supervisor configuration
COPY ./docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/
COPY ./docker/supervisor/supervisord.conf /etc/supervisord.conf

# Copy application source
COPY . /var/www/html

# Copy start-container script
COPY ./docker/start-container /usr/local/bin/start-container

# Set working directory
WORKDIR /var/www/html
#RUN chmod 777 -R storage/
#RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN chmod +x /usr/local/bin/start-container

# Expose port 9000 and start supervisor
EXPOSE 9000

#CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
