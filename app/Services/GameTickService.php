<?php

namespace App\Services;

use App\Models\Government;

class GameTickService
{

    public function processTick()
    {
        $governments = Government::all();

        foreach ($governments as $government) {
//            $government->calculatePopulationChange();
//            $government->calculateResourceChange();
            $government->calculateStatsChange();
        }
    }


}
