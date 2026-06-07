<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class AppUser extends Authenticatable
{
    use HasApiTokens, HasUuids, Notifiable, BelongsToTenant, HasRoles, HasFactory;

    protected $table = 'app_users';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'external_identity_id',
        'employee_code',
        'contractor_reference_id',
        'status',
        'locale',
        'timezone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
