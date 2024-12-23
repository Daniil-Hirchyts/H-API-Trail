# H-API Trail — livraison Docker

## Dépôt Git

Cette application réunit le client Vue, l’API d’événements et l’annuaire. Voir la [présentation du projet](../README.md).

## Récapitulatif de l'investissement des membres

- **Marius** : Front (50%), API (10%), Deploiement (40%)
- **Daniil** : Front (70%), API (30%)
- **Clément** : Front (30%), API (60%), Deploiement (10%)

## Adresses de déploiement utilisées en 2024

- **Annuaire** : [https://webinfo.iutmontp.univ-montp2.fr/~garroc/annuaire/public](https://webinfo.iutmontp.univ-montp2.fr/~garroc/annuaire/public)
- **Front** : [https://webinfo.iutmontp.univ-montp2.fr/~garroc/front](https://webinfo.iutmontp.univ-montp2.fr/~garroc/front)
- **API** : [https://webinfo.iutmontp.univ-montp2.fr/~garroc/trail/apiTrail/public/api](https://webinfo.iutmontp.univ-montp2.fr/~garroc/trail/apiTrail/public/api)

> Nous n'avons pas réussi à déployer l'annuaire correctement sur le serveur de l'IUT. Il y a des erreurs de paths que nous n'avons réussi à corriger. Cependant, il tourne sans problème en local.

## URL avec docker compose

- **Front** : [http://localhost:5050](http://localhost:5050)
- **API** : [http://localhost:8080](http://localhost:8080)
- **Annuaire** : [http://localhost:5051](http://localhost:5051)

> Il se peut que refresh token ne fonctionne pas. Dans ce cas, il faut supprimer les cookies du front car d'anciens cookies peuvent interférer avec les nouveaux.

## Bases de données

### Annuaire

- **Type** : PostgreSQL
- **Database** : app
- **User** : app
- **Password** : password
- **Port** : 5432

### API

- **Type** : MySQL
- **Database** : db
- **User** : root
- **Password** : root
- **Port** : 3306

## Indications supplémentaires pour tester l'application en local

Pour lancer et tester l'application en local, voici les étapes spécifiques à suivre, outre les classiques `composer install` et `npm install` pour les projets, ainsi que la configuration et génération de la base de données :

1. Clonez le dépôt Git :
   ```bash
   git clone https://github.com/Daniil-Hirchyts/H-API-Trail.git
   cd H-API-Trail/submission
    ```
2. Exécutez le script run.sh situé à la racine du projet pour démarrer les services nécessaires :
    ```bash
    ./run.sh
    ```
> **Note** : Le lancement des services peut prendre un certain temps car les entrypoints installent les dépendances. Le script `run.sh` tente aussi de copier des fichiers d’environnement vers un dossier `app/` absent de cette arborescence ; les fichiers d’environnement des applications se trouvent dans leurs propres dossiers sous `shared/`.
   
## Peuplement de la base de données

Les scripts [script_sql_annuaire.sql](script_sql_annuaire.sql) et [script_sql_api.sql](script_sql_api.sql) fournissent les données à importer dans les bases locales correspondantes.

## API

### Endpoints de l'API

Ci-dessous, vous trouverez une liste des routes disponibles dans l'API. Elle prend en charge les méthodes HTTP standards (GET, POST, PATCH, DELETE) pour faciliter les opérations CRUD sur les ressources.

Vous pouvez également utiliser [API Platform](http://localhost:8080/api).

### Authentification

- **Enregistrer un nouvel utilisateur**
    - `POST /api/auth/register`
- **Connexion**
    - `POST /api/auth/login`
- **Déconnexion**
    - `POST /api/token/invalidate`

### Utilisateurs

- **Récupérer tous les utilisateurs**
    - `GET /api/utilisateurs`
- **Récupérer un utilisateur par ID**
    - `GET /api/utilisateurs/{id}`
- **Mettre à jour un utilisateur par ID**
    - `PATCH /api/utilisateurs/{id}`
- **Supprimer un utilisateur par ID**
    - `DELETE /api/utilisateurs/{id}`
- **Changer le rôle d’un utilisateur**
    - `PATCH /api/utilisateurs/{id}/changeRole`

### Événements

- **Récupérer tous les événements**
    - `GET /api/events`
- **Récupérer un événement par ID**
    - `GET /api/events/{id}`
- **Créer un nouvel événement**
    - `POST /api/events`
- **Mettre à jour un événement par ID**
    - `PATCH /api/events/{id}`
- **Supprimer un événement par ID**
    - `DELETE /api/events/{id}`
- **Récupérer les événements d'un utilisateur**
    - `GET /api/utilisateurs/{idOrga}/events`

### Participations

- **Récupérer toutes les participations**
    - `GET /api/participations`
- **Récupérer une participation par ID**
    - `GET /api/participations/{id}`
- **Créer une nouvelle participation**
    - `POST /api/participations`
- **Récupérer les participations d'un utilisateurs**
    - `GET /api/utilisateurs/{idUser}/participations`
- **Supprimer une participation spécifique d'un utilisateur**
    - `DELETE /api/participations/{id}`
- **Récupérer les participations d'un événement**
    - `GET /api/events/{idEvent}/participations`