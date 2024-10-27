<?php

namespace Database\Seeders;

use App\Models\Government;
use App\Models\GovernmentInfrastructure;
use App\Models\GovernmentResource;
use App\Models\Infrastructure;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(User::all() as $user) {
            $government = Government::factory()->create(['user_id' => $user->id]);

            foreach(Resource::all() as $resource) {
                GovernmentResource::factory()->create(['government_id' => $government->id, 'resource_id' => $resource->id]);
            }

            foreach(Infrastructure::all() as $infrastructure) {
                GovernmentInfrastructure::factory()->create(['government_id' => $government->id, 'infrastructure_id' => $infrastructure->id]);
            }
        }
    }
}
