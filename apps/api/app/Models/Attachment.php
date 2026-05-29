<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class Attachment extends Model {
    use HasFactory, HasUuids, BelongsToTenant;
    protected $fillable = [
        'tenant_id', 'storage_provider', 'storage_key', 'media_type', 'size_bytes', 'checksum', 'created_by', 'status',
    ];
    public function links() { return $this->hasMany(AttachmentLink::class); }
}
