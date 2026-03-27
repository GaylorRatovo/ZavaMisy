@echo off
setlocal

rem Se placer dans le dossier du script (racine du projet)
cd /d "%~dp0"

echo ===== Arret des conteneurs ZavaMisy =====
echo.

rem Essayer d'utiliser 'docker compose' (Docker v2)
docker compose down
if errorlevel 1 (
    echo Echec avec 'docker compose', tentative avec 'docker-compose'...
    docker-compose down
)

echo.
echo Conteneurs arretes (si aucune erreur n'est apparue ci-dessus).

endlocal
pause