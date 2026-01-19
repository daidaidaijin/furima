<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // order_id を nullable にする（MySQL直接）
        DB::statement("
            ALTER TABLE shipping_addresses
            MODIFY order_id BIGINT UNSIGNED NULL
        ");
    }

    public function down(): void
    {
        // 元に戻す（NOT NULL）
        DB::statement("
            ALTER TABLE shipping_addresses
            MODIFY order_id BIGINT UNSIGNED NOT NULL
        ");
    }
};
