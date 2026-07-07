<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Auth\Application\UseCases\AuthenticateExternalUserUseCase;
use Modules\Auth\Domain\Exceptions\AuthenticationFailedException;
use Modules\Auth\Infrastructure\Http\Resources\AuthenticatedUserResource;
use Subfission\Cas\CasFacade as Cas;

/**
 * @OA\Tag(name="Auth - CAS", description="Authentification via serveur CAS central")
 */
final class CasAuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateExternalUserUseCase $authenticateExternalUser,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/cas/login",
     *     tags={"Auth - CAS"},
     *     summary="Redirige vers le serveur CAS puis valide le ticket",
     *     @OA\Response(response=200, description="Connexion CAS réussie"),
     *     @OA\Response(response=503, description="CAS désactivé sur ce serveur")
     * )
     */
    public function login(): JsonResponse
    {
        if (! config('cas.enabled')) {
            return response()->json(['message' => "L'authentification CAS n'est pas activée."], 503);
        }

        Cas::authenticate();

        $email = (string) Cas::user();
        $attributes = Cas::getAttributes() ?? [];

        try {
            $result = $this->authenticateExternalUser->handle(
                email: $attributes[config('cas.cas_attribute_mapping.email')] ?? $email,
                name: $attributes[config('cas.cas_attribute_mapping.name')] ?? $email,
            );
        } catch (AuthenticationFailedException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return (new AuthenticatedUserResource($result))->response();
    }

    public function logout(): JsonResponse
    {
        Cas::logout();

        return response()->json(null, 204);
    }
}
