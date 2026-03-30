# Utiliser une image PHP officielle avec Apache
FROM php:8.2-apache

# Activer les modules Apache nécessaires
# - rewrite: URLs propres
# - deflate: compression gzip
# - expires: cache navigateur
# - headers: headers HTTP personnalisés
RUN a2enmod rewrite deflate expires headers

# Installer curl pour les appels API
RUN apt-get update && apt-get install -y \
    curl \
    && rm -rf /var/lib/apt/lists/*

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
