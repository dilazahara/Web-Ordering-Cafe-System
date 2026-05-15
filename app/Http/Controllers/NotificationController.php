<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NotificationController extends Controller
{
    // ══════════════════════════════════════════════════════
    // SSE STREAM — satu endpoint per role
    // ══════════════════════════════════════════════════════

    /**
     * Server-Sent Events stream.
     * Client connect ke sini, server push data tiap 4 detik.
     */
    public function stream(): StreamedResponse
    {
        $role = Auth::user()->role;

        $response = new StreamedResponse(function () use ($role) {
            // Matikan output buffering agar data langsung terkirim
            while (ob_get_level() > 0) {
                ob_end_flush();
            }

            $lastId = Notification::forRole($role)->latest()->value('id') ?? 0;

            $iteration = 0;

            while (true) {
                // Cek koneksi masih aktif
                if (connection_aborted()) {
                    break;
                }

                // Ambil notif baru sejak lastId
                $notifs = Notification::forRole($role)
                    ->where('id', '>', $lastId)
                    ->orderBy('id')
                    ->get();

                if ($notifs->isNotEmpty()) {
                    $lastId = $notifs->last()->id;

                    $data = $notifs->map(fn($n) => [
                        'id'           => $n->id,
                        'type'         => $n->type,
                        'title'        => $n->title,
                        'message'      => $n->message,
                        'queue_number' => $n->queue_number,
                        'order_id'     => $n->order_id,
                        'is_read'      => $n->is_read,
                        'created_at'   => $n->created_at->diffForHumans(),
                    ]);

                    echo "data: " . json_encode(['notifications' => $data]) . "\n\n";
                    flush();
                }

                // Heartbeat tiap 30 iterasi (~2 menit) agar koneksi tidak di-drop proxy
                if ($iteration % 30 === 0) {
                    echo ": heartbeat\n\n";
                    flush();
                }

                $iteration++;
                sleep(4);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no'); // nginx
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    // ══════════════════════════════════════════════════════
    // LIST — ambil notif terbaru (polling fallback)
    // ══════════════════════════════════════════════════════

    public function index(): \Illuminate\Http\JsonResponse
    {
        $role = Auth::user()->role;

        $notifs = Notification::forRole($role)
            ->orderByDesc('id')
            ->limit(30)
            ->get()
            ->map(fn($n) => [
                'id'           => $n->id,
                'type'         => $n->type,
                'title'        => $n->title,
                'message'      => $n->message,
                'queue_number' => $n->queue_number,
                'order_id'     => $n->order_id,
                'is_read'      => $n->is_read,
                'created_at'   => $n->created_at->diffForHumans(),
            ]);

        $unreadCount = Notification::forRole($role)->unread()->count();

        return response()->json([
            'notifications' => $notifs,
            'unread_count'  => $unreadCount,
        ]);
    }

    // ══════════════════════════════════════════════════════
    // UNREAD COUNT — badge angka saja
    // ══════════════════════════════════════════════════════

    public function unreadCount(): \Illuminate\Http\JsonResponse
    {
        $role  = Auth::user()->role;
        $count = Notification::forRole($role)->unread()->count();

        return response()->json(['count' => $count]);
    }

    // ══════════════════════════════════════════════════════
    // MARK READ — tandai satu notif sudah dibaca
    // ══════════════════════════════════════════════════════

    public function markRead(int $id): \Illuminate\Http\JsonResponse
    {
        $role = Auth::user()->role;

        Notification::forRole($role)
            ->where('id', $id)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    // ══════════════════════════════════════════════════════
    // MARK ALL READ — tandai semua sudah dibaca
    // ══════════════════════════════════════════════════════

    public function markAllRead(): \Illuminate\Http\JsonResponse
    {
        $role = Auth::user()->role;

        Notification::forRole($role)
            ->unread()
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
