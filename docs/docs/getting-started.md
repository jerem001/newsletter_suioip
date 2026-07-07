# Démarrage rapide

## Prérequis

- Docker + Docker Compose
- Make (optionnel, confort)

## Installation

```bash
git clone https://github.com/jerem001/newsletter_suioip.git
cd newsletter_suioip
cp .env.example .env

docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

L'API est alors disponible sur `http://localhost:8080/api`.

## Comptes de test (après le seed)

| Email | Mot de passe | Rôle |
|-------|---------------|------|
| admin@suioip.test | password | admin |

## Lancer les tests

```bash
docker compose exec app composer test
# ou avec couverture (seuil 80% minimum) :
docker compose exec app composer test:coverage
```

## Lancer le linter (Pint, PSR-12 + règles Laravel)

```bash
docker compose exec app composer lint
```

## Activer LDAP en local (optionnel)

```bash
docker compose --profile ldap up -d openldap
```

Puis renseigner `LDAP_ENABLED=true` et les variables `LDAP_*` dans `.env`.

## Générer la doc OpenAPI (Swagger UI)

```bash
docker compose exec app php artisan l5-swagger:generate
```

Disponible ensuite sur `http://localhost:8080/api/documentation`.
