<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // is_sold が無いときだけ追加
        if (!Schema::hasColumn('items', 'is_sold')) {
            Schema::table('items', function (Blueprint $table) {
                $table->boolean('is_sold')->default(false)->after('price');
            });
        }
    }

    public function down(): void
    {
        // is_sold があるときだけ削除
        if (Schema::hasColumn('items', 'is_sold')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('is_sold');
            });
        }
    }
};
