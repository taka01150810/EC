<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('primary_categories')->insert([
            //migrationファイルを見ながら行うとやりやすい。idは自動生成される。
            [
                'name' => '花・観葉植物・フラワーギフト',
                'sort_order' => 1,
            ],
            [
                'name' => 'ドライ・プリザーブドフラワー',
                'sort_order' => 2,
            ],
            [
                'name' => 'プリザーブドフラワー',
                'sort_order' => 3,
            ],
            [
                'name' => 'フラワーアレンジメント',
                'sort_order' => 4,
            ],
            [
                'name' => '花束',
                'sort_order' => 5,
            ],
        ]);

        DB::table('secondary_categories')->insert([
            //migrationファイルを見ながら行うとやりやすい。idは自動生成される。
            [
                'name' => 'フラワーアレンジメント',
                'sort_order' => 1,
                'primary_category_id' => 1
            ],
            [
                'name' => '花束',
                'sort_order' => 1,
                'primary_category_id' => 1
            ],
            [
                'name' => '観葉植物',
                'sort_order' => 1,
                'primary_category_id' => 1
            ],
            [
                'name' => 'ドライプランツ',
                'sort_order' => 1,
                'primary_category_id' => 2
            ],
            [
                'name' => 'プリザーブドフラワー',
                'sort_order' => 1,
                'primary_category_id' => 2
            ],
        ]);
    }
}