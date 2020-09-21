<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database ("users" table).
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $faker = Factory::create();
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);
        User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user'),
        ]);
    }
}
