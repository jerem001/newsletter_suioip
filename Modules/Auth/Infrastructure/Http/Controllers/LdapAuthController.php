<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use LdapRecord\Container;
use LdapRecord\Models\ModelNotFoundException;
use Modules\Auth\Application\UseCases\AuthenticateExternalUserUseCase;
use Modules\Auth\Domain\Exceptions\AuthenticationFailedException;
use Modules\Auth\Infrastructure\Http\Requests\LdapLoginRequest;
use Modules\Auth\Infrastructure\Http\Resources\AuthenticatedUserResource;

/**
 * @OA\Tag(name="Auth - LDAP", description="Authentification via annuaire LDAP/Active Directory")
 */
final class LdapAuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateExternalUserUseCase $authenticateExternalUser,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/ldap/login",
     *     tags={"Auth - LDAP"},
     *     summary="Bind LDAP puis provisioning local (JIT)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="jdupont"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Connexion LDAP réussie"),
     *     @OA\Response(response=422, description="Identifiants invalides"),
     *     @OA\Response(response=503, description="LDAP désactivé sur ce serveur")
     * )
     */
    public function login(LdapLoginRequest $request): JsonResponse
    {
        if (! config('ldap.enabled')) {
            return response()->json(['message' => "L'authentification LDAP n'est pas activée."], 503);
        }

        $connection = Container::getConnection(config('ldap.default'));

        try {
            $entry = $connection->query()
                ->where('uid', '=', $request->string('username')->toString())
                ->orWhere('sAMAccountName', '=', $request->string('username')->toString())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Identifiants invalides.'], 422);
        }

        $bound = $connection->auth()->attempt(
            $entry->getDn(),
            (string) $request->input('password')
        );

        if (! $bound) {
            return response()->json(['message' => 'Identifiants invalides.'], 422);
        }

        try {
            $result = $this->authenticateExternalUser->handle(
                email: (string) ($entry->getFirstAttribute('mail') ?? ''),
                name: (string) ($entry->getFirstAttribute('cn') ?? $request->string('username')->toString()),
            );
        } catch (AuthenticationFailedException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return (new AuthenticatedUserResource($result))->response();
    }
}
