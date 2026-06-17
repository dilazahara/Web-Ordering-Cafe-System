<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Meja;
use Symfony\Component\HttpFoundation\Response;

class ValidateTableSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ TAKE AWAY: Jika request POST dengan order_type=take_away,
        // skip validasi meja karena tidak perlu nomor meja.
        if ($request->isMethod('POST') && $request->input('order_type') === 'take_away') {
            return $next($request);
        }

        $tableNumber  = session('table_number');
        $sessionToken = session('table_scan_token');

        $meja = $tableNumber
            ? Meja::where('nomor_meja', $tableNumber)->first()
            : null;

        if (! $meja || $meja->status === 'kosong') {
            $this->clearTableSession();
            return $this->rejectRequest(
                $request,
                'Meja belum aktif. Silakan scan QR meja terlebih dahulu.'
            );
        }

        if (empty($sessionToken) || $sessionToken !== $meja->qr_token) {
            return $this->rejectRequest(
                $request,
                'Sesi tidak ditemukan. Silakan scan QR meja kembali.'
            );
        }

        return $next($request);
    }

    private function clearTableSession(): void
    {
        session()->forget(['table_number', 'table_scanned_at', 'table_scan_token']);
    }

    private function rejectRequest(Request $request, string $pesan): Response
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error'   => 'table_session_required',
                'message' => $pesan,
            ], 401);
        }

        return redirect('/')->with('error', $pesan);
    }
}