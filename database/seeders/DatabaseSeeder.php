<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
            ShopSeeder::class, //外部キー制約がある場合は事前に必要なデータ(Owner)を設定する
            ImageSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
