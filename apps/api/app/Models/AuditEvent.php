<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class AuditEvent extends Model
{
    use HasUuids, BelongsToTenant;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'tenant_id',
        'domain',
        'entity_type',
        'entity_id',
        'action_type',
        'actor_id',
        'actor_type',
        'occurred_at',
        'correlation_id',
        'metadata_json'
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'metadata_json' => 'array'
    ];
}
