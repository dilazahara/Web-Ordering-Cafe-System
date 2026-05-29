<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('table_number')->nullable();
            $table->string('payment_method')->default('cash');
            $table->text('note')->nullable();
            $table->integer('total')->default(0);
            $table->enum('status', ['pending','paid','process','done','delivered','selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};