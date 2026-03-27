@echo off
setlocal

rem Se placer dans le dossier du script (racine du projet)
cd /d "%~dp0"

echo ===== Demarrage des conteneurs ZavaMisy (build + up) =====
echo.

rem Essayer d'utiliser 'docker compose' (Docker v2)
docker compose up -d --build
if errorlevel 1 (
    echo Echec avec 'docker compose', tentative avec 'docker-compose'...
    docker-compose up -d --build
)

echo.
echo Conteneurs demarres (si aucune erreur n'est apparue ci-dessus).

endlocal
pause