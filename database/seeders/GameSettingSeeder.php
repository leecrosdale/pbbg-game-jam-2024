<?php

namespace Database\Seeders;

use App\Models\GameSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GameSetting::factory()->create([
            'name' => 'available_population',
            'value' => 100
        ]);

        GameSetting::factory()->create([
            'name' => 'turn',
            'value' => 0
        ]);


    }
}
