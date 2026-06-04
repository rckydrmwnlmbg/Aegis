<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasVectors;
use Pgvector\Laravel\Vector;

class KnowledgeDocument extends Model
{
    use HasUuids, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'title',
        'content',
        'metadata',
        'embedding',
    ];

    protected function casts(): array
    {
        $casts = [
            'metadata' => 'json',
        ];

        if (config('database.default') === 'pgsql') {
            $casts['embedding'] = Vector::class;
        }

        return $casts;
    }
}
