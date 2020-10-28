<?php

namespace Database\Seeders;

use App\Models\PublicTransport;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PublicTransportTableSeeder extends Seeder
{
    /**
     * Check if the given "type-route_number" pair is unique.
     *
     * @param  string  $routeNumber
     * @param  string  $type
     * @param  array  $arrayOfRouteNumbersWithTypes
     * @return bool
     */
    function isUniqueTypeRouteNumberPair($routeNumber, $type, $arrayOfRouteNumbersWithTypes) {
        return !in_array("{$routeNumber}-{$type}", $arrayOfRouteNumbersWithTypes);
    }

    /**
     * Seed the application's database ("public_transport" table).
     *
     * @return void
     */
    public function run()
    {
        PublicTransport::truncate();
        $publicTransportTypes = config('constants.public_transport_types');
        $organizationNames = [
            'Company #1',
            'Company #2',
            'Company #3',
        ];
        $faker = Factory::create();
        $arrayOfRouteNumbersWithTypes = [];
        for ($i = 0; $i < 25; $i++) {
            $routeNumber = null;
            $type = null;
            do {
                $routeNumber = "{$faker->numberBetween(1, 100)}{$faker->optional(0.5, "")->randomLetter()}";
                $type = $faker->randomElement($publicTransportTypes);
            } while (!$this->isUniqueTypeRouteNumberPair($routeNumber, $type, $arrayOfRouteNumbersWithTypes));
            array_push($arrayOfRouteNumbersWithTypes, "{$routeNumber}-{$type}");
            PublicTransport::create([
                'type' => $type,
                'route_number' => $routeNumber,
                'capacity' => $faker->numberBetween(10, 100),
                'organization_name' => $faker->randomElement($organizationNames),
            ]);
        }
    }
}
