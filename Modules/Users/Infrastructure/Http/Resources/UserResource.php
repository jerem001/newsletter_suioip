<?php

declare(strict_types=1);

namespace Modules\Users\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Users\Domain\Entities\UserEntity;

/**
 * @mixin UserEntity
 */
final class UserResource extends JsonResource
{
    public function __construct(private readonly UserEntity $entity)
    {
        parent::__construct($entity);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->entity->id(),
            'name' => $this->entity->name(),
            'email' => $this->entity->email()->value(),
            'active' => $this->entity->isActive(),
            'roles' => $this->entity->roles(),
            'created_at' => $this->entity->createdAt()?->format(DATE_ATOM),
        ];
    }
}
