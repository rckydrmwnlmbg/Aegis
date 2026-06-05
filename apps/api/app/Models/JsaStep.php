<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\BelongsToTenant;

class JsaStep extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'id',
        'ptw_document_id',
        'job_step',
        'potential_hazards',
        'risk_level',
        'control_measures',
    ];

    public function ptwDocument()
    {
        return $this->belongsTo(PtwDocument::class);
    }
}
