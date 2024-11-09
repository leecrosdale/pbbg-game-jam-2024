<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Government extends Model
{

    use HasUuid;
    use HasFactory;

    protected $casts = [
        'economy' => 'integer',
        'health' => 'integer',
        'safety' => 'integer',
        'education' => 'integer',
    ];

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


    public function calculatePopulationChange()
    {

        // Define scaling factor for population adjustment
        $scaling_factor = 0.05; // Adjust this factor to control rate of population growth

        // Determine population change based on overall level
        // This example increases population slightly for each level, more at higher levels
        $population_change = round($this->overall * $scaling_factor);

        // Apply the population change, ensuring it doesn't go below 0
        $new_population = max(0, $this->available_population + $population_change);

        // Update population
        $this->available_population = $new_population;

    }

    public function calculateResourceChange()
    {

    }

    public function getStatChange($stat)
    {

        $increment_rate = config('game.stats.increment_rate');

        // Current population and level
        $pop = $this->{$stat . '_population'};
        $currentLevel = $this->{$stat};

        // Calculate the population threshold
        $populationThreshold = max(pow($currentLevel, 2), 1);

        $increased = false;

        // Determine if there is excess or deficit population
        if ($pop > $populationThreshold) {
            // Calculate excess population
            $excessPopulation = $pop - $populationThreshold;
            // Calculate increase change
            $change = round($increment_rate * ($excessPopulation / $populationThreshold), 2);
            $newLevel = $currentLevel + $change;
            $increased = true;
        } else {
            // Calculate deficit population
            $deficitPopulation = $populationThreshold - $pop;

            // Increase the impact of the deficit based on the current level
            // This factor amplifies the decrease for higher levels with low population
            $decrease_factor = min($deficitPopulation / $populationThreshold, 1) * $currentLevel; // Ensure it doesn’t exceed the current level
            $change = round($increment_rate * ($deficitPopulation / $populationThreshold) * $decrease_factor, 2);

            // Ensure new level does not go below 0
            $newLevel = max(0, $currentLevel - $change);
        }

        // Ensure new level does not go below 0
        return [
            'change' => $change,
            'new_level' => max(0, $newLevel),
            'icon' => $increased ? '+' : '-'
        ];
    }

    public function calculateStatsChange()
    {

        $stats = config('game.stats.categories');

        $totalChange = 0;

        foreach ($stats as $stat) {

            $statIncrease = $this->getStatChange($stat);

            $newLevel = $statIncrease['new_level'];

            if ($newLevel != 0) {
                dump("{$this->id} updating {$stat} from {$this->{$stat}} to {$newLevel}");
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
