<?php

namespace Database\Seeders;

use App\Enums\Season;
use App\Enums\TurnState;
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

        GameSetting::factory()->create([
            'name' => 'turn_state',
            'value' => TurnState::PROCESSING->value
        ]);

        GameSetting::factory()->create([
            'name' => 'season',
            'value' => Season::SPRING->value
        ]);

    }
}
