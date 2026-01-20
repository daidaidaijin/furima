<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'items/watch.jpg',
                'condition' => '良好',
            ],
            [
                'title' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'items/hdd.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'title' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'description' => '新鮮な玉ねぎの3束セット',
                'image_path' => 'items/ball.jpg',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'title' => '革靴',
                'price' => 4000,
                'brand' => null,
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'items/shoes.jpg',
                'condition' => '状態が悪い',
            ],
            [
                'title' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'description' => '高性能なノートパソコン',
                'image_path' => 'items/laptop.jpg',
                'condition' => '良好',
            ],
            [
                'title' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'items/mic.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
            [
                'title' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'items/bag.jpg',
                'condition' => 'やや傷や汚れあり',
            ],
            [
                'title' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'description' => '使いやすいタンブラー',
                'image_path' => 'items/tumbler.jpg',
                'condition' => '状態が悪い',
            ],
            [
                'title' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'items/mill.jpg',
                'condition' => '良好',
            ],
            [
                'title' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'description' => '便利なメイクアップセット',
                'image_path' => 'items/makeup.jpg',
                'condition' => '目立った傷や汚れなし',
            ],
        ];

        foreach ($items as $item) {
            Item::create(array_merge($item, [
                'user_id' => 1,      // ★ ダミーユーザーID
                'is_sold' => false,
            ]));
        }
    }
}
