<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermitToWork extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function permitWorkers(): HasMany
    {
        return $this->hasMany(PermitWorker::class, 'permit_id');
    }

    public function permitType(): BelongsTo
    {
        return $this->belongsTo(PermitType::class);
    }

    public function jsa(): BelongsTo
    {
        return $this->belongsTo(Jsa::class, 'jsa_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'requested_by');
    }

    public function currentOwner(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'current_owner_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(PermitApproval::class, 'permit_id');
    }
}
