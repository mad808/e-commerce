<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Aşgabat', 'delivery_price' => 20],
            ['name' => 'Gokdepe', 'delivery_price' => 40],
            ['name' => 'Anew', 'delivery_price' => 40],
            ['name' => 'Mary', 'delivery_price' => 60],
            ['name' => 'Daşoguz', 'delivery_price' => 60],
            ['name' => 'Türkmenabat', 'delivery_price' => 60],
            ['name' => 'Balkanabat', 'delivery_price' => 60],
            ['name' => 'Türkmenbaşy', 'delivery_price' => 60],
        ];

        foreach ($locations as $loc) {
            \App\Models\Location::create($loc);
        }
    }
}
