# API

La spécification OpenAPI 3 est disponible :

- **Source de référence versionnée** : [`openapi/openapi.yaml`](https://github.com/jerem001/newsletter_suioip/blob/main/openapi/openapi.yaml)
- **Générée dynamiquement depuis les annotations des contrôleurs** (`l5-swagger`) :
  `php artisan l5-swagger:generate`, puis Swagger UI sur `/api/documentation`.

Les deux sources doivent rester cohérentes : la génération dynamique est la
source de vérité au runtime, le fichier statique sert de référence rapide
(review de PR, intégration front, outillage externe type Postman/Insomnia).

## Convention de nommage

- Toutes les routes sont préfixées `/api/v1/...` (versionnement d'API dès le départ).
- Authentification par jeton Bearer (Sanctum), sauf `/auth/login`, `/auth/cas/login`, `/auth/ldap/login`.
- Réponses d'erreur au format `{"message": "..."}` avec le code HTTP adapté (422, 404, 401, 503).
