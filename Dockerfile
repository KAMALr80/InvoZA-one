FROM php:8.3-apache

# ==================== SYSTEM DEPENDENCIES ====================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libwebp-dev \
    libxpm-dev \
    libssl-dev \
    libgd-dev \
    nano \
    vim \
    && rm -rf /var/lib/apt/lists/*

# ==================== PHP EXTENSIONS ====================
# Configure GD with all supported formats (required for QR code)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mysqli \
    zip \
    gd \
    exif \
    pcntl \
    bcmath \
    opcache \
    sockets

# Enable GD extension specifically for QR code generation
RUN docker-php-ext-enable gd

# Install Redis extension (optional but recommended for sessions)
RUN pecl install redis && docker-php-ext-enable redis || true

# ==================== APACHE CONFIGURATION ====================
# Copy custom Apache config
COPY public/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite \
    && a2enmod headers \
    && a2enmod ssl \
    && a2enmod deflate

# ==================== COMPOSER ====================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ==================== WORKDIR ====================
WORKDIR /var/www/html

# ==================== COPY APPLICATION ====================
COPY . .

# ==================== COMPOSER INSTALLATION ====================
# Install all dependencies (including packages needed for 2FA)
RUN composer install --optimize-autoloader --no-interaction

# ==================== LARAVEL PERMISSIONS ====================
# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/public

# ==================== PHP CONFIGURATION ====================
# Copy custom PHP.ini settings
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_input_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "gd.jpeg_ignore_warning = 1" >> /usr/local/etc/php/conf.d/gd.ini

# ==================== CREATE ENVIRONMENT FILE ====================
# Copy .env.example to .env if not exists
RUN if [ ! -f .env ]; then cp .env.example .env 2>/dev/null || echo "APP_KEY=" > .env; fi

# ==================== GENERATE APP KEY ====================
RUN php artisan key:generate --force || true

# ==================== STORAGE LINK ====================
RUN php artisan storage:link || true

# ==================== CACHE OPTIMIZATION ====================
RUN php artisan config:cache || true \
    && php artisan route:cache || true \
    && php artisan view:cache || true

# ==================== EXPOSE PORT ====================
EXPOSE 80

# ==================== HEALTHCHECK ====================
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# ==================== START COMMAND ====================
# Run migrations and start Apache
CMD php artisan migrate --force && apache2-foreground
