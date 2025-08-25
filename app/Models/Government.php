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

    public function policies()
    {
        return $this->hasMany(Policy::class);
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
        $baseInterestRate = 0.005; // Reduced from 0.01
        $scalingFactor = 0.005; // Reduced from 0.01
        $interestRate = $baseInterestRate + ($this->overall * $scalingFactor);
        $interestRate = min($interestRate, config('game.settings.interest_rate_cap', 0.05)); // Use config cap
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
        // Get housing capacity
        $housingCapacity = $this->government_infrastructures()->whereHas('infrastructure', function($q) {
            $q->where('type', InfrastructureType::HOUSING->value);
        })->sum('level') * 10; // Each housing level supports 10 people

        // Calculate current population
        $currentPopulation = $this->population;
        
        // Calculate potential growth based on available housing
        $potentialGrowth = max(0, $housingCapacity - $currentPopulation);
        
        // Apply growth rate limit to prevent exponential growth
        $maxGrowthRate = config('game.settings.max_population_growth_rate', 0.1);
        $maxGrowth = $currentPopulation * $maxGrowthRate;
        
        // Also consider resource availability for population growth
        $resourceFactor = $this->calculatePopulationGrowthResourceFactor();
        
        $populationChange = min($potentialGrowth, $maxGrowth) * $resourceFactor;
        
        return round($populationChange);
    }

    private function calculatePopulationGrowthResourceFactor()
    {
        // Population growth is limited by resource availability
        $requiredResources = [
            'food' => 0.5,
            'medicine' => 0.1,
            'electricity' => 0.3
        ];
        
        $minFactor = 1.0;
        
        foreach ($requiredResources as $resource => $required) {
            $available = $this->getResourceAmount($resource);
            $factor = $available >= $required ? 1.0 : max(0.1, $available / $required);
            $minFactor = min($minFactor, $factor);
        }
        
        return $minFactor;
    }

    private function getResourceAmount($resourceName)
    {
        $resource = $this->government_resources()->whereHas('resource', function($q) use ($resourceName) {
            $q->where('name', $resourceName);
        })->first();
        
        return $resource ? $resource->amount : 0;
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
            $resourceGenerated = $infrastructure->next_tick;

            // Apply resource efficiency bonuses based on sector levels
            $resourceGenerated = $this->applyResourceEfficiencyBonus($infrastructure->infrastructure->resource_type, $resourceGenerated);

            // Store the resource generated in the result array
            $resourceType = $infrastructure->infrastructure->resource_type;
            $resourceChanges[$resourceType] = ($resourceChanges[$resourceType] ?? 0) + $resourceGenerated;
        }

        foreach ($resourceChanges as $resource => $amount) {
            if ($amount <= 0) continue;

            $this->changeResourceAmount($resource, $amount);
        }

        return $resourceChanges;
    }

    private function applyResourceEfficiencyBonus($resourceType, $amount)
    {
        // Apply sector-based efficiency bonuses
        $bonus = 1.0;
        
        switch ($resourceType) {
            case 'food':
                $bonus += $this->health * 0.02; // Health sector improves food production
                break;
            case 'electricity':
                $bonus += $this->economy * 0.02; // Economy sector improves power generation
                break;
            case 'medicine':
                $bonus += $this->education * 0.02; // Education sector improves medicine production
                break;
            case 'clothing':
                $bonus += $this->economy * 0.01; // Economy sector improves clothing production
                break;
            case 'happiness':
                $bonus += $this->safety * 0.02; // Safety sector improves happiness
                break;
        }
        
        return $amount * $bonus;
    }

    public function calculateHappinessEffect()
    {
        // Get happiness from government resources
        $happinessResource = $this->government_resources()->whereHas('resource', function($q) {
            $q->where('name', 'happiness');
        })->first();
        
        $happiness = $happinessResource ? $happinessResource->amount : 0;
        $population = $this->population;
        
        if ($happiness < 10) {
            // Low happiness causes population decline and score penalty
            $this->overall -= 1;
            $this->available_population = max(0, $this->available_population - 1);
        } elseif ($happiness > 80) {
            // High happiness provides bonuses
            $this->overall += 0.5;
        }
    }

    public function checkForCrisis()
    {
        $crisisChance = 0.05; // 5% chance per tick
        
        if (rand(1, 100) <= $crisisChance * 100) {
            $this->triggerRandomCrisis();
        }
    }

    private function triggerRandomCrisis()
    {
        $crises = [
            'economic_recession' => function() {
                $this->economy = max(0, $this->economy - 2);
                $this->money = max(0, $this->money * 0.8);
                return 'Economic recession hit! Economy -2, Money -20%';
            },
            'health_emergency' => function() {
                $this->health = max(0, $this->health - 2);
                $this->medicine = max(0, $this->medicine * 0.7);
                return 'Health emergency! Health -2, Medicine -30%';
            },
            'safety_incident' => function() {
                $this->safety = max(0, $this->safety - 2);
                $this->happiness = max(0, $this->happiness * 0.8);
                return 'Safety incident! Safety -2, Happiness -20%';
            },
            'education_crisis' => function() {
                $this->education = max(0, $this->education - 2);
                return 'Education crisis! Education -2';
            }
        ];
        
        $crisis = array_rand($crises);
        $message = $crises[$crisis]();
        
        // Log the crisis
        \Log::info("Crisis triggered for government {$this->id}: {$message}");
        
        return $message;
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
        
        if (!$resource) {
            // Log the missing resource and return early
            \Log::warning("Resource '{$resourceType}' not found in database");
            return;
        }
        
        $governmentResource = $this->government_resources()->firstOrCreate(['resource_id' => $resource->id]);
        
        // Apply storage capacity limits
        $storageCapacity = config("game.resources.storage_capacity.{$resourceType}", 1000);
        $newAmount = $governmentResource->amount + $amount;
        $governmentResource->amount = min($newAmount, $storageCapacity);
        
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

        $data =  [
            'change' => $change,
            'new_level' => $newLevel,
            'seasonal_adjustment' => $seasonalAdjustment,
            'icon' => $increased,
        ];

        return $data;
    }

