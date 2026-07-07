<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in(
        'Feature',
        '../Modules/Auth/Tests/Feature',
        '../Modules/Users/Tests/Feature',
        '../Modules/Dashboard/Tests/Feature',
    );

pest()->extend(TestCase::class)
    ->in(
        'Unit',
        '../Modules/Auth/Tests/Unit',
        '../Modules/Users/Tests/Unit',
        '../Modules/Dashboard/Tests/Unit',
    );
