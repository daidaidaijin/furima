<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // 既にカラムがあるかもしれないので、環境によっては追加前に確認が必要だけど
            // まずは課題の item_id を足すのが最優先

            if (!Schema::hasColumn('comments', 'item_id')) {
                $table->foreignId('item_id')->after('id')->constrained()->cascadeOnDelete();
            }

            if (!Schema::hasColumn('comments', 'user_id')) {
                $table->foreignId('user_id')->after('item_id')->constrained()->cascadeOnDelete();
            }

            if (!Schema::hasColumn('comments', 'comment')) {
                $table->string('comment', 255)->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // 外部キーがあるので先に dropForeign
            if (Schema::hasColumn('comments', 'item_id')) {
                $table->dropConstrainedForeignId('item_id');
            }
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('comments', 'comment')) {
                $table->dropColumn('comment');
            }
        });
    }
};
