<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom qr_token ke tabel mejas.
     * Token ini di-generate ulang setiap QR dicetak/dibuka admin,
     * sehingga QR lama otomatis tidak berlaku.
     */
    public function up(): void
    {
        Schema::table('mejas', function (Blueprint $table) {
            $table->string('qr_token', 64)->nullable()->unique()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('mejas', function (Blueprint $table) {
            $table->dropColumn('qr_token');
        });
    }
};