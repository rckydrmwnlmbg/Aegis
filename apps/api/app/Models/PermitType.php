<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitType extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'configuration_json' => 'array',
    ];
}
