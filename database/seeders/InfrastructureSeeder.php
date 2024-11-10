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
                'cost' => 1000,
                'base' => 1.0, // Base production for the infrastructure
                'resource_type' => 'happiness', // Resource produced
            ],
            [
                'name' => 'Large Apartment Block',
                'type' => InfrastructureType::HOUSING,
                'cost' => 3000,
                'base' => 3.0, // Higher base production
                'resource_type' => 'happiness',
            ],

            // Food
            [
                'name' => 'Farm',
                'type' => InfrastructureType::FOOD,
                'cost' => 500,
                'base' => 10.0, // Produces 10 food
                'resource_type' => 'food',
            ],
            [
                'name' => 'Food Processing Plant',
                'type' => InfrastructureType::FOOD,
                'cost' => 2000,
                'base' => 30.0, // Produces 30 food
                'resource_type' => 'food',
            ],

            // Electricity
            [
                'name' => 'Power Station',
                'type' => InfrastructureType::ELECTRICITY,
                'cost' => 1500,
                'base' => 20.0, // Produces 20 electricity
                'resource_type' => 'electricity',
            ],
            [
                'name' => 'Solar Farm',
                'type' => InfrastructureType::ELECTRICITY,
                'cost' => 4000,
                'base' => 50.0, // Produces 50 electricity
                'resource_type' => 'electricity',
            ],

            // Medicine
            [
                'name' => 'Hospital',
                'type' => InfrastructureType::MEDICINE,
                'cost' => 2500,
                'base' => 15.0, // Produces 15 medicine
                'resource_type' => 'medicine',
            ],
            [
                'name' => 'Pharmaceutical Factory',
                'type' => InfrastructureType::MEDICINE,
                'cost' => 5000,
                'base' => 40.0, // Produces 40 medicine
                'resource_type' => 'medicine',
            ],

            // Clothing
            [
                'name' => 'Clothing Factory',
                'type' => InfrastructureType::CLOTHING,
                'cost' => 2000,
                'base' => 20.0, // Produces 20 clothing
                'resource_type' => 'clothing',
            ],
            [
                'name' => 'Textile Mill',
                'type' => InfrastructureType::CLOTHING,
                'cost' => 4500,
                'base' => 50.0, // Produces 50 clothing
                'resource_type' => 'clothing',
            ],
        ];




        foreach ($infrastructures as $infrastructure) {

            Infrastructure::factory()->create($infrastructure);

        }

    }

}
