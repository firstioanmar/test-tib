<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql2')->table('products')->insert([
            'name' => 'buku tulis',
            'price' => '1500',
            'description' => 'ini buku tulis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::connection('mysql2')->table('products')->insert([
            'name' => 'pensil 2B',
            'price' => '1000',
            'description' => 'ini pensil 2B cuy',
            'created_at' => '2022-07-30 00:00:00',
            'updated_at' => '2022-07-30 00:00:00',
        ]);

        DB::connection('mysql2')->table('products')->insert([
            'name' => 'jam tangan',
            'price' => '50000',
            'description' => 'jam tangan kiri',
            'created_at' => '2022-08-30 00:00:00',
            'updated_at' => '2022-08-30 00:00:00',
        ]);

        DB::connection('mysql2')->table('products')->insert([
            'name' => 'topi bundar',
            'price' => '5000',
            'description' => 'topi saya bundar',
            'created_at' => '2022-08-30 00:00:00',
            'updated_at' => '2022-08-30 00:00:00',
        ]);
    }
}
