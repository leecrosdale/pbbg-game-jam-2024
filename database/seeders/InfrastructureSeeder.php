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
            // Housing
            [
                'name' => 'Small Apartment Block',
                'type' => InfrastructureType::HOUSING,
                'cost' => 2000,
                'base' => 0.5, // Reduced from 1.0 - housing provides population capacity, not happiness
                'resource_type' => 'happiness',
            ],
            [
                'name' => 'Large Apartment Block',
                'type' => InfrastructureType::HOUSING,
                'cost' => 6000,
                'base' => 1.5, // Reduced from 3.0
                'resource_type' => 'happiness',
            ],

            // Food
            [
                'name' => 'Farm',
                'type' => InfrastructureType::FOOD,
                'cost' => 1000,
                'base' => 5.0, // Reduced from 10.0 - more realistic food production
                'resource_type' => 'food',
            ],
            [
                'name' => 'Food Processing Plant',
                'type' => InfrastructureType::FOOD,
                'cost' => 4000,
                'base' => 15.0, // Reduced from 30.0
                'resource_type' => 'food',
            ],

            // Electricity
            [
                'name' => 'Power Station',
                'type' => InfrastructureType::ELECTRICITY,
                'cost' => 3000,
                'base' => 10.0, // Reduced from 20.0 - more realistic power generation
                'resource_type' => 'electricity',
            ],
            [
                'name' => 'Solar Farm',
                'type' => InfrastructureType::ELECTRICITY,
                'cost' => 8000,
                'base' => 25.0, // Reduced from 50.0
                'resource_type' => 'electricity',
            ],

            // Medicine
            [
                'name' => 'Hospital',
                'type' => InfrastructureType::MEDICINE,
                'cost' => 5000,
                'base' => 8.0, // Reduced from 15.0 - medicine production is complex
                'resource_type' => 'medicine',
            ],
            [
                'name' => 'Pharmaceutical Factory',
                'type' => InfrastructureType::MEDICINE,
                'cost' => 10000,
                'base' => 20.0, // Reduced from 40.0
                'resource_type' => 'medicine',
            ],

            // Clothing
            [
                'name' => 'Clothing Factory',
                'type' => InfrastructureType::CLOTHING,
                'cost' => 4000,
                'base' => 10.0, // Reduced from 20.0 - more realistic clothing production
                'resource_type' => 'clothing',
            ],
            [
                'name' => 'Textile Mill',
                'type' => InfrastructureType::CLOTHING,
                'cost' => 9000,
                'base' => 25.0, // Reduced from 50.0
                'resource_type' => 'clothing',
            ],
        ];

        foreach ($infrastructures as $infrastructure) {
            Infrastructure::factory()->create($infrastructure);
        }
    }
}
