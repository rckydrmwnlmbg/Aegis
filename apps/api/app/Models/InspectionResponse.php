<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResponse extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'inspection_id',
        'template_item_id',
        'response_value',
        'response_boolean',
        'attachment_id',
    ];

    protected $casts = [
        'response_boolean' => 'boolean',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class, 'inspection_id');
    }

    public function templateItem()
    {
        return $this->belongsTo(InspectionTemplateItem::class, 'template_item_id');
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class, 'attachment_id');
    }
}
