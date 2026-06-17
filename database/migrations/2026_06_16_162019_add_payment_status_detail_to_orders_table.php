<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ✅ FIX: status keuangan murni, terpisah dari status alur dapur (status).
            // Sebelumnya tidak ada kolom ini sama sekali, sehingga "Status Keuangan"
            // di halaman customer hanya menebak dari kolom `status` (pending/process/dst).
            $table->string('payment_status')->default('pending')->after('payment_method');

            // ✅ FIX: payment_type asli dari Midtrans (bank_transfer, gopay, qris, credit_card, dll)
            $table->string('payment_type')->nullable()->after('payment_status');

            // ✅ FIX: channel/bank spesifik (bca, bni, bri, gopay, shopeepay, qris, dst)
            $table->string('payment_channel')->nullable()->after('payment_type');

            // ✅ FIX: label deskriptif untuk kasir/admin (mis. "BCA Virtual Account")
            $table->string('payment_method_label')->nullable()->after('payment_channel');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_type', 'payment_channel', 'payment_method_label']);
        });
    }
};