<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) item_id を追加（既存データがあっても落ちないように nullable）
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->after('items_id');
                $table->index('item_id');
            }
        });

        // 2) 既存データを items_id -> item_id にコピー
        DB::statement('UPDATE comments SET item_id = items_id WHERE item_id IS NULL');

        // 3) items テーブルに外部キーを張る（items テーブル名が items ならこれでOK）
        Schema::table('comments', function (Blueprint $table) {
            // すでに外部キーがあると失敗するので、無ければ付ける
            // MySQLでは外部キー名の存在確認が面倒なので、ここは一旦付けに行く
            $table->foreign('item_id')->references('id')->on('items')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // 外部キー -> index -> column の順
            try { $table->dropForeign(['item_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['item_id']); } catch (\Throwable $e) {}

            if (Schema::hasColumn('comments', 'item_id')) {
                $table->dropColumn('item_id');
            }
        });
    }
};
