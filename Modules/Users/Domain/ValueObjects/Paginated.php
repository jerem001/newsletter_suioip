<?php

declare(strict_types=1);

namespace Modules\Users\Domain\ValueObjects;

final class Paginated
{
    /**
     * @param  array<int, mixed>  $items
     */
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $currentPage,
    ) {
    }
}
