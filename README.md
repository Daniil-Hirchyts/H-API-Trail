# SAEAnnuaire

## Initialiser le projet

> Le lancement du server peut être un peu long car il faut attendre que la base de données soit healthy et que
> l'entrypoint est fini de s'exécuter

En mode dev :

```bash
./run.sh
```

En mode prod :

```bash
./run.sh prod
```

> L'image dockerfile du projet execute les migrations dans le script entrypoint.sh