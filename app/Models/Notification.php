<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'target_role',
        'title',
        'message',
        'order_id',
        'queue_number',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ── Helper statis untuk kirim notif ──────────────────

    /**
     * Kirim notifikasi ke satu atau beberapa role.
     */
    public static function kirim(
        string|array $roles,
        string $type,
        string $title,
        string $message,
        ?Order $order = null
    ): void {

        foreach ((array) $roles as $role) {

            static::create([

                'type'         => $type,
                'target_role'  => $role,
                'title'        => $title,
                'message'      => $message,
                'order_id'     => $order?->id,
                'queue_number' => $order?->queue_number,
                'is_read'      => false,

            ]);
        }
    }

    // ── Scope helpers ─────────────────────────────────────

    public function scopeForRole(
        Builder $query,
        string $role
    )
    {
        return $query->where(
            'target_role',
            $role
        );
    }

    public function scopeUnread(
        Builder $query
    )
    {
        return $query->where(
            'is_read',
            false
        );
    }
}
