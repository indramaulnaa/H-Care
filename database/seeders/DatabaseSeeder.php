<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil seeder kustom kita
        $this->call([
            UserSeeder::class,
            PegawaiSeeder::class,
        ]);
    }
}