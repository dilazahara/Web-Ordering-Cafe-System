<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meja;
use Illuminate\Support\Str;

class RefreshAllQrTokens extends Command
{
    protected $signature   = 'qr:refresh-all';
    protected $description = 'Reset token QR semua meja setiap tengah malam. Token lama langsung tidak berlaku.';

    public function handle(): int
    {
        $mejas = Meja::all();

        if ($mejas->isEmpty()) {
            $this->info('Tidak ada meja ditemukan.');
            return self::SUCCESS;
        }

        foreach ($mejas as $meja) {
            $meja->update(['qr_token' => Str::random(40)]);
        }

        $this->info("[" . now()->format('Y-m-d H:i:s') . "] ✅ Token QR {$mejas->count()} meja berhasil di-reset.");

        return self::SUCCESS;
    }
}