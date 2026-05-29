<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CorrectiveAction extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'capa_number',
        'action_type',
        'title',
        'description',
        'source_id',
        'source_type',
        'owner_id',
        'site_id',
        'project_id',
        'due_date',
        'status',
        'version'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the parent source model (Incident, HazardObservation, AuditFinding, etc.)
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'owner_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CorrectiveActionUpdate::class, 'corrective_action_id');
    }
}
