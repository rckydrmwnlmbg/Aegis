<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class AiRun extends Model {
    use HasFactory, HasUuids, BelongsToTenant;
    protected $fillable = [
        'tenant_id', 'actor_id', 'use_case', 'provider_reference', 'workflow_domain', 'workflow_entity_id', 'payload', 'status', 'occurred_at', 'correlation_id',
    ];
    protected $casts = [ 'payload' => 'array', 'occurred_at' => 'datetime', ];
}