// Example function to retrieve seasonal adjustment (customize as needed)
    protected function getSeasonalAdjustment($sector)
    {
        $seasonSetting = GameSetting::where('name', 'season')->value('value');
        $currentSeason = Season::from($seasonSetting);
        $multiplier = config('game.settings.seasonal_impact_multiplier', 0.5);

        // Apply specific seasonal effect based on sector with reduced impact
        return match($currentSeason) {
            Season::SPRING => ($sector === 'education') ? 0.5 * $multiplier : 0,
            Season::SUMMER => ($sector === 'health') ? 0.5 * $multiplier : 0,
            Season::AUTUMN => ($sector === 'safety') ? 0.5 * $multiplier : 0,
            Season::WINTER => ($sector === 'economy') ? 0.5 * $multiplier : 0,
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

        // Generate money from sector levels
        $moneyFromSectors = config('game.economy.money_from_sectors', 10);
        $sectorMoney = ($this->economy + $this->health + $this->safety + $this->education) * $moneyFromSectors;
        $this->money += $sectorMoney;
        
        // Apply infrastructure maintenance costs
        $maintenanceCost = $this->calculateInfrastructureMaintenanceCost();
        $this->money = max(0, $this->money - $maintenanceCost); // Prevent negative money
        
        $this->overall += $totalChange;
        $this->save();
    }

    private function calculateInfrastructureMaintenanceCost()
    {
        $maintenanceRate = config('game.economy.infrastructure_maintenance_cost', 0.01);
        $totalCost = 0;
        
        foreach ($this->government_infrastructures as $infrastructure) {
            $baseCost = $infrastructure->infrastructure->cost;
            $maintenanceCost = $baseCost * $maintenanceRate * $infrastructure->level;
            $totalCost += $maintenanceCost;
        }
        
        return $totalCost;
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
