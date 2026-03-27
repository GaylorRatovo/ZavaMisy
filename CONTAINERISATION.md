# Conteneurisation du projet ZavaMisy

Ce document décrit comment fonctionne la conteneurisation Docker du projet, la gestion des dépendances PHP (Composer) et des dossiers `vendor`, ainsi que le rôle de chaque service.

## 1. Vue d'ensemble

Le projet est composé de trois services Docker principaux, définis dans `docker-compose.yml` :

- **back-office** : API BackOffice en PHP/Flight, exposée sur le port `8001`.
- **front-office** : interface FrontOffice en PHP/Flight, exposée sur le port `8000`.
- **db** : base de données PostgreSQL utilisée uniquement par le BackOffice.

Tous les services sont connectés au même réseau Docker `zavamisy-network`.

## 2. Services et ports

### 2.1 BackOffice

- Service : `back-office`
- Image : construite à partir de `BackOffice/Dockerfile`
- Port : `8001:80` (port 80 du conteneur mappé sur 8001 en local)
- Volume : `./BackOffice:/var/www/html`
- Variables d'environnement principales :
  - `APP_ENV=development`
  - `DB_DRIVER=pgsql`
  - `DB_HOST=db`
  - `DB_PORT=5432`
  - `DB_NAME=zavamisy`
  - `DB_USER=zava_user`
  - `DB_PASSWORD=secret`

### 2.2 FrontOffice

- Service : `front-office`
- Image : construite à partir de `FrontOffice/Dockerfile`
- Port : `8000:80`
- Volume : `./FrontOffice:/var/www/html`
- Variables d'environnement :
  - `APP_ENV=development`
- Le FrontOffice n'accède pas directement à la base de données.

### 2.3 Base de données PostgreSQL

- Service : `db`
- Image : `postgres:16-alpine`
- Port : **non exposé** à l'extérieur (accessible uniquement par les autres conteneurs)
- Variables d'environnement :
  - `POSTGRES_DB=zavamisy`
  - `POSTGRES_USER=zava_user`
  - `POSTGRES_PASSWORD=secret`
- Volume persistant :
  - `pgdata:/var/lib/postgresql/data`

## 3. Dockerfile BackOffice et FrontOffice

Les deux Dockerfile (`BackOffice/Dockerfile` et `FrontOffice/Dockerfile`) sont très similaires.

### 3.1 Image de base et Apache

- Image : `php:8.2-apache`
- Activation du module de réécriture d'URL :
  - `RUN a2enmod rewrite`
- Le dossier racine Apache est réglé sur `/var/www/html/public` via la variable d'environnement `APACHE_DOCUMENT_ROOT`.

### 3.2 Extensions PHP et PostgreSQL

Les extensions nécessaires sont installées dans l'image :

```Dockerfile
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql pgsql
```

Cela active PDO + PostgreSQL dans PHP.

### 3.3 Composer et dossier `vendor`

Le Dockerfile suit le pattern classique pour optimiser l'installation des dépendances :

1. Copier `composer.json` (et éventuellement `composer.lock`) dans l'image :
   ```Dockerfile
   WORKDIR /var/www/html
   COPY composer.json composer.lock* ./
   ```
2. Installer les dépendances PHP dans `/var/www/html/vendor` :
   ```Dockerfile
   RUN composer install --no-dev --optimize-autoloader --no-interaction
   ```
3. Copier ensuite le reste du projet :
   ```Dockerfile
   COPY --chown=www-data:www-data . .
   ```

Au moment du build, les vendor sont donc créés dans l'image Docker.

## 4. Volumes et code source

Dans `docker-compose.yml`, les dossiers locaux sont montés dans les conteneurs :

- `./BackOffice:/var/www/html`
- `./FrontOffice:/var/www/html`

Conséquences :

- Le code source modifié en local (PHP, HTML, CSS, JS, etc.) est immédiatement visible dans les conteneurs.
- Les dossiers `vendor` présents localement sont partagés avec les conteneurs.
- Si les vendor sont supprimés localement, on peut les régénérer à l'intérieur des conteneurs.

