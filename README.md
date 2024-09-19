# SAEAnnuaire

## Initialiser le projet

En mode dev :

```bash
./build.sh
```

Puis soit dans docker, soit en local (dans le dossier /app) :

```bash
npm run watch
```

En mode prod :

```bash
./build.sh prod
```

> L'image dockerfile du projet execute les migrations dans le script entrypoint.sh