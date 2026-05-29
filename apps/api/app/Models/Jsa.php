<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jsa extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(AppUser::class, 'prepared_by');
    }

    public function permit(): BelongsTo
    {
        return $this->belongsTo(PermitToWork::class, 'linked_permit_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(JsaTask::class);
    }
}
