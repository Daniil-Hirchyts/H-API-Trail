#!/bin/bash

set -e

APP_DIR="./app"

echo "Copie de .env.test vers .env..."
if [ -f "$APP_DIR/.env.test" ]; then
    cp "$APP_DIR/.env.test" "$APP_DIR/.env"
else
    echo "Le fichier .env.test n'existe pas dans $APP_DIR."
    exit 1
fi

echo "Installation des dépendances Composer..."
if [ -d "$APP_DIR" ]; then
    cd "$APP_DIR"
    composer install
    cd ..
else
    echo "Le dossier $APP_DIR n'existe pas."
    exit 1
fi

echo "Démarrage de Docker Compose avec build..."
# run docker compose in App directory
cd "$APP_DIR" && docker compose up -d --build

echo "Installation terminée avec succès !"