## 5. Gestion des dépendances et du dossier `vendor`

### 5.1 Côté projet (local)

- Les dépendances sont déclarées dans :
  - `BackOffice/composer.json`
  - `FrontOffice/composer.json`
- Les dossiers `vendor/` sont normalement ignorés par Git (via `.gitignore`).

### 5.2 Côté conteneurs

Lors du build des images, `composer install` est exécuté dans l'image (voir section 3.3).

En environnement de développement, un script Windows permet de régénérer les vendor dans les conteneurs si nécessaire :

- `sync_project_to_containers.bat` (à la racine du projet)

Ce script exécute, pour chaque service PHP, un `composer install` dans `/var/www/html` du conteneur :

```bash
docker exec -w /var/www/html zavamisy-back-office composer install --no-dev --optimize-autoloader --no-interaction
# (FrontOffice peut être ajouté de la même manière si besoin)
```

Les vendor créés dans le conteneur apparaîtront aussi dans le dossier `vendor/` local (car le volume est partagé).

## 6. Scripts .bat fournis (Windows)

À la racine du projet, plusieurs scripts `.bat` facilitent le travail sous Windows :

- `start_containers.bat`  
  - Lance les services en buildant les images si nécessaire :
    - `docker compose up -d --build` (ou `docker-compose up -d --build`).

- `restart_containers.bat`  
  - Redémarre uniquement les conteneurs déjà créés :
    - `docker compose restart`.

- `stop_containers.bat`  
  - Arrête et supprime les conteneurs :
    - `docker compose down`.

- `sync_project_to_containers.bat`  
  - Met à jour les dépendances PHP à l'intérieur du conteneur BackOffice (et potentiellement FrontOffice) en relançant `composer install`.

## 7. Connexion PostgreSQL côté BackOffice

La connexion à PostgreSQL se fait uniquement côté BackOffice.

### 7.1 Variables d'environnement

Dans le service `back-office` de `docker-compose.yml` :

```yaml
environment:
  - APP_ENV=development
  - DB_DRIVER=pgsql
  - DB_HOST=db
  - DB_PORT=5432
  - DB_NAME=zavamisy
  - DB_USER=zava_user
  - DB_PASSWORD=secret
```

### 7.2 Classe de connexion PDO

Dans `BackOffice/src/Database.php` se trouve une classe `ZavaMisy\BackOffice\Database` qui :

- Lit les variables d'environnement `DB_*`.
- Construit un DSN PostgreSQL `pgsql:host=...;port=...;dbname=...`.
- Retourne un objet `PDO` configuré avec :
  - `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`  
  - `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`

### 7.3 Enregistrement dans Flight

Dans `BackOffice/public/index.php` :

- La classe `Database` est importée, puis la connexion PDO est enregistrée dans le conteneur Flight :
  - `Flight::set('db', Database::getConnection());`
- Les routes peuvent ensuite récupérer la connexion via :
  - `$db = Flight::get('db');`

## 8. Résumé du flux de travail

1. **Lancer les conteneurs** :
   - Exécuter `start_containers.bat` à la racine du projet.
2. **Développer le code** :
   - Modifier les fichiers dans `BackOffice/` et `FrontOffice/`. Les changements sont immédiatement reflétés dans les conteneurs grâce aux volumes.
3. **Mettre à jour les dépendances** :
   - Après avoir modifié `composer.json` ou en cas de problème avec `vendor/`, exécuter `sync_project_to_containers.bat` pour relancer `composer install` dans les conteneurs.
4. **Arrêter les conteneurs** :
   - Exécuter `stop_containers.bat` lorsque vous n'avez plus besoin de l'environnement.

Ce document doit te servir de référence rapide pour comprendre comment l'application est packagée avec Docker, comment les dépendances PHP sont gérées et comment la base de données PostgreSQL est intégrée au BackOffice.