<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Karena MySQL tidak bisa langsung modify ENUM,
        // kita pakai raw query untuk update enum-nya
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status
            ENUM('pending','process','done','delivered','selesai')
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Kembalikan ke enum lama kalau di-rollback
        DB::statement("
            ALTER TABLE orders
            MODIFY COLUMN status
            ENUM('pending','process','done')
            NOT NULL DEFAULT 'pending'
        ");
    }
};