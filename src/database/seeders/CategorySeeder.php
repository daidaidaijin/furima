<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ここを全部あなた用に書き換える部分！
        $categories = [
            'レディース',
            'メンズ',
            'ベビー・キッズ',
            '家電・スマホ・カメラ',
            'おもちゃ・ホビー・グッズ',
            '本・音楽・ゲーム',
            'コスメ・美容',
            'インテリア・住まい・小物',
            'スポーツ・レジャー',
            'ハンドメイド',
            'チケット',
            '自動車・オートバイ',
            '生活雑貨',
            'ペット用品'
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name
            ]);
        }
    }
}