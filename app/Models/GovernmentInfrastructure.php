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
        $baseOutput = $this->infrastructure->base;  // Default production per tick for this type
        $level = $this->level;
        $efficiency = $this->efficiency;
        $populationAssigned = $this->population;

        $populationThreshold = 1;
        // Population impact: boosts production if population meets or exceeds the threshold
        $populationImpact = ($populationAssigned >= $populationThreshold)
            ? 1 + ($populationAssigned - $populationThreshold) / $populationThreshold
            : ($populationAssigned / $populationThreshold);


        return round($baseOutput * $efficiency * $populationImpact, 2) * $level;

    }

    public function getUpgradeCostAttribute()
    {
        $base_cost = 100;          // Set your base cost
        $growth_factor = 1.5;      // This factor controls the exponential increase rate
        $level = $this->level;     // Use your model's current level attribute

        // Calculate the upgrade cost based on level and base cost
        $upgrade_cost = (int) ($base_cost * pow($growth_factor, $level));

        $rounded_cost = round($upgrade_cost / 500) * 500;

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
