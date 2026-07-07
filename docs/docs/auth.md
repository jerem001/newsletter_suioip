# Authentification

Trois stratégies cohabitent, activables indépendamment via `.env` :

| Variable | Effet |
|----------|-------|
| `AUTH_DEFAULT_STRATEGY` | Stratégie mise en avant côté frontend (`local`, `cas`, `ldap`) |
| `CAS_ENABLED` | Active les routes `/api/v1/auth/cas/*` |
| `LDAP_ENABLED` | Active la route `/api/v1/auth/ldap/login` |

## Locale

`POST /api/v1/auth/login` — email + mot de passe, retourne un jeton Sanctum.

## CAS (SSO)

`GET /api/v1/auth/cas/login` — redirige vers le serveur CAS configuré
(`CAS_HOSTNAME`, `CAS_PORT`, `CAS_URI`), valide le ticket, puis effectue un
**JIT provisioning** : l'utilisateur est créé/mis à jour localement à partir
des attributs renvoyés par le CAS (mappés via `cas.cas_attribute_mapping`).

## LDAP / Active Directory

`POST /api/v1/auth/ldap/login` — effectue un *bind* LDAP avec les identifiants
fournis (via `ldaprecord-laravel`), puis JIT provisioning identique à CAS.

## JIT Provisioning

Les stratégies externes (CAS, LDAP) ne créent **jamais** de mot de passe local :
le champ `password` reste `null` pour ces comptes. Un compte provisionné en JIT
ne peut donc pas se connecter en `local` tant qu'un mot de passe n'a pas été
explicitement défini par un administrateur.

## Autorisations

Les rôles/permissions sont gérés par `spatie/laravel-permission` (guard `api`).
Permissions livrées en v0.1 : `users.viewAny`, `users.view`, `users.create`,
`users.update`, `users.delete`, `dashboard.view`.
