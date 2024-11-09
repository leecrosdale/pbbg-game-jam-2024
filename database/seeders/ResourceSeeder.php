<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            'food',
            'electricity',
            'medicine',
            'happiness'
        ];


        foreach ($resources as $resource) {

            Resource::factory()->create([
                'name' => $resource
            ]);
        }

    }
}
