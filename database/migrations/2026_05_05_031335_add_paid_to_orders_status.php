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
            // SQLite tidak support MODIFY COLUMN — skip, enum sudah lengkap di create_orders_table
        } else {
            // MySQL: tambah 'paid' ke enum status
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','paid','process','done','delivered','selesai') NOT NULL DEFAULT 'pending'");

            // Fix semua order qris yang salah status
            DB::statement("UPDATE orders SET status = 'paid' WHERE payment_method = 'qris' AND status = 'pending'");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','process','done','delivered','selesai') NOT NULL DEFAULT 'pending'");
        }
    }
};