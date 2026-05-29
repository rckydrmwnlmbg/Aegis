<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermitApproval extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'decided_at' => 'datetime',
    ];

    public function permit(): BelongsTo
    {
        return $this->belongsTo(PermitToWork::class, 'permit_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'approver_id');
    }
}
