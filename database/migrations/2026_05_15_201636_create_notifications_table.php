<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');           // order_new, order_confirmed, order_done, order_delivered
            $table->string('target_role');    // kasir, dapur, pelayan, admin
            $table->string('title');
            $table->string('message');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('queue_number')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
