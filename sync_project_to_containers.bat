@echo off
setlocal
@echo off
setlocal

rem Se placer dans le dossier du script (racine du projet)
cd /d "%~dp0"

echo ===== Synchronisation du projet vers les conteneurs =====
echo.
echo Les dossiers locaux ./BackOffice et ./FrontOffice sont deja
echo montes dans les conteneurs via des volumes Docker.
echo Les modifications de fichiers (PHP/HTML/CSS/JS, etc.) sont
echo donc visibles immediatement dans les conteneurs.
echo.
echo Cette commande met a jour les dependances PHP dans les conteneurs
echo (par ex. apres modification de composer.json).

echo.
echo --- BackOffice : composer install dans le conteneur ---
docker exec -w /var/www/html zavamisy-back-office composer install --no-dev --optimize-autoloader --no-interaction

echo.
echo --- FrontOffice : composer install dans le conteneur ---
docker exec -w /var/www/html zavamisy-front-office composer install --no-dev --optimize-autoloader --no-interaction

echo.
echo Synchronisation terminee (si aucune erreur n'est apparue ci-dessus).
echo Verifiez que les conteneurs sont bien en cours d'execution.

endlocal
pause