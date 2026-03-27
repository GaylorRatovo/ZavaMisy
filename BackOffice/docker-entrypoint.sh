#!/bin/sh
set -e

cd /var/www/html

# Si vendor/autoload.php n'existe pas (par ex. aucun vendor local + volume),
# on installe automatiquement les dépendances PHP.
if [ ! -f vendor/autoload.php ]; then
  echo "[BackOffice] vendor manquant, execution de composer install..."
  composer install --no-dev --optimize-autoloader --no-interaction
fi

exec "$@"
