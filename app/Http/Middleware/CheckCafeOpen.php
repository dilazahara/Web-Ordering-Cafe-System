<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckCafeOpen
{
    private const JAM_BUKA  = '08:00';
    private const JAM_TUTUP = '03:00';

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isCafeOpen()) {
            $pesan = 'Cafe sedang tutup. Silakan kembali pada jam operasional ('
                . self::JAM_BUKA . '-' . self::JAM_TUTUP . ' WIB).';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error'   => 'cafe_closed',
                    'message' => $pesan,
                ], 503);
            }

            return redirect('/')->with('error', $pesan);
        }

        return $next($request);
    }

   private function isCafeOpen(): bool
{
    $timezone = config('app.timezone', 'Asia/Jakarta');

    $now = Carbon::now($timezone)->format('H:i');

    // Buka jam 08:00 sampai 02:00 esok hari
    return $now >= self::JAM_BUKA || $now < self::JAM_TUTUP;
}
}