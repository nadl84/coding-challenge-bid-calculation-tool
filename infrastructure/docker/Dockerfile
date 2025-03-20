FROM dunglas/frankenphp

WORKDIR /app

RUN install-php-extensions \
    pdo_mysql \
    zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

EXPOSE 80
EXPOSE 443

CMD ["frankenphp", "run", "--config", "/app/infrastructure/frankenphp/Caddyfile"] 