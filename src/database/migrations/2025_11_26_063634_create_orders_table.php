<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 購入者
        $table->foreignId('item_id')->constrained()->cascadeOnDelete(); // 商品
        $table->unsignedInteger('price'); // 購入時点価格
        $table->string('payment_method'); // 'konbini' or 'card'
        $table->string('stripe_session_id')->nullable();
        $table->timestamp('purchased_at')->nullable();
        $table->timestamps();

        $table->unique('item_id'); // 1商品1購入
    });
}


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
