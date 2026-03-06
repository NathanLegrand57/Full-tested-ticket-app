FROM php:8.1-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client

# Extensions PHP nécessaires à Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    bcmath \
    gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copier les fichiers
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /app

EXPOSE 8001

# Lancer Laravel (Railway fournit le PORT)
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
