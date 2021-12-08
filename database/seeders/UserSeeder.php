<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'root@localhost',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::factory(10)->create();
    }
}
