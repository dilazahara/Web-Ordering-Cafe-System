<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        $notifications = Notification::forRole($role)
            ->latest()
            ->take(20)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => Notification::forRole($role)
                ->unread()
                ->count(),
        ]);
    }

    public function read(int $id)
    {
        $notif = Notification::findOrFail($id);

        $notif->update([
            'is_read' => true
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function readAll()
    {
        Notification::forRole(Auth::user()->role)
            ->unread()
            ->update([
                'is_read' => true
            ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function stream()
    {
        return response()->stream(function () {

            while (true) {

                $notifications = Notification::forRole(
                    Auth::user()->role
                )
                ->unread()
                ->latest()
                ->take(10)
                ->get();

                echo "data: " . json_encode([
                    'notifications' => $notifications
                ]) . "\n\n";

                ob_flush();
                flush();

                sleep(5);
            }

        }, 200, [
            'Content-Type'  => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection'    => 'keep-alive',
        ]);
    }
}