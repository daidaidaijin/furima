<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_postal_code')->nullable()->after('payment_method');
            $table->string('shipping_address')->nullable()->after('shipping_postal_code');
            $table->string('shipping_building')->nullable()->after('shipping_address');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_postal_code',
                'shipping_address',
                'shipping_building',
            ]);
        });
    }
};
