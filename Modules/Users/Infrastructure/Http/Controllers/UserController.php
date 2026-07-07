<?php

declare(strict_types=1);

namespace Modules\Users\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Users\Application\DTO\CreateUserData;
use Modules\Users\Application\DTO\UpdateUserData;
use Modules\Users\Application\UseCases\CreateUserUseCase;
use Modules\Users\Application\UseCases\DeleteUserUseCase;
use Modules\Users\Application\UseCases\GetUserUseCase;
use Modules\Users\Application\UseCases\ListUsersUseCase;
use Modules\Users\Application\UseCases\UpdateUserUseCase;
use Modules\Users\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Users\Domain\Exceptions\UserNotFoundException;
use Modules\Users\Infrastructure\Http\Requests\StoreUserRequest;
use Modules\Users\Infrastructure\Http\Requests\UpdateUserRequest;
use Modules\Users\Infrastructure\Http\Resources\UserResource;

/**
 * @OA\Tag(name="Users", description="Gestion des utilisateurs du back-office")
 */
final class UserController extends Controller
{
    public function __construct(
        private readonly ListUsersUseCase $listUsers,
        private readonly GetUserUseCase $getUser,
        private readonly CreateUserUseCase $createUser,
        private readonly UpdateUserUseCase $updateUser,
        private readonly DeleteUserUseCase $deleteUser,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Liste paginée des utilisateurs",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Liste des utilisateurs")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $paginated = $this->listUsers->handle(
            perPage: (int) $request->integer('per_page', 15),
            search: $request->string('search')->value() ?: null,
        );

        return response()->json([
            'data' => UserResource::collection($paginated->items),
            'meta' => [
                'total' => $paginated->total,
                'per_page' => $paginated->perPage,
                'current_page' => $paginated->currentPage,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Crée un utilisateur",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=201, description="Utilisateur créé"),
     *     @OA\Response(response=422, description="Email déjà utilisé / validation")
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->createUser->handle(new CreateUserData(
                name: $request->string('name')->toString(),
                email: $request->string('email')->toString(),
                password: $request->input('password'),
                roles: $request->input('roles', []),
                active: $request->boolean('active', true),
            ));
        } catch (EmailAlreadyTakenException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{user}",
     *     tags={"Users"},
     *     summary="Détail d'un utilisateur",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Utilisateur"),
     *     @OA\Response(response=404, description="Introuvable")
     * )
     */
    public function show(int $user): JsonResponse
    {
        try {
            $entity = $this->getUser->handle($user);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        return (new UserResource($entity))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{user}",
     *     tags={"Users"},
     *     summary="Met à jour un utilisateur",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Utilisateur mis à jour"),
     *     @OA\Response(response=404, description="Introuvable"),
     *     @OA\Response(response=422, description="Validation")
     * )
     */
    public function update(UpdateUserRequest $request, int $user): JsonResponse
    {
        try {
            $entity = $this->updateUser->handle(new UpdateUserData(
                id: $user,
                name: $request->input('name'),
                email: $request->input('email'),
                roles: $request->input('roles'),
                active: $request->has('active') ? $request->boolean('active') : null,
            ));
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (EmailAlreadyTakenException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return (new UserResource($entity))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{user}",
     *     tags={"Users"},
     *     summary="Supprime (soft delete) un utilisateur",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Supprimé"),
     *     @OA\Response(response=404, description="Introuvable")
     * )
     */
    public function destroy(int $user): JsonResponse
    {
        try {
            $this->deleteUser->handle($user);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        return response()->json(null, 204);
    }
}
