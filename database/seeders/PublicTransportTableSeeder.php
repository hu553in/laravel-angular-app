<?php

namespace Database\Seeders;

use App\Models\PublicTransport;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PublicTransportTableSeeder extends Seeder
{
    /**
     * Seed the application's database ("public_transport" table).
     *
     * @return void
     */
    public function run()
    {
        PublicTransport::truncate();
        $faker = Factory::create();
        for ($i = 0; $i < 25; $i++) {
            $route_number_number = $faker->unique()->numberBetween(1, 100);
            $route_number_letter = $faker->optional(0.5, "")->randomLetter();
            PublicTransport::create([
                'type' => $faker->word(),
                'route_number' => "{$route_number_number}{$route_number_letter}",
                'capacity' => $faker->numberBetween(10, 100),
                'organization_name' => $faker->company(),
            ]);
        }
    }
}
