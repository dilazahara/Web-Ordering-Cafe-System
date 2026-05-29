<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite tidak support MODIFY COLUMN, skip — enum sudah ditangani di create_orders_table
            // Status baru 'delivered' & 'selesai' ditambahkan dengan recreate kolom jika perlu
            // Untuk testing, cukup pastikan kolom status sudah ada
        } else {
            DB::statement("
                ALTER TABLE orders
                MODIFY COLUMN status
                ENUM('pending','process','done','delivered','selesai')
                NOT NULL DEFAULT 'pending'
            ");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("
                ALTER TABLE orders
                MODIFY COLUMN status
                ENUM('pending','process','done')
                NOT NULL DEFAULT 'pending'
            ");
        }
    }
};