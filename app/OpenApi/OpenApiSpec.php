<?php

declare(strict_types=1);

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="Newsletter Suioip API",
 *     version="0.1.0",
 *     description="API REST du back-office Newsletter Suioip — Smart Mailer Pro open source. Sprint v0.1 : Authentification (Locale/CAS/LDAP), Gestion des utilisateurs, Dashboard.",
 *     @OA\Contact(name="Suioip Contributors")
 * )
 * @OA\Server(url="/", description="Serveur courant")
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="API Token"
 * )
 */
final class OpenApiSpec
{
    // Fichier porteur des annotations globales uniquement.
}
