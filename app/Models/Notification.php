<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     *
     * @param string|array $roles   e.g. 'kasir' atau ['kasir','admin']
     * @param string       $type    order_new | order_confirmed | order_done | order_delivered
     * @param string       $title
     * @param string       $message
     * @param Order|null   $order
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

    public function scopeForRole($query, string $role)
    {
        return $query->where('target_role', $role);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
