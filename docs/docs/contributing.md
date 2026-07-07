# Contribuer

## Style de code

- PSR-12 + règles Laravel, appliquées via `laravel/pint` (`composer lint`).
- `declare(strict_types=1);` obligatoire dans tout nouveau fichier PHP.
- Les Value Objects et entités de domaine ne doivent importer **aucune**
  classe `Illuminate\*`.

## Tests

- Toute nouvelle règle métier → un test **Unit** dans `Modules/{Module}/Tests/Unit`.
- Tout nouvel endpoint → un test **Feature** dans `Modules/{Module}/Tests/Feature`.
- Couverture minimale exigée en CI : **80%** (`composer test:coverage`).

## Ajouter un module

```bash
docker compose exec app php artisan module:make MonModule
```

Puis respecter l'arborescence `Domain / Application / Infrastructure` déjà
en place dans `Auth`, `Users` et `Dashboard`.

## Versionnage (SemVer)

- `MAJOR.MINOR.PATCH` — voir [`VERSION`](https://github.com/jerem001/newsletter_suioip/blob/main/VERSION) et [`CHANGELOG.md`](changelog.md).
- Toute PR mergée sur `main` doit être accompagnée d'une entrée de changelog.

## Commits

Format recommandé (Conventional Commits) : `feat(auth): ajoute le provisioning LDAP`,
`fix(users): corrige la pagination`, `docs: met à jour le guide de démarrage`.
