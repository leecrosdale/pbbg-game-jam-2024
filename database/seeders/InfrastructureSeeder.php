<?php

namespace Database\Seeders;

use App\Enums\InfrastructureType;
use App\Models\Infrastructure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfrastructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infrastructures = [
            [
                'name' => 'Small Apartment Block',
                'type' => InfrastructureType::HOUSING,
                'cost' => 100
            ],
            [
                'name' => 'Large Apartment Block',
                'type' => InfrastructureType::HOUSING,
                'cost' => 300
            ]
        ];


        foreach ($infrastructures as $infrastructure) {

            Infrastructure::factory()->create($infrastructure);

        }

    }

}
