<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

class PtwDocument extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'id',
        'job_title',
        'location',
        'work_type',
        'status',
        'applicant_id',
        'assessor_id',
        'manager_id',
    ];

    public function applicant()
    {
        return $this->belongsTo(AppUser::class, 'applicant_id');
    }

    public function assessor()
    {
        return $this->belongsTo(AppUser::class, 'assessor_id');
    }

    public function manager()
    {
        return $this->belongsTo(AppUser::class, 'manager_id');
    }

    public function jsaSteps()
    {
        return $this->hasMany(JsaStep::class);
    }
}
