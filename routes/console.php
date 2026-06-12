<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ══════════════════════════════════════════════════════
// SCHEDULED TASKS
// ══════════════════════════════════════════════════════

// Reset token QR semua meja setiap tengah malam (00:00)
// Efek: semua QR yang disalin sebelumnya tidak bisa dipakai lagi keesokan harinya
Schedule::command('qr:refresh-all')->dailyAt('00:00');