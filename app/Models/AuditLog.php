<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'actor_id',
        'action',
        'entity_type',
        'entity_id',
        'meta',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // Relación: un log pertenece a un actor (usuario)
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    // Método helper para crear logs fácilmente
    public static function log(string $action, ?int $actorId, string $entityType, ?int $entityId = null, array $meta = []): self
    {
        return self::create([
            'actor_id' => $actorId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'meta' => $meta,
            'created_at' => now(),
        ]);
    }
}
