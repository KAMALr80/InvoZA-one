FROM php:8.2-cli

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    libonig-dev libxml2-dev \
    && docker-php-ext-install \
    pdo pdo_mysql zip mbstring bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

# Copy env safely
RUN cp .env.example .env || true

RUN composer install --no-dev --no-interaction --prefer-dist

RUN php artisan key:generate || true
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
