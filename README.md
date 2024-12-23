# H-API Trail

**Sports events, registrations and a member directory · 2024**

We developed **H-API Trail** as a team academic project for managing running and cycling events. The work combined a REST API, a Vue web client, a separate member directory and a final Docker-based submission containing the applications together.

The platform covered road running, trail running, mountain biking and road cycling. Users could browse events, manage their accounts and register for events, while organisers managed event information and participation.

## Components

| Component | Features |
| --- | --- |
| [REST API](api/) | Users, authentication, events, participation records and role-dependent operations. |
| [Web client](frontend/) | Event browsing, event details, registration, account views and organiser forms. |
| [Member directory](directory/) | Registration, searchable profiles, profile editing, comments and profile-data access. |
| [Final submission](submission/) | The assembled applications, Docker configuration, database scripts and delivery notes. |

## REST API

We implemented the backend with **Symfony 6.4**, **API Platform 4**, Doctrine and a MySQL database. Its resources represented users, events and participations.

The implementation included:

- Account registration, login and JWT-based authentication, with refresh-token support.
- User information and roles for participants, organisers and administrators.
- Event creation, retrieval, updates and deletion.
- Event dates, locations, sport categories, descriptions, capacity and prices.
- Participation registration, cancellation and event/user participation views.
- Validation, access-control logic, API state processors and database migrations.

Source: [entities](api/shared/public_html/apiTrail/src/Entity/), [state processors](api/shared/public_html/apiTrail/src/State/) and [API documentation](api/README.md).

## Vue web client

The client provided the interface for browsing events and using the API. We built event cards and detail pages, registration and login screens, a participation view, profile editing and an event-creation dialog.

Vue Router separated the authentication and application layouts. Client stores and service modules handled account state, event data and API requests. The frontend also retrieved additional profile information from the directory.

The interface used **Vue 3**, **TypeScript**, **Vite**, Tailwind CSS and reusable Radix/shadcn-vue components.

Source: [views](frontend/src/views/), [API services](frontend/src/lib/services/), [routing](frontend/src/lib/routes.ts) and [directory integration](frontend/src/lib/annuaire-api.ts).

## Member directory

The directory was a separate **Symfony 7.1** application with Twig views, Doctrine and PostgreSQL. It included account registration and login, profile search, profile pages, editing forms and account-management actions.

Profiles included contact details, a biography, a website, occupation information, an avatar, a banner and visibility settings. Comment entities and forms supported interaction on profiles, while a JSON profile endpoint exposed information to the event client.

Source: [controllers](directory/app/src/Controller/), [entities](directory/app/src/Entity/) and [forms](directory/app/src/Form/).

## Final delivery

The [submission](submission/) brought the API, client and directory together with Docker Compose configurations and SQL scripts for the two databases.

The [delivery notes](submission/README.md) cover local startup, a directory-path issue on the IUT hosting server and cookie conflicts during token refresh.

## Team

- **[Marius Brouty](https://github.com/Marius-brt)**
- **[Clément Garro](https://github.com/Clement-Garro)**
- **[Daniil Hirchyts](https://github.com/Daniil-Hirchyts)**

## Licensing

The Symfony applications declare `proprietary`, and the directory's asset package declares `UNLICENSED`. See [LICENSES.md](LICENSES.md) for the component declarations. Third-party components have their own license notices.
