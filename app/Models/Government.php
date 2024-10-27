<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Government extends Model
{

    use HasUuid;
    use HasFactory;


    public function government_infrastructures()
    {
        return $this->hasMany(GovernmentInfrastructure::class);
    }

    public function government_resources()
    {
        return $this->hasMany(GovernmentResource::class);
    }


    public function getPopulationAttribute()
    {

        $population = $this->education_population + $this->health_population + $this->safety_population + $this->economy_population;
        $population += $this->government_infrastructures()->sum('population');

        return $population;
    }


    public function calculatePopulationChange()
    {

    }

    public function calculateResourceChange()
    {

    }

    public function calculateStatsChange()
    {

        $stats = [
            'economy', 'health', 'safety', 'education'
        ];

        $base_threshold = 10;
        $increment_rate = 0.1; // This is the fraction added per tick if over the threshold

        $totalChange = 0;

        foreach ($stats as $stat) {



            // Get the allocated population and the current level
            $pop = $this->{$stat . '_population'};
            $current_level = $this->{$stat};

            // Calculate the dynamic population threshold based on the current level
            $population_threshold = pow($current_level, 2);

            // Calculate the excess population above the threshold
            $excess_population = max(0, $pop - $population_threshold);

            // Determine a smooth increment rate, e.g., each tick gives a 0.1 increment per threshold exceeded

            $change = $increment_rate * ($excess_population / $population_threshold);

            // Update the stat by adding the calculated change
            $new_level = $current_level + $change;

            // Only update the level if it reaches a whole number (increase or decrease)
            $this->{$stat} = $new_level;


            if ($new_level != 0) {
                dump("{$this->id} updating {$stat} from {$current_level} to {$new_level}");
            }

            $totalChange += $change;

//            dump([$this->id, $stat, $pop, $current_level, $population_threshold, $change, $new_level, floor($new_level)]);

        }

        $averageChange = $totalChange / count($stats);

        $this->overall += $averageChange;

        $this->save();

    }

}
