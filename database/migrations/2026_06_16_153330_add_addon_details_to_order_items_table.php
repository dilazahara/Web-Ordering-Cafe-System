<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Harga dasar menu (sebelum add-on)
            if (! Schema::hasColumn('order_items', 'base_price')) {
                $table->integer('base_price')->default(0)->after('price');
            }
            // JSON berisi add-on yang dipilih: [{"id":1,"name":"Medium","price":1000}, ...]
            if (! Schema::hasColumn('order_items', 'addon_details')) {
                $table->json('addon_details')->nullable()->after('base_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['base_price', 'addon_details']);
        });
    }
};