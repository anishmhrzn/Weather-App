<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('cities')->count() === 0) {
            $cities = array_map('str_getcsv', array_map('trim', file('database/cities.csv')));
            array_shift($cities);
            foreach ($cities as $city) {
                DB::table('cities')->insert([
                    'name' => $city[0],
                    'postal_code' => $city[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
