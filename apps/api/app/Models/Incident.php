<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Incident extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'status',
        'title',
        'summary',
        'metadata',
        'ai_confidence_score',
        'incident_number',
        'incident_type',
        'classification_id',
        'occurred_at',
        'reported_at',
        'reported_by',
        'location_reference',
        'project_reference',
        'contractor_reference_id',
        'severity_status',
        'current_owner_id',
        'created_by',
        'updated_by',
        'version',
    ];

    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime',
        'reported_at' => 'datetime',
    ];
}
