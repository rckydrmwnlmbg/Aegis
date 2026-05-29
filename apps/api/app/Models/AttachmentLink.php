<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class AttachmentLink extends Model {
    use HasFactory, HasUuids, BelongsToTenant;
    public $timestamps = false;
    protected $fillable = [
        'tenant_id', 'attachment_id', 'domain', 'entity_type', 'entity_id', 'linkage_type', 'linked_at', 'linked_by',
    ];
    protected $casts = [ 'linked_at' => 'datetime', ];
    public function attachment() { return $this->belongsTo(Attachment::class); }
}
