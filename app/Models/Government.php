<?php

namespace App\Models;

use App\Enums\InfrastructureType;
use App\Enums\Season;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Government extends Model
{

    use HasUuid;
    use HasFactory;

    protected $guarded = [];

//    protected $casts = [
//        'economy' => 'integer',
//        'health' => 'integer',
//        'safety' => 'integer',
//        'education' => 'integer',
//    ];

    public function government_infrastructures()
    {
        return $this->hasMany(GovernmentInfrastructure::class);
    }

    public function government_resources()
    {
        return $this->hasMany(GovernmentResource::class);
    }


    public function getSectorAssignedPopulationAttribute()
    {
        return $this->education_population + $this->health_population + $this->safety_population + $this->economy_population;
    }

    public function getPopulationAttribute()
    {

        $population = $this->education_population + $this->health_population + $this->safety_population + $this->economy_population;
        $population += $this->government_infrastructures()->sum('population');
        $population += $this->available_population;

        return $population;
    }


    public function calculateInterestAmount()
    {
        $baseInterestRate = 0.01;
        $scalingFactor = 0.01;
        $interestRate = $baseInterestRate + ($this->overall * $scalingFactor);
        $interestRate = min($interestRate, 0.2);
        $availableMoney = $this->money;
        $interestAmount = $availableMoney * $interestRate;
        return $interestAmount;
    }


    public function applyInterest($interestAmount)
    {
        // Add the calculated interest to the available money
        $this->money += $interestAmount;

        // Save the updated value to the database
        $this->save();

        // Return the new money balance
        return $this->money;
    }


    public function calculatePopulationChange()
    {

//        // Define scaling factor for population adjustment
//        $scaling_factor = 0.5; // Adjust this factor to control rate of population growth
//
//        // Determine population change based on overall level
//        // This example increases population slightly for each level, more at higher levels
//        $population_change = round($this->overall * $scaling_factor);

        $population_change = $this->government_infrastructures()->whereHas('infrastructure', function($q) {
            $q->where('type', InfrastructureType::HOUSING->value);
        })->sum('level') - $this->population;

        return $population_change;
    }

    public function applyPopulationChange($change)
    {
        // Apply the population change, ensuring it doesn't go below 0
        $this->available_population = max(0, $this->available_population + $change);
        $this->save();
    }

    public function calculateResourceChange()
    {
        $resourceChanges = [];

        foreach ($this->government_infrastructures as $infrastructure) {

            // Final resource production calculation
            $resourceGenerated = $infrastructure->next_tick;;

            // Store the resource generated in the result array
            $resourceType = $infrastructure->infrastructure->resource_type;
            $resourceChanges[$resourceType] = ($resourceChanges[$resourceType] ?? 0) + $resourceGenerated;

        }

        foreach ($resourceChanges as $resource => $amount)
        {
            if ($amount <= 0) continue;

            $this->changeResourceAmount($resource, $amount);
        }

        return $resourceChanges;
    }

    // Example method for calculating production efficiency based on resources
    public function calculateResourceEfficiency()
    {
        $totalResources = $this->government_resources->sum('amount');
        $totalCapacity = $this->government_resources->count() * 100; // Assuming 100 is the max capacity per resource

        return $totalCapacity > 0 ? ($totalResources / $totalCapacity) * 100 : 0;
    }


    public function changeResourceAmount(string $resourceType, float $amount)
    {

        $resource = Resource::where('name', $resourceType)->first();

        $governmentResource = $this->government_resources()->firstOrCreate(['resource_id' => $resource->id]);

        $governmentResource->amount += $amount;
        $governmentResource->save();

    }

    public function calculateResourceConsumption($resourceType = null)
    {
        $population = $this->population;
        $overallScore = $this->overall;

        // Consumption rates per person
        $consumptionRates = config('game.resources.consumption_rates');

        // Result array to store calculations
        $results = [
            'required' => [],
            'overall_score_impact' => $overallScore,
        ];

        // Filter rates for a specific resource if provided
        $ratesToCalculate = $resourceType ? [$resourceType => $consumptionRates[$resourceType] ?? 0] : $consumptionRates;

        // Calculate resource consumption
        foreach ($ratesToCalculate as $resource => $rate) {
            $requiredAmount = $rate * $population;
            $availableAmount = $this->{$resource}; // Current resource level

            $results['required'][$resource] = $requiredAmount;

            if ($availableAmount < $requiredAmount) {
                // Shortfall calculation if insufficient resources
                $shortfall = $requiredAmount - $availableAmount;
                $impactFactor = 0.1; // Define how much each unit of shortfall impacts score
                $results['overall_score_impact'] -= round($shortfall * $impactFactor);
            }
        }

        // Ensure score doesn't go below zero
        $results['overall_score_impact'] = max(0, $results['overall_score_impact']);

        return $results;
    }

    public function applyResourceConsumption(array $consumptionResult)
    {


        $resources = $this->government_resources;

        foreach ($resources as $resource) {

            $amount = $consumptionResult['required'][$resource->resource->name];

            if ($resource->amount < $amount) {
                $resource->amount = 0;
            } else {
                $resource->amount -= $amount;
            }


            $resource->save();
        }

        $this->overall = $consumptionResult['overall_score_impact'];
        $this->save();
    }


    public function calculateSectorChange($sector)
    {
        // Retrieve current values for the sector
        $currentLevel = floor($this->{$sector});            // Current level of the sector

        $assignedPopulation = $this->{$sector . '_population'}; // Population assigned to the sector
        $seasonalAdjustment = $this->getSeasonalAdjustment($sector); // Adjustment based on season, if applicable
        $incrementRate = config('game.stats.increment_rate', 0.05); // Increment rate for gradual change

        $increased = '';

        // Calculate change for the next tick
        if ($assignedPopulation == $currentLevel) {
            $change = 0; // No change if assigned population matches current level
        } elseif ($assignedPopulation > $currentLevel) {
            // Gradual increase if assigned population exceeds current level
            $change = ($assignedPopulation - $currentLevel) * $incrementRate;
            $increased = '+';
        } else {
            // Gradual decrease if assigned population is below current level
            $change = ($assignedPopulation - $currentLevel) * $incrementRate;
        }

        // Apply seasonal adjustment
        $newLevel = $currentLevel + $change + $seasonalAdjustment;

        // Ensure new level does not go below 0
        $newLevel = max(0, $newLevel);

        return [
            'change' => $change,
            'new_level' => $newLevel,
            'seasonal_adjustment' => $seasonalAdjustment,
            'icon' => $increased,
        ];
    }

// Example function to retrieve seasonal adjustment (customize as needed)
    protected function getSeasonalAdjustment($sector)
    {
        $seasonSetting = GameSetting::where('name', 'season')->value('value');
        $currentSeason = Season::from($seasonSetting);

        // Apply specific seasonal effect based on sector
        return match($currentSeason) {
            Season::SPRING => ($sector === 'education') ? 1 : 0,
            Season::SUMMER => ($sector === 'health') ? 1 : 0,
            Season::AUTUMN => ($sector === 'safety') ? 1 : 0,
            Season::WINTER => ($sector === 'economy') ? 1 : 0,
            default => 0,
        };
    }

//    public function getStatChange($stat)
//    {
//        $increment_rate = config('game.stats.increment_rate');
//
//        // Current population and level
//        $pop = $this->{$stat . '_population'};
//        $currentLevel = $this->{$stat};
//
//        // Calculate the population threshold
//        $populationThreshold = max(pow($currentLevel, 0.5), 1);
//
//        $increased = false;
//
//        // Determine if there is excess or deficit population
//        if ($pop > $populationThreshold) {
//            // Calculate excess population
//            $excessPopulation = $pop - $populationThreshold;
//            // Calculate increase change
//            $change = round($increment_rate * ($excessPopulation / $populationThreshold), 2);
//            $newLevel = $currentLevel + $change;
//            $increased = true;
//        } else {
//            // Calculate deficit population
//            $deficitPopulation = $populationThreshold - $pop;
//
//            // Increase the impact of the deficit based on the current level
//            $decrease_factor = min($deficitPopulation / $populationThreshold, 1) * $currentLevel;
//            $change = round($increment_rate * ($deficitPopulation / $populationThreshold) * $decrease_factor, 2);
//
//            // Ensure new level does not go below 0
//            $newLevel = max(0, $currentLevel - $change);
//        }
//
//        // Get the current season and its effect
//        $seasonSetting = GameSetting::where('name', 'season')->value('value');
//        $currentSeason = Season::from($seasonSetting);
//        $seasonEffect = $currentSeason->effect();
//
////        // Apply season effect if it matches the stat
////        if (str_contains(strtolower($seasonEffect), $stat)) {
////            $change += 1;
////            $newLevel += 1;
////        }
//
//        // Ensure new level does not go below 0
//        return [
//            'change' => $change,
//            'new_level' => max(0, $newLevel),
//            'icon' => $increased ? '+' : '-',
//            'season_effect' => $seasonEffect
//        ];
//    }


    public function calculateStatsChange()
    {

        $stats = config('game.stats.categories');

        $totalChange = 0;

        foreach ($stats as $stat) {

            $statIncrease = $this->calculateSectorChange($stat);

            $newLevel = $statIncrease['new_level'];

            if ($newLevel != 0) {
                Log::debug("{$this->id} updating {$stat} from {$this->{$stat}} to {$newLevel}");
            }

            $this->{$stat} = $newLevel;

            $totalChange += $statIncrease['change'];

        }

        $averageChange = $totalChange / count($stats);

        $this->overall += $averageChange;

        $this->save();

    }

    public function updatePopulationAllocation(int $economy,int $health, int $safety, int $education)
    {


        $totalToAllocate = $economy + $health + $safety + $education;

        $currentAssignedPopulation = $this->getSectorAssignedPopulationAttribute();

        $availablePopulationRequired = $totalToAllocate - $currentAssignedPopulation;


       if ($availablePopulationRequired > $this->available_population) {
           // Not enough pop
           return false;
       }


       if ($availablePopulationRequired > 0 && $this->available_population <= 0) {
           return false;
       }

        if($availablePopulationRequired > 0) {
            $this->available_population -= $availablePopulationRequired;
        } else if ($availablePopulationRequired < 0) {
            $this->available_population += abs($availablePopulationRequired);
        }



        $this->economy_population = $economy;
        $this->health_population = $health;
        $this->safety_population = $safety;
        $this->education_population = $education;

        $this->save();


    }

}
