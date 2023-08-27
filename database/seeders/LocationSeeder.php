<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('locations')->delete();

        $data = [
            [
                'id' => 1,
                'location' => 'Al Wakrah',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'location' => 'Al Khour',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'location' => 'Al Doha',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'location' => 'Al Rayyan',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 5,
                'location' => 'Um Salal',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 6,
                'location' => 'Al Shahania',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 7,
                'location' => 'Mesaieed',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 8,
                'location' => 'Al Shamal',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 9,
                'location' => 'Lusail',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 10,
                'location' => 'The Pearl',
                'latitude' => null,
                'longitude' => null,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('locations')->insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
