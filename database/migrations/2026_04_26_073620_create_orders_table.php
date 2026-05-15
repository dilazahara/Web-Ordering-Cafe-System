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
        $table->integer('table_number')->nullable(); // ✅ nullable
        $table->string('payment_method')->default('cash'); // ✅ tambah
        $table->text('note')->nullable(); // ✅ tambah
        $table->integer('total')->default(0);
        $table->enum('status', ['pending','process','done'])->default('pending');
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};