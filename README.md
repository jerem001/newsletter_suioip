# Newsletter Suioip

> Smart Mailer Pro — édition **open source**.

[![CI/CD](https://github.com/jerem001/newsletter_suioip/actions/workflows/ci.yml/badge.svg)](https://github.com/jerem001/newsletter_suioip/actions/workflows/ci.yml)
![Version](https://img.shields.io/badge/version-0.1.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## Stack technique

| Domaine | Choix |
|---|---|
| Backend | Laravel 12, PHP 8.3 |
| Architecture | DDD + hexagonale légère, modules (`nwidart/laravel-modules`) |
| Frontend | Vue 3 (dépôt/couche séparée) |
| API | REST, versionnée (`/api/v1`), documentée OpenAPI 3 |
| Authentification | Locale, CAS (SSO), LDAP/Active Directory |
| Base de données | PostgreSQL 16 |
| Déploiement | Docker, GitHub Actions (CI/CD) |
| Tests | PHPUnit + Pest (couverture min. 80%) |
| Documentation | MkDocs (Material) |

## Sprint v0.1 — Back-office

- ✅ **Authentification** multi-stratégie (Locale / CAS / LDAP) avec JIT provisioning
- ✅ **Gestion des utilisateurs** (CRUD, rôles/permissions via Spatie)
- ✅ **Dashboard** (statistiques globales)

## Démarrage rapide

```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

API disponible sur `http://localhost:8080/api`. Détails complets : voir [`docs/docs/getting-started.md`](docs/docs/getting-started.md).

## Documentation

- 📖 Documentation complète (MkDocs) : dossier [`docs/`](docs/)
- 📑 Spécification OpenAPI : [`openapi/openapi.yaml`](openapi/openapi.yaml)
- 🧱 Architecture DDD/hexagonale : [`docs/docs/architecture.md`](docs/docs/architecture.md)
- 🔐 Authentification (Locale/CAS/LDAP) : [`docs/docs/auth.md`](docs/docs/auth.md)
- 📝 Historique des versions : [`CHANGELOG.md`](CHANGELOG.md)

## Structure du dépôt

```
Modules/
├── Auth/          # Authentification (Locale, CAS, LDAP)
├── Users/          # Gestion des utilisateurs, rôles & permissions
└── Dashboard/      # Statistiques et vue d'ensemble
docker/             # Dockerfiles, config Nginx/PHP
docs/               # Documentation MkDocs
openapi/             # Spécification OpenAPI de référence
```

## Contribuer

Voir [`docs/docs/contributing.md`](docs/docs/contributing.md).

## Licence

MIT — voir [`LICENSE`](LICENSE).
