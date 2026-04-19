# Stage 1: Build Frontend Assets (Tailwind & Alpine)
FROM node:20 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Serve PHP Application
FROM php:8.2-fpm
WORKDIR /var/www/html

# Install System Dependencies & PHP Exts for PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Project Files
COPY . .

# Copy compiled frontend assets from Stage 1
COPY --from=node_builder /app/public/build ./public/build

# Install PHP Dependencies (No Dev)
RUN composer install --optimize-autoloader --no-dev

# Set Directory Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
