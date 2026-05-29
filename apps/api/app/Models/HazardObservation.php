<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class HazardObservation extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'status',
        'title',
        'description',
        'category_name',
        'risk_score',
        'ai_confidence_score',
        'metadata',
        'hazard_number',
        'category_id',
        'severity_level',
        'observed_at',
        'observed_by',
        'location_reference',
        'contractor_reference_id',
        'owner_id',
        'version',
    ];

    protected $casts = [
        'metadata' => 'array',
        'observed_at' => 'datetime',
    ];
}
