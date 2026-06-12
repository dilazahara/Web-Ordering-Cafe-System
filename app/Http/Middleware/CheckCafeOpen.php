<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckCafeOpen
{
    private const JAM_BUKA  = '08:00';
    private const JAM_TUTUP = '22:00';

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
        $now   = Carbon::now($timezone);
        $buka  = Carbon::createFromTimeString(self::JAM_BUKA, $timezone);
        $tutup = Carbon::createFromTimeString(self::JAM_TUTUP, $timezone);

        return $now->between($buka, $tutup);
    }
}