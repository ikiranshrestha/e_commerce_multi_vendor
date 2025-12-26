<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/sample_airports.json');

        if (!File::exists($path)) {
            $this->command->error("JSON file not found: $path");
            return;
        }

        $json = File::get($path);
        $airports = json_decode($json, true);

        foreach ($airports as $airport) {
            Airport::updateOrCreate(
                ['code' => $airport['code']],
                [
                    'name'    => $airport['name'],
                    'city'    => $airport['city'],
                    'country' => $airport['country'],
                ]
            );
        }

        $this->command->info('Airports seeded successfully.');
    }
}
