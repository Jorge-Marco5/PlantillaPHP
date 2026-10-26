FROM php:8.5-fpm

# Instalar dependencias del sistema y extensiones de PHP requeridas
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de definición de dependencias primero para aprovechar la caché de Docker
COPY composer.json composer.lock* ./

# Instalar dependencias de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copiar el resto del código del proyecto
COPY . .

# Crear directorios de caché y logs con permisos adecuados
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html/var \
    && chmod -R 777 /var/www/html/var

EXPOSE 9000

CMD ["sh", "-c", "mkdir -p var/cache var/log && chmod -R 777 var && php-fpm"]
