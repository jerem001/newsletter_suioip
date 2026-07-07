# Changelog

Toutes les modifications notables de ce projet sont documentées ici.

Le format suit [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/),
et ce projet adhère au [Semantic Versioning](https://semver.org/lang/fr/).

## [0.1.0] - 2026-07-07

### Ajouté

- Architecture initiale : Laravel 12, DDD, hexagonale légère, modules (`nwidart/laravel-modules`).
- Module **Auth** : authentification Locale (Sanctum), CAS (SSO), LDAP/Active Directory (JIT provisioning).
- Module **Users** : CRUD utilisateurs, rôles/permissions (`spatie/laravel-permission`).
- Module **Dashboard** : endpoint de statistiques globales.
- Documentation OpenAPI 3 (annotations `l5-swagger` + spec statique `openapi/openapi.yaml`).
- Environnement Docker complet (PHP-FPM 8.3, Nginx, PostgreSQL 16, Redis, Mailhog, OpenLDAP optionnel).
- CI/CD GitHub Actions : lint (Pint), tests (Pest/PHPUnit + couverture), build image Docker, déploiement SSH sur serveur de test.
- Documentation MkDocs (Material) : démarrage rapide, architecture, authentification, API, contribution.
- Suite de tests Pest dès le premier sprint (couverture Domain + Application + Feature HTTP).

[0.1.0]: https://github.com/jerem001/newsletter_suioip/releases/tag/v0.1.0
