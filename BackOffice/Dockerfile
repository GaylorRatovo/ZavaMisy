# Utiliser une image PHP officielle avec Apache
FROM php:8.2-apache

# Activer le module mod_rewrite pour les URLs propres
RUN a2enmod rewrite

# Installer les dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Définir le répertoire racine du serveur web
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le projet
COPY --chown=www-data:www-data . .

# Donner les permissions appropriées
RUN chown -R www-data:www-data /var/www/html/ && \
    chmod -R 755 /var/www/html/

# Exposer le port 80
EXPOSE 80

CMD ["apache2-foreground"]
