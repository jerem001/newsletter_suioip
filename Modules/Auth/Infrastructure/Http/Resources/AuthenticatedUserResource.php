<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Application\DTO\AuthResult;

/**
 * @mixin AuthResult
 */
final class AuthenticatedUserResource extends JsonResource
{
    public function __construct(private readonly AuthResult $result)
    {
        parent::__construct($result);
    }

    public function toArray(Request $request): array
    {
        $user = $this->result->user;

        return [
            'user' => [
                'id' => $user->id(),
                'name' => $user->name(),
                'email' => $user->email()->value(),
                'strategy' => $user->strategy()->value,
                'roles' => $user->roles(),
            ],
            'token' => $this->result->token,
            'token_type' => $this->result->tokenType,
        ];
    }
}
