<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Application\DTO\LoginCredentials;
use Modules\Auth\Application\UseCases\AuthenticateLocalUserUseCase;
use Modules\Auth\Application\UseCases\RevokeCurrentTokenUseCase;
use Modules\Auth\Domain\Exceptions\AuthenticationFailedException;
use Modules\Auth\Infrastructure\Http\Requests\LoginRequest;
use Modules\Auth\Infrastructure\Http\Resources\AuthenticatedUserResource;

/**
 * @OA\Tag(name="Auth", description="Authentification locale, CAS et LDAP")
 */
final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticateLocalUserUseCase $authenticateLocalUser,
        private readonly RevokeCurrentTokenUseCase $revokeCurrentToken,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"Auth"},
     *     summary="Connexion locale (email + mot de passe)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@suioip.test"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="device_name", type="string", example="dashboard-web")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Connexion réussie"),
     *     @OA\Response(response=422, description="Identifiants invalides")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authenticateLocalUser->handle(new LoginCredentials(
                email: $request->string('email')->toString(),
                password: (string) $request->input('password'),
                deviceName: $request->input('device_name'),
            ));
        } catch (AuthenticationFailedException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return (new AuthenticatedUserResource($result))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     tags={"Auth"},
     *     summary="Révoque le token courant",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=204, description="Déconnexion réussie")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $request->user()?->currentAccessToken();

        if ($user && $token) {
            $this->revokeCurrentToken->handle((int) $user->getAuthIdentifier(), (string) $token->id);
        }

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     tags={"Auth"},
     *     summary="Retourne l'utilisateur courant",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Utilisateur courant")
     * )
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(),
        ]);
    }
}
