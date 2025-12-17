<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('item_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unique(['item_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_items');
    }
};
