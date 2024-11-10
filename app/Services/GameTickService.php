<?php

namespace App\Services;

use App\Enums\Season;
use App\Enums\TurnState;
use App\Models\GameSetting;
use App\Models\Government;
use App\Models\Resource;

class GameTickService
{

    public function processTick()
    {
        $gameSettingTurnState = GameSetting::where('name', 'turn_state')->first();
        $gameSettingTurnState->value = TurnState::PROCESSING->value;
        $gameSettingTurnState->save();

        $governments = Government::where('id', 1)->get();

        /** @var Government $government */
        foreach ($governments as $government) {

            $resourceConsumption = $government->calculateResourceConsumption();
            $government->applyResourceConsumption($resourceConsumption);
            $government->calculateStatsChange();
            $populationChange = $government->calculatePopulationChange();
            $government->applyPopulationChange($populationChange);
            $government->calculateResourceChange();
            $interestChange = $government->calculateInterestAmount();
            $government->applyInterest($interestChange);
            $government->save();
        }

        $resources = Resource::all();

        foreach ($resources as $resource) {
            $resource->adjustMarketPrices();
        }


        $gameSettingTick = GameSetting::where('name', 'turn')->first();
        $gameSettingTick->value = $gameSettingTick->value + 1;
        $gameSettingTick->save();

        if ($gameSettingTick->value % 8 === 0) {
            // Every 8 turns (2 hours) the season changes
            $gameSettingSeason = GameSetting::where('name', 'season')->first();
            $gameSettingSeason->value = Season::from($gameSettingSeason->value)->next();
            $gameSettingSeason->save();
        }


        $gameSettingTurnState->value = TurnState::RUNNING->value;
        $gameSettingTurnState->save();

    }


}
