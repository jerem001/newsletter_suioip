<?php

declare(strict_types=1);

namespace Modules\Users\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Users\Database\Factories\UserModelFactory;
use Spatie\Permission\Traits\HasRoles;

final class UserModel extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'users';

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'auth_strategy',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    protected static function newFactory(): UserModelFactory
    {
        return UserModelFactory::new();
    }
}
