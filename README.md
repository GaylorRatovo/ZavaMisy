# 🚀 ZavaMisy - Projet Flight PHP

Projet multi-modules avec Flight PHP framework.

## 📋 Structure du Projet

```
ZavaMisy/
├── BackOffice/     # API et gestion administrative
├── FrontOffice/    # Interface publique
└── docker-compose.yml  # Orchestration des conteneurs
```

## 🐳 Docker - Démarrage Rapide

### Prérequis
- Docker et Docker Compose installés
- Port 8000 et 8001 disponibles

### Lancer les applications

```bash
# À partir du répertoire racine du projet
docker-compose up -d
```

### Accès aux applications

- **Front Office**: http://localhost:8000
- **Back Office API**: http://localhost:8001
- **Health Check**: http://localhost:8001/health

### Arrêter les applications

```bash
docker-compose down
```

## 🛠️ Développement Local

### Prérequis
- PHP 8.2+
- Composer
- Apache (ou serveur web compatible)

### Installation

#### BackOffice

```bash
cd BackOffice
composer install
# Configurer le serveur web pour le répertoire 'public/'
```

#### FrontOffice

```bash
cd FrontOffice
composer install
# Configurer le serveur web pour le répertoire 'public/'
```

### Variables d'environnement

Copier les fichiers `.env.example` en `.env` et configurer selon vos besoins:

```bash
cp BackOffice/.env.example BackOffice/.env
cp FrontOffice/.env.example FrontOffice/.env
```

## 📁 Structure des Fichiers

### BackOffice

- `public/index.php` - Point d'entrée de l'API
- `src/` - Contrôleurs, modèles et logique métier
- `views/` - Templates de réponse
- `.htaccess` - Réécriture d'URLs

### FrontOffice

- `public/index.php` - Point d'entrée de l'application
- `src/` - Contrôleurs et logique métier
- `views/` - Templates HTML
- `.htaccess` - Réécriture d'URLs

## 🔄 Réécriture d'URLs

Les fichiers `.htaccess` permettent de réécrire les URLs vers `index.php` pour un routage propre avec Flight PHP.

Assurez-vous que le module `mod_rewrite` est activé sur votre serveur Apache:

```bash
a2enmod rewrite
systemctl restart apache2
```

## 📦 Dépendances

- **Flight PHP** ^3.18 - Framework PHP minimaliste

Voir `composer.json` pour la liste complète des dépendances.

## 🐳 Configuration Docker

### BackOffice Dockerfile

- Image de base: `php:8.2-apache`
- Extensions: pdo, pdo_mysql
- Port: 80 (mappé à 8001 par défaut)

### FrontOffice Dockerfile

- Image de base: `php:8.2-apache`
- Extensions: pdo, pdo_mysql
- Port: 80 (mappé à 8000 par défaut)

## 🔒 .gitignore

Les fichiers suivants sont ignorés:

- `/vendor/` - Dépendances Composer
- `.env` - Variables d'environnement
- `.vscode/` et `.idea/` - Configurations d'IDE
- Fichiers temporaires et logs

## 📝 Routes de Base

### BackOffice

- `GET /` - Message de bienvenue API
- `GET /health` - Vérification de santé

### FrontOffice

- `GET /` - Page d'accueil
- `GET /api/health` - Vérification de santé

## 🔧 Personnalisation

### Ajouter une route

#### BackOffice (API)

```php
Flight::route('GET /users/@id', function($id) {
    return Flight::json(['id' => $id]);
});
```

#### FrontOffice

```php
Flight::route('GET /products/@id', function($id) {
    return Flight::view('product', ['id' => $id]);
});
```

### Ajouter un contrôleur

1. Créer le fichier dans `src/Controllers/`
2. Importer et utiliser dans `public/index.php`

## 📚 Ressources

- [Flight PHP Documentation](https://flightphp.com/)
- [Docker Documentation](https://docs.docker.com/)

## ✅ Checklist de Démarrage

- [x] Dockerfile créé pour chaque application
- [x] docker-compose.yml configuré
- [x] index.php créé comme point d'entrée
- [x] .gitignore et .dockerignore configurés
- [x] .htaccess pour réécriture d'URLs
- [x] Views de base créées
- [x] .env.example fourni

## 📞 Support

Pour toute question ou issue, consultez la documentation de Flight PHP ou Docker.
