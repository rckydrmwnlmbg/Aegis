<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionTemplate extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'category',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(InspectionTemplateItem::class, 'template_id')->orderBy('order_index');
    }

    public function creator()
    {
        return $this->belongsTo(AppUser::class, 'created_by');
    }
}
