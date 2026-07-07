# Newsletter Suioip

**Smart Mailer Pro — édition open source.**

Plateforme d'envoi de newsletters avec une architecture moderne :

- **Backend** : Laravel 12, DDD, architecture hexagonale légère, modules (`nwidart/laravel-modules`)
- **Frontend** : Vue 3 (dépôt séparé / à venir)
- **API** : REST, documentée en OpenAPI 3
- **Authentification** : Locale, CAS (SSO), LDAP/Active Directory
- **BDD** : PostgreSQL
- **Déploiement** : Docker, CI/CD GitHub Actions
- **Tests** : PHPUnit + Pest

## Sprint v0.1 (back-office)

| Fonctionnalité   | Statut |
|------------------|--------|
| Authentification | ✅ Locale + CAS + LDAP (JIT provisioning) |
| Gestion des utilisateurs | ✅ CRUD, rôles/permissions (Spatie) |
| Dashboard        | ✅ Statistiques globales |

Voir la [Fiche de démarrage rapide](getting-started.md) pour lancer l'environnement Docker.
