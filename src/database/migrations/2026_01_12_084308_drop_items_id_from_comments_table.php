<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // items_id が存在するときだけ drop
            if (Schema::hasColumn('comments', 'items_id')) {
                $table->dropColumn('items_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // 戻すときも無ければ作る（型はプロジェクトに合わせて）
            if (!Schema::hasColumn('comments', 'items_id')) {
                $table->unsignedBigInteger('items_id')->nullable();
            }
        });
    }
};
