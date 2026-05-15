<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_addon_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                  ->constrained('menus')
                  ->cascadeOnDelete();
            $table->foreignId('addon_group_id')
                  ->constrained('addon_groups')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_addon_group');
    }
};