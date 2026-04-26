<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 修正: 関連テーブルを先に作る
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            StatusSeeder::class,
            ProductSeeder::class,
        ]);
    }
}