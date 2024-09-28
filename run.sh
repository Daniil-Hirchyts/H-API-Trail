#!/bin/bash

if [ "$1" = "prod" ]; then
    echo "Build de l'image Docker en mode production..."
    cp .env.prod ./app/.env
    cp .env.prod ./app/.env.dev
    docker compose -f compose.yaml -f compose.override.yaml up -d --build
    open http://localhost
else
    echo "Build de l'image Docker en mode développement..."
    cp .env.dev ./app/.env
    cp .env.dev ./app/.env.dev
    docker compose -f compose.yaml up -d --build
    open http://localhost:8085
fi
