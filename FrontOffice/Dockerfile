# Utiliser une image PHP officielle avec Apache
FROM php:8.2-apache

# Activer le module mod_rewrite pour les URLs propres
RUN a2enmod rewrite

# Installer les dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Copier Composer depuis l'image composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire racine du serveur web
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier composer.json et composer.lock EN PREMIER
COPY composer.json composer.lock* ./

# Installer les dépendances avec Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copier le reste du projet (après installation des dépendances)
COPY --chown=www-data:www-data . .

# Donner les permissions appropriées
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/

# Exposer le port 80
EXPOSE 80

# Script d'entrypoint : verifie/installe vendor si necessaire puis lance Apache
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
