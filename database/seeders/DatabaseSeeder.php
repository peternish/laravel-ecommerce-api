<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        if (App::environment() === 'testing'){
            $this->call(ProductSeeder::class);
            $this->call(OrderSeeder::class);
        }
    }
}
