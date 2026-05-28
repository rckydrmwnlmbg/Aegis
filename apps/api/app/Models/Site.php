<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasUuids, SoftDeletes, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'organization_unit_id',
        'project_id'
    ];

    public function organizationUnit()
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
