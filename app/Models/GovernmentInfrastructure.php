<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentInfrastructure extends Model
{

    /** @use HasFactory<\Database\Factories\GovernmentInfrastructureFactory> */
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function infrastructure()
    {
        return $this->belongsTo(Infrastructure::class);
    }

    public function government()
    {
        return $this->belongsTo(Government::class);
    }


    public function getNextTickAttribute()
    {
        // Define base resource production values
        $baseOutput = $this->infrastructure->base;
        $level = $this->level;
        $efficiency = $this->efficiency;
        $populationAssigned = $this->population;

        // Apply efficiency decay with level increases
        $efficiencyDecay = config('game.settings.infrastructure_efficiency_decay', 0.95);
        $effectiveEfficiency = $efficiency * pow($efficiencyDecay, $level - 1);

        // Improved population scaling - diminishing returns
        $populationThreshold = max(1, $level * 2); // Threshold increases with level
        $populationImpact = 1.0;
        
        if ($populationAssigned >= $populationThreshold) {
            // Diminishing returns for excess population
            $excessPopulation = $populationAssigned - $populationThreshold;
            $populationImpact = 1.0 + (min($excessPopulation, $populationThreshold) / $populationThreshold) * 0.5;
        } else {
            // Penalty for insufficient population
            $populationImpact = $populationAssigned / $populationThreshold;
        }

        // Apply level scaling with diminishing returns
        $levelScaling = 1.0 + ($level - 1) * 0.5; // Linear scaling instead of exponential

        return round($baseOutput * $effectiveEfficiency * $populationImpact * $levelScaling, 2);
    }

    public function getUpgradeCostAttribute()
    {
        $base_cost = $this->infrastructure->cost;
        $growth_factor = 1.3; // Reduced from 1.5 for more reasonable scaling
        $level = $this->level;

        // Calculate the upgrade cost based on level and base cost
        $upgrade_cost = (int) ($base_cost * pow($growth_factor, $level - 1));

        $rounded_cost = round($upgrade_cost / 100) * 100; // Round to nearest 100

        return $rounded_cost;
    }



    public function setPopulation(int $population)
    {

        $currentPopulation = $this->population;

        $remaining = $currentPopulation - $population;


        if ($currentPopulation < $population) {
            if (abs($remaining) > $this->government->available_population ) {
                return false;
            }
        }

        $this->population = $population;
        $this->save();

        if ($remaining > 0) {
            $this->government->available_population += $remaining;
        } else if ($remaining < 0) {
            $this->government->available_population -= abs($remaining);
        }

        $this->government->save();

        return true;

    }

}
