<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ID order unik yang dikirim ke Midtrans (format: ORDER-{id}-{timestamp})
            $table->string('midtrans_order_id')->nullable()->after('payment_method');

            // Snap token dari Midtrans untuk membuka payment popup
            $table->text('snap_token')->nullable()->after('midtrans_order_id');

            // Tambahkan status 'waiting_payment' jika belum ada
            // Karena enum di MySQL tidak bisa di-alter dengan mudah,
            // kita modifikasi kolom status
            $table->string('status')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'snap_token']);
        });
    }
};