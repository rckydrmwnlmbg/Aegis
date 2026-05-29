<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JsaHazard extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $guarded = ['id'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(JsaTask::class, 'jsa_task_id');
    }

    public function controls(): HasMany
    {
        return $this->hasMany(JsaControl::class, 'hazard_id');
    }
}
